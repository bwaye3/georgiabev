<?php

namespace Drupal\Tests\mailchimp_signup\Functional;

use Drupal\mailchimp_signup\Entity\MailchimpSignup;
use Drupal\Tests\mailchimp\Functional\FunctionalMailchimpTestBase;
use Drupal\user\Entity\Role;

/**
 * Tests the Mailchimp signup form.
 *
 * @group mailchimp
 */
class MailchimpSignupFormTest extends FunctionalMailchimpTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['mailchimp_signup'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $rid = $this->adminUser->getRoles()[1];
    $this->grantPermissions(Role::load($rid), [
      'access mailchimp signup pages',
      'administer mailchimp signup entities',
    ]);
  }

  /**
   * Tests the basic behavior of the sign up form.
   */
  public function testSignUpForm() {
    $this->drupalLogin($this->lowUser);
    $this->drupalGet('/admin/config/services/mailchimp/signup');
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('/admin/config/services/mailchimp/signup');
    $this->assertSession()->statusCodeEquals(403);
    // This implicitly tests the _mailchimp_configuration_access_check check.
    \Drupal::configFactory()->getEditable('mailchimp.settings')
      ->set('api_key', 'TEST_KEY')
      ->set('test_mode', TRUE)
      ->save();
    $this->drupalGet('/admin/config/services/mailchimp/signup');
    $this->assertSession()->statusCodeEquals(200);
    // Create a signup form.
    $this->drupalGet('/admin/config/services/mailchimp/signup/add');
    $this->assertSession()->statusCodeEquals(200);
    $this->submitForm([
      'title' => 'My signup form',
      'id' => 'my_signup_form',
      'description' => 'Test description',
      'mode[1]' => TRUE,
      'mode[2]' => TRUE,
      'settings[submit_button]' => 'Sign up',
      'settings[path]' => 'newsletter/signup',
      'settings[confirmation_message]' => 'You have signed up',
      'settings[destination]' => '/home',
      'mc_lists[57afe96172]' => TRUE,
    ], 'Save');

    $this->assertSession()->addressEquals('/admin/config/services/mailchimp/signup');
    $this->assertSession()->pageTextContains('My signup form');
    // Visit/submit the signup form.
    $this->drupalLogin($this->lowUser);
    $this->drupalGet('/newsletter/signup');
    $this->assertSession()->statusCodeEquals(403);
    $rid = $this->lowUser->getRoles()[0];
    $this->grantPermissions(Role::load($rid), ['access mailchimp signup pages']);
    $this->drupalGet('/newsletter/signup');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('My signup form');
    $this->submitForm([
      'mergevars[EMAIL]' => 'admin@example.com',
    ], 'Sign up');
    $this->assertSession()->pageTextContains('You have signed up');
    $this->assertSession()->addressEquals('/home');

    /** @var \Drupal\Core\Block\BlockManager $manager */
    $manager = \Drupal::service('plugin.manager.block');
    $manager->clearCachedDefinitions();

    // Test the block.
    $this->assertSession()->pageTextNotContains('My signup block');
    $this->drupalPlaceBlock('mailchimp_signup_subscribe_block:my_signup_form', ['label' => 'My signup block']);
    $this->drupalGet('/foo');
    $this->assertSession()->pageTextContains('My signup block');
    $this->submitForm([
      'mergevars[EMAIL]' => 'admin@example.com',
    ], 'Sign up');
    $this->assertSession()->pageTextContains('You have signed up');
    $this->assertSession()->addressEquals('/home');
  }

  /**
   * Tests editing an existing signup form with minimal configuration.
   */
  public function testEditSignUpForm() {
    // Configure API key.
    \Drupal::configFactory()->getEditable('mailchimp.settings')
      ->set('api_key', 'TEST_KEY')
      ->set('test_mode', TRUE)
      ->save();

    // Programmatically add a signup form with only required fields set.
    MailchimpSignup::create([
      'title' => 'My signup form',
      'id' => 'my_signup_form',
      'mc_lists' => [
        '57afe96172' => '57afe96172',
      ],
      'mode' => MAILCHIMP_SIGNUP_BLOCK,
      'mergefields' => [
        'EMAIL' => TRUE,
      ],
      'settings' => [
        'submit_button' => 'Sign up',
        'mergefields' => [
          'EMAIL' => 'O:8:"stdClass":8:{s:3:"tag";s:5:"EMAIL";s:4:"name";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:13:"Email Address";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:4:"type";s:5:"email";s:8:"required";b:1;s:13:"default_value";s:0:"";s:6:"public";b:1;s:13:"display_order";i:1;s:7:"options";O:8:"stdClass":1:{s:4:"size";i:25;}}',
        ],
      ],
    ])->save();

    // Login as an admin.
    $this->drupalLogin($this->adminUser);

    // Save the signup form without changing anything.
    $this->drupalGet('/admin/config/services/mailchimp/signup/my_signup_form');
    $this->submitForm([], 'Save');
    $this->assertSession()->pageTextContains('Signup Forms');
  }

  /**
   * Tests subscribing on a signup form that is minimally configured.
   */
  public function testSubscribeWithMinimalConfiguredSignUpForm() {
    // Configure API key.
    \Drupal::configFactory()->getEditable('mailchimp.settings')
      ->set('api_key', 'TEST_KEY')
      ->set('test_mode', TRUE)
      ->save();

    // Programmatically add a signup form with only required fields set.
    MailchimpSignup::create([
      'title' => 'My signup form',
      'id' => 'my_signup_form',
      'mc_lists' => [
        '57afe96172' => '57afe96172',
      ],
      'mode' => MAILCHIMP_SIGNUP_PAGE,
      'mergefields' => [
        'EMAIL' => TRUE,
      ],
      'settings' => [
        'path' => 'newsletter/signup',
        'submit_button' => 'Sign up',
        'mergefields' => [
          'EMAIL' => 'O:8:"stdClass":8:{s:3:"tag";s:5:"EMAIL";s:4:"name";O:48:"Drupal\Core\StringTranslation\TranslatableMarkup":3:{s:9:" * string";s:13:"Email Address";s:12:" * arguments";a:0:{}s:10:" * options";a:0:{}}s:4:"type";s:5:"email";s:8:"required";b:1;s:13:"default_value";s:0:"";s:6:"public";b:1;s:13:"display_order";i:1;s:7:"options";O:8:"stdClass":1:{s:4:"size";i:25;}}',
        ],
      ],
    ])->save();

    // Flush caches to make the form available.
    drupal_flush_all_caches();

    // Submit the signup form.
    $this->drupalLogin($this->lowUser);
    $rid = $this->lowUser->getRoles()[0];
    $this->grantPermissions(Role::load($rid), ['access mailchimp signup pages']);
    $this->drupalGet('/newsletter/signup');
    $this->submitForm([
      'mergevars[EMAIL]' => 'admin@example.com',
    ], 'Sign up');
  }

}
