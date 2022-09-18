<?php
function ziston_form_alter(&$form, $form_state, $form_id) {
  // User form (Login, Register or Forgot password)

  if (strpos($form_id, 'user_login') !== FALSE || strpos($form_id, 'user_register') !== FALSE || strpos($form_id, 'user_pass') !== FALSE) {
    $form['actions']['submit']['#attributes']['class'][] = 'button--primary';
  }

  // Adding button/links to Register and Forgot password.
  if (strpos($form_id, 'user_login') !== FALSE) {
    // Move actions before new elements.
    $form['actions']['#weight'] = '98';

    // Add new class to submit button.
    $form['actions']['submit']['#attributes']['class'][] = 'button-login';

    // New wrapper.
    $form['more-links'] = [
      '#type' => 'container',
      '#weight' => '99',
      '#attributes' => ['class' => ['more-links']],
    ];

    // Register button.
    if (\Drupal::config('user.settings')->get('register') != UserInterface::REGISTER_ADMINISTRATORS_ONLY) {
      $form['more-links']['register_button'] = [
        '#type' => 'link',
        '#url' => Url::fromRoute('user.register'),
        '#title' => t('Create new account'),
        '#attributes' => [
          'class' => [
            'register-button',
            'button',
            'button--secondary',
          ],
        ],
        '#weight' => '1',
      ];
    }

    // Forgot password link.
    $form['more-links']['forgot_password_link'] = [
      '#type' => 'link',
      '#url' => Url::fromRoute('user.pass'),
      '#title' => t('Forgot your password?'),
      '#attributes' => ['class' => ['link', 'forgot-password-link']],
      '#weight' => '2',
    ];
  }

  // Changing name of Reset button.
  if (strpos($form_id, 'user_pass') !== FALSE) {
    $form['actions']['submit']['#value'] = t('Reset');
  }
}