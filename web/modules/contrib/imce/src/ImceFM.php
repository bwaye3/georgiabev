<?php

namespace Drupal\imce;

use Drupal\Component\Render\MarkupInterface;
use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Imce File Manager.
 */
class ImceFM {

  use StringTranslationTrait;

  /**
   * File manager configuration.
   *
   * @var array
   */
  public $conf;

  /**
   * Active user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  public $user;

  /**
   * Active request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  public $request;

  /**
   * Current validation status for the configuration.
   *
   * @var bool
   */
  public $validated;

  /**
   * Currently selected items.
   *
   * @var array
   */
  public $selection = [];

  /**
   * Folder tree.
   *
   * @var array
   */
  public $tree = [];

  /**
   * Active folder.
   *
   * @var \Drupal\imce\ImceFolder
   */
  public $activeFolder;

  /**
   * Response data.
   *
   * @var array
   */
  public $response = [];

  /**
   * Status messages.
   *
   * @var array
   */
  public $messages = [];

  /**
   * Image style for thumbnails.
   *
   * @var \Drupal\image\Entity\ImageStyle
   */
  private $thumbnailStyle;

  /**
   * Constructs the file manager.
   *
   * @param array $conf
   *   File manager configuration.
   * @param \Drupal\Core\Session\AccountProxyInterface $user
   *   The active user.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The active request that contains parameters for file manager operations.
   */
  public function __construct(array $conf, AccountProxyInterface $user = NULL, Request $request = NULL) {
    $this->conf = $conf;
    $this->user = $user ?: Imce::currentUser();
    $this->request = $request;
    $this->init();
  }

  /**
   * Initializes the file manager.
   *
   * Initializes the file manager by validating
   *   the current configuration and request.
   */
  protected function init() {
    if (!isset($this->validated)) {
      // Create the root.
      $root = $this->createItem('folder', '.');
      $root->setPath('.');
      // Check initialization error.
      $error = $this->getInitError();
      if ($error) {
        $this->setMessage($error);
      }
      else {
        $this->initSelection();
      }
      $this->validated = $error === FALSE;
    }
  }

  /**
   * Performs the initialization and returns the first error message.
   */
  protected function getInitError() {
    $conf = &$this->conf;
    // Check configuration options.
    $keys = ['folders', 'root_uri'];
    foreach ($keys as $key) {
      if (empty($conf[$key])) {
        return $this->t('Missing configuration %key.', ['%key' => $key]);
      }
    }
    // Check root.
    $root_uri = $conf['root_uri'];
    if (!is_dir($root_uri)) {
      if (!mkdir($root_uri, $this->getConf('chmod_directory', 0775), TRUE)) {
        return $this->t('Missing root folder.');
      }
    }
    // Check and add predefined folders.
    foreach ($conf['folders'] as $path => $folder_conf) {
      $path = (string) $path;
      $uri = $this->createUri($path);
      if (is_dir($uri) || mkdir($uri, $this->getConf('chmod_directory', 0775), TRUE)) {
        $this->addFolder($path, $folder_conf);
      }
      else {
        unset($conf['folders'][$path]);
      }
    }
    if (!$conf['folders']) {
      return $this->t('No valid folder definitions found.');
    }
    // Check and set active folder if provided.
    $path = $this->getPost('active_path');
    if (isset($path) && $path !== '') {
      $folder = $this->checkFolder($path);
      if ($folder) {
        $this->activeFolder = $folder;
        // Remember active path.
        if ($this->user->isAuthenticated()) {
          if (
            !isset($conf['folders'][$path])
            || count($conf['folders']) > 1
            || $folder->getPermission('browse_subfolders')
          ) {
            $this->request->getSession()->set('imce_active_path', $path);
          }
        }
      }
      else {
        return $this->t('Invalid active folder path: %path.', ['%path' => $path]);
      }
    }
    return FALSE;
  }

  /**
   * Initiates the selection with a list of user provided item paths.
   */
  protected function initSelection() {
    $paths = $this->getPost('selection');
    if ($paths && is_array($paths)) {
      foreach ($paths as $path) {
        $item = $this->checkItem($path);
        if ($item) {
          $item->select();
        }
        // Remove non-existing paths from js.
        else {
          $this->removePathFromJs($path);
        }
      }
    }
  }

  /**
   * Runs an operation on the file manager.
   */
  public function run($op = NULL) {
    if (!$this->validated) {
      return FALSE;
    }
    // Check operation.
    if (!isset($op)) {
      $op = $this->getOp();
    }
    if (!$op || !is_string($op)) {
      return FALSE;
    }
    // Validate security token.
    $token = $this->getPost('token');
    if (!$token || $token !== $this->getConf('token')) {
      $this->setMessage($this->t('Invalid security token.'));
      return FALSE;
    }
    // Let plugins handle the operation.
    $return = Imce::service('plugin.manager.imce.plugin')->handleOperation($op, $this);
    if ($return === FALSE) {
      $this->setMessage($this->t('Invalid operation %op.', ['%op' => $op]));
    }
    return $return;
  }

  /**
   * Adds a folder to the tree.
   */
  public function addFolder($path, array $conf = NULL) {
    // Existing.
    $folder = $this->getFolder($path);
    if ($folder) {
      if (isset($conf)) {
        $folder->setConf($conf);
      }
      return $folder;
    }
    // New. Append to the parent.
    $parts = Imce::splitPath($path);
    if ($parts) {
      [$parent_path, $name] = $parts;
      $parent = $this->addFolder($parent_path);
      if ($parent) {
        return $parent->addSubfolder($name, $conf);
      }
    }
  }

  /**
   * Get folder.
   *
   * @param string $path
   *   The patches folder.
   *
   * @return \Drupal\imce\ImceFolder
   *   Returns a folder from the tree.
   */
  public function getFolder($path) {
    return $this->tree[$path] ?? NULL;
  }

  /**
   * Checks if the user provided folder path is accessible.
   *
   * @param string $path
   *   The patches folder.
   *
   * @return \Drupal\imce\ImceFolder|null
   *   Returns the folder object with the path.
   */
  public function checkFolder($path) {
    if (is_array(Imce::folderInConf($path, $this->conf))) {
      return $this->addFolder($path);
    }
  }

  /**
   * Checks if the user provided file path is accessible.
   *
   * Returns the file object with the path.
   */
  public function checkFile($path) {
    $item = $this->checkItem($path);
    if ($item && $item->type === 'file') {
      return $item;
    }
  }

  /**
   * Checks the existence of a user provided item path.
   *
   * Scans the parent folder and returns the item object if it is accessible.
   */
  public function checkItem($path) {
    $parts = Imce::splitPath($path);
    if ($parts) {
      $folder = $this->checkFolder($parts[0]);
      if ($folder) {
        return $folder->checkItem($parts[1]);
      }
    }
  }

  /**
   * Creates and returns an imce file/folder object by name.
   */
  public function createItem($type, $name, $conf = NULL) {
    $item = $type === 'folder' ? new ImceFolder($name, $conf) : new ImceFile($name);
    $item->setFm($this);
    return $item;
  }

  /**
   * Creates an uri from a relative path.
   */
  public function createUri($path) {
    return Imce::joinPaths($this->getConf('root_uri'), $path);
  }

  /**
   * Returns the current operation in the request.
   */
  public function getOp() {
    return $this->getPost('jsop');
  }

  /**
   * Returns value of a posted parameter.
   */
  public function getPost($key, $default = NULL) {
    if ($this->request) {
      $params = $this->request->request->all();
      return $params[$key] ?? $default;
    }
    return $default;
  }

  /**
   * Returns a configuration option.
   */
  public function getConf($key, $default = NULL) {
    return $this->conf[$key] ?? $default;
  }

  /**
   * Sets a configuration option.
   */
  public function setConf($key, $value) {
    $this->conf[$key] = $value;
  }

  /**
   * Checks if a permission exists in any of the predefined folders.
   */
  public function hasPermission($permission) {
    return Imce::permissionInConf($permission, $this->conf);
  }

  /**
   * Returns the contents of a directory.
   */
  public function scanDir($diruri, array $options = []) {
    $options += ['name_filter' => $this->getNameFilter()];
    $scanner = $this->getConf('scanner', 'Drupal\imce\Imce::scanDir');
    return call_user_func($scanner, $diruri, $options);
  }

  /**
   * Returns name filtering regexp.
   */
  public function getNameFilter() {
    return Imce::nameFilterInConf($this->conf);
  }

  /**
   * Returns currently selected items.
   */
  public function getSelection() {
    return $this->selection;
  }

  /**
   * Returns selected items grouped by parent folder path.
   */
  public function groupSelection() {
    return $this->groupItems($this->selection);
  }

  /**
   * Groups the items by parent path and type.
   */
  public function groupItems(array $items) {
    $group = [];
    foreach ($items as $item) {
      $path = $item->parent->getPath();
      $type = $item->type == 'folder' ? 'subfolders' : 'files';
      $group[$path][$type][$item->name] = $item;
    }
    return $group;
  }

  /**
   * Selects an item.
   */
  public function selectItem(ImceItem $item) {
    if (!$item->selected && $item->parent) {
      $item->selected = TRUE;
      $this->selection[] = $item;
    }
  }

  /**
   * Deselects an item.
   */
  public function deselectItem(ImceItem $item) {
    if ($item->selected) {
      $item->selected = FALSE;
      $index = array_search($item, $this->selection);
      if ($index !== FALSE) {
        array_splice($this->selection, $index, 1);
      }
    }
  }

  /**
   * Add an item to the response.
   */
  public function addItemToJs(ImceItem $item) {
    $parent = $item->parent;
    if ($parent) {
      $path = $parent->getPath();
      if (isset($path)) {
        $name = $item->name;
        $uri = $item->getUri();
        if ($item->type === 'folder') {
          $this->response['added'][$path]['subfolders'][$name] = $this->getFolderProperties($uri);
        }
        else {
          $props = $this->getFileProperties($uri);
          if (isset($item->uuid)) {
            $props['uuid'] = $item->uuid;
          }
          $this->response['added'][$path]['files'][$name] = $props;
        }
      }
    }
  }

  /**
   * Sets an item as removed in the response.
   */
  public function removeItemFromJs(ImceItem $item) {
    $this->removePathFromJs($item->getPath());
  }

  /**
   * Sets a path as removed in the response.
   */
  public function removePathFromJs($path) {
    if (isset($path)) {
      $this->response['removed'][] = $path;
    }
  }

  /**
   * Returns js properties of a file.
   */
  public function getFileProperties($uri) {
    $properties = [
      'date' => @filemtime($uri) ?: 0,
      'size' => @filesize($uri) ?: 0,
    ];
    // Some file systems need url altering for each file.
    if (!empty($this->conf['url_alter'])) {
      $properties['url'] = Imce::service('file_url_generator')->generateAbsoluteString($uri);
    }
    // Skip image properties if lazy_dimensions is enabled.
    if (!empty($this->conf['lazy_dimensions'])) {
      return $properties;
    }
    // Get image properties.
    $regexp = $this->conf['image_extensions_regexp'] ?? $this->imageExtensionsRegexp();
    if ($regexp && preg_match($regexp, $uri)) {
      $info = getimagesize($uri);
      if ($info) {
        $properties['width'] = $info[0];
        $properties['height'] = $info[1];
        $style = $this->getThumbnailStyle();
        if ($style && strpos($uri, '/styles/') === FALSE) {
          $properties['thumbnail'] = $style->buildUrl($uri);
        }
      }
    }
    return $properties;
  }

  /**
   * Returns thumbnail style.
   *
   * @return false|\Drupal\image\ImageStyleInterface
   *   Drupal ImageStyle entity.
   */
  public function getThumbnailStyle() {
    if (!isset($this->thumbnailStyle)) {
      $this->thumbnailStyle = FALSE;
      $style_name = $this->getConf('thumbnail_style');
      if ($style_name) {
        $this->thumbnailStyle = Imce::entityStorage('image_style')->load($style_name);
      }
    }
    return $this->thumbnailStyle;
  }

  /**
   * Returns js properties of a folder.
   */
  public function getFolderProperties($uri) {
    return ['date' => filemtime($uri)];
  }

  /**
   * Returns the response data.
   */
  public function getResponse() {
    $defaults = ['jsop' => $this->getOp()];
    $messages = $this->getMessages();
    if ($messages) {
      $defaults['messages'] = $messages;
    }
    $folder = $this->activeFolder;
    if ($folder) {
      $defaults['active_path'] = $folder->getPath();
    }
    return $this->response + $defaults;
  }

  /**
   * Adds response data.
   */
  public function addResponse($key, $value) {
    return $this->response[$key] = $value;
  }

  /**
   * Returns the status messages.
   */
  public function getMessages() {
    // Get drupal messages.
    $messenger = Imce::messenger();
    $messages = $messenger->all();
    $messenger->deleteAll();
    foreach ($messages as &$group) {
      foreach ($group as &$message) {
        $message = $message instanceof MarkupInterface ? $message . '' : Html::escape($message);
      }
    }
    // Merge with file manager messages.
    return array_merge_recursive($messages, $this->messages);
  }

  /**
   * Sets a status message.
   */
  public function setMessage($message, $type = 'error') {
    $this->messages[$type][] = $message;
  }

  /**
   * Checks parent folder permissions of the given items.
   */
  public function validatePermissions(array $items, $file_perm = NULL, $subfolder_perm = NULL) {
    foreach ($this->groupItems($items) as $path => $content) {
      $parent = $this->getFolder($path);
      // Parent contains files but does not have the file permission.
      if (!empty($content['files'])) {
        if (!isset($file_perm) || !$parent->getPermission($file_perm)) {
          return FALSE;
        }
      }
      // Parent contains subfolders but does not have the subfolder permission.
      if (!empty($content['subfolders'])) {
        if (!isset($subfolder_perm) || !$parent->getPermission($subfolder_perm)) {
          return FALSE;
        }
      }
    }
    return TRUE;
  }

  /**
   * Checks the existence of a predefined path.
   */
  public function validatePredefinedPath(array $items, $silent = FALSE) {
    foreach ($items as $item) {
      if ($item->type !== 'folder') {
        continue;
      }
      $folder = $item->hasPredefinedPath();
      if ($folder) {
        if (!$silent) {
          $this->setMessage($this->t(
            '%path is a predefined path and can not be modified.',
            ['%path' => $folder->getPath()]
          ));
        }
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Validates a file name.
   */
  public function validateFileName($filename, $silent = FALSE) {
    if (!Imce::validateFileName($filename, $this->getNameFilter())) {
      if (!$silent) {
        $msg = $this->t('%filename is not allowed.', [
          '%filename' => $filename,
        ]);
        $this->setMessage($msg);
      }
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Validates min/max image dimensions.
   */
  public function validateDimensions(array $items, $width, $height, $silent = FALSE) {
    // Check min dimensions.
    if ($width < 1 || $height < 1) {
      return FALSE;
    }
    // Check max dimensions.
    $maxwidth = $this->getConf('maxwidth');
    $maxheight = $this->getConf('maxheight');
    if ($maxwidth && $width > $maxwidth || $maxheight && $height > $maxheight) {
      if (!$silent) {
        $this->setMessage($this->t(
          'Image dimensions must be smaller than %dimensions pixels.',
          ['%dimensions' => $maxwidth . 'x' . $maxwidth]
        ));
      }
      return FALSE;
    }
    return TRUE;
  }

  /**
   * Checks if all the selected items are images.
   */
  public function validateImageTypes(array $items, $silent = FALSE) {
    $regex = $this->imageExtensionsRegexp();
    foreach ($items as $item) {
      if ($item->type !== 'file' || !preg_match($regex, $item->name)) {
        if (!$silent) {
          $this->setMessage($this->t('%name is not a supported image type.', ['%name' => $item->name]));
        }
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * Builds and returns image extension regular expression.
   */
  public function imageExtensionsRegexp() {
    // Build only once.
    $regexp = &$this->conf['image_extensions_regexp'];
    if (!isset($regexp)) {
      $exts = trim($this->getConf('image_extensions', 'jpg jpeg png gif webp'));
      $regexp = $exts ? '/\.(' . preg_replace('/ +/', '|', $exts) . ')$/i' : FALSE;
    }
    return $regexp;
  }

  /**
   * Builds file manager page.
   */
  public function buildPage() {
    $page = [];
    $page['#attached']['library'][] = 'imce/drupal.imce';
    // Add meta for robots.
    $robots = [
      '#tag' => 'meta',
      '#attributes' => [
        'name' => 'robots',
        'content' => 'noindex,nofollow',
      ],
    ];
    $page['#attached']['html_head'][] = [$robots, 'robots'];
    // Disable cache.
    $page['#cache']['max-age'] = 0;
    // Run builders of available plugins.
    Imce::service('plugin.manager.imce.plugin')->buildPage($page, $this);
    // Add active path to the conf.
    $conf = $this->conf;
    if (!isset($conf['active_path'])) {
      $folder = $this->activeFolder;
      if ($folder) {
        $conf['active_path'] = $folder->getPath();
      }
      elseif ($this->request) {
        // Check $_GET['init_path'].
        $path = $this->request->query->get('init_path');
        if ($path && $this->checkFolder($path)) {
          $conf['active_path'] = $path;
        }
        // Check session.
        elseif ($this->user->isAuthenticated()) {
          $path = $this->request->getSession()->get('imce_active_path');
          if ($path) {
            if ($this->checkFolder($path)) {
              $conf['active_path'] = $path;
            }
          }
        }
      }
    }
    // Set initial messages.
    $messages = $this->getMessages();
    if ($messages) {
      $conf['messages'] = $messages;
    }
    $page['#attached']['drupalSettings']['imce'] = $conf;
    return $page;
  }

  /**
   * Builds and renders the file manager page.
   */
  public function buildRenderPage() {
    $page = $this->buildPage();
    return Imce::service('bare_html_page_renderer')->renderBarePage(
      $page,
      $this->t('File manager'),
      'imce_page',
      ['#show_messages' => FALSE]
    )->getContent();
  }

  /**
   * Returns a page response based on the current request.
   */
  public function pageResponse() {
    $request = $this->request;
    if (!$request) {
      return;
    }
    // Json request.
    if ($request->request->has('jsop')) {
      $this->run();
      $data = $this->getResponse();
      Imce::service('plugin.manager.imce.plugin')->alterJsResponse($data, $this);
      // Return html response if the flag is set.
      if ($request->request->get('return_html')) {
        return new Response('<html><body><textarea>' . Json::encode($data) . '</textarea></body></html>');
      }
      return new JsonResponse($data);
    }
    // Build and render the main page.
    return new Response($this->buildRenderPage());
  }

}
