<?php

namespace Drupal\change_author_action;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * ChangeAuthorAction.
 */
class ChangeAuthorAction {

  use StringTranslationTrait;

  /**
   * The messenger.
   */
  protected $messenger;

  /**
   * The string translation.
   */
  protected $stringTranslation;

  /**
   * Constructs a \Drupal\change_author_action\ChangeAuthorAction.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Messenger.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $stringTranslation
   *   String Translation.
   */
  public function __construct(MessengerInterface $messenger, TranslationInterface $stringTranslation) {
    $this->messenger = $messenger;
    $this->stringTranslation = $stringTranslation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('string_translation')
    );
  }
  /**
   * {@inheritdoc}
   */
  public static function updateFields($entities, $new_author, &$context) {
    $message = 'Changing author...';
    $results = [];
    foreach ($entities as $entity) {
      if($entity->getOwnerId() != $new_author){
        $entity->setOwnerId($new_author);
        $entity->setNewRevision();
        $entity->save();
      }else{
      }
    }
    $context['message'] = $message;
    $context['results'] = $results;
  }

  /**
   * {@inheritdoc}
   */
  public static function changeAuthorActionFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
//      $message = \Drupal::translation()->formatPlural(
//        count($results['changed_author']),
//        'One operations processed.', '@count authors have been changed.'
//      );
    }
    else {
      $message = $this->t('Finished with an error.');
      $this->messenger->addStatus($message);
    }
  }

}
