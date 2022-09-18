<?php
use Drupal\Core\Template\Attribute;
use Drupal\user\UserInterface;
use Drupal\Core\Url;

function ziston_form_views_exposed_form_alter(array &$form) {
	//You need to verify the id
	global $base_url;
	
	$form['sort_by']['#weight'] = '-3';
	$form['sort_order']['#weight'] = '-2';

	$form['title']['#attributes']['placeholder'] = $form['#info']['filter-title']['label'];
	unset($form['#info']['filter-title']['label']);

	foreach ($form['#info'] as $filter_info) {
		$filter = $filter_info['value'];
		if ($form[$filter]['#type'] == 'select') {
			$form[$filter]['#options']['All'] = $filter_info['label'];
			unset($form['#info']['filter-' . $filter]['label']);
		}
	}
	
	$listing_search_action = 'listings';
	$language =  \Drupal::languageManager()->getCurrentLanguage()->getID();
	$languagesAll =  \Drupal::languageManager()->getLanguages();
	if(count($languagesAll) > 1){
		if(theme_get_setting('listing_search_action' . $language)){
			$listing_search_action = theme_get_setting('listing_search_action' . $language);
		}
	}else{
		if(theme_get_setting('listing_search_action')){
			$listing_search_action = theme_get_setting('listing_search_action');
		}
	}

	switch ($form['#id']) {
		case 'views-exposed-form-listing-content-listing-filter-form':
			$form['#action'] = base_path() . $listing_search_action;
			break;
	}
}

/**
 * Implements hook_form_alter() to add classes to the search form.
 */
function ziston_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
	if (in_array($form_id, ['search_block_form', 'search_form'])) {
		$key = ($form_id == 'search_block_form') ? 'actions' : 'basic';
		if (!isset($form[$key]['submit']['#attributes'])) {
			$form[$key]['submit']['#attributes'] = new Attribute();
		}
		$form[$key]['submit']['#attributes']->addClass('search-form__submit');
	}
	if($form_id == 'node_listing_form'){
		$form['status']['widget']['value']['#default_value'] = 0;
	}

	//========== Form Login ==============
	if (strpos($form_id, 'user_login') !== FALSE || strpos($form_id, 'user_register') !== FALSE || strpos($form_id, 'user_pass') !== FALSE) {
		$form['actions']['submit']['#attributes']['class'][] = 'button--primary';
	}
	if (strpos($form_id, 'user_login') !== FALSE) {
		$form['actions']['#weight'] = '98';
		$form['actions']['submit']['#attributes']['class'][] = 'button-login';
		$form['more-links'] = [
			'#type' => 'container',
			'#weight' => '99',
			'#attributes' => ['class' => ['more-links']],
		];
		if (\Drupal::config('user.settings')->get('register') != UserInterface::REGISTER_ADMINISTRATORS_ONLY) {
			$form['more-links']['register_button'] = [
				'#type' => 'link',
				'#url' => Url::fromRoute('user.register'),
				'#title' => t('Create new account'),
				'#attributes' => [
					'class' => [
						'register-button',
						'button'
					],
				],
				'#weight' => '1',
			];
		}
		$form['more-links']['forgot_password_link'] = [
			'#type' => 'link',
			'#url' => Url::fromRoute('user.pass'),
			'#title' => t('Forgot your password?'),
			'#attributes' => ['class' => ['link', 'forgot-password-link']],
			'#weight' => '2',
		];
	}
	if (strpos($form_id, 'user_pass') !== FALSE) {
		$form['actions']['submit']['#value'] = t('Reset');
	}
}

function ziston_theme() {
   $theme['page__user__login'] = [
    	'template' => 'user/page--user--login',
    	'preprocess functions' => ['ziston_preprocess_login'],
  	];
  	$theme['page__user__password'] = [
    	'template' => 'user/page--user--password',
    	'preprocess functions' => ['ziston_preprocess_login'],
  	];
  	$theme['page__user__register'] = [
    	'template' => 'user/page--user--register',
    	'preprocess functions' => ['ziston_preprocess_login'],
  	];
  return $theme;
}

function ziston_preprocess_login(&$vars){
	$vars['background_login_image'] = base_path() . drupal_get_path('theme', 'ziston') . '/assets/images/bg-login.jpg';
}