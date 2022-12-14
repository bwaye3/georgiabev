/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/
(function ($, Drupal) {
  Drupal.behaviors.mediaFormSummaries = {
    attach: function attach(context) {
      $(context).find('.media-form-author').drupalSetSummary(function (context) {
        var nameInput = context.querySelector('.field--name-uid input');
        var name = nameInput && nameInput.value;
        var dateInput = context.querySelector('.field--name-created input');
        var date = dateInput && dateInput.value;
        if (name && date) {
          return Drupal.t('By @name on @date', {
            '@name': name,
            '@date': date
          });
        }
        if (name) {
          return Drupal.t('By @name', {
            '@name': name
          });
        }
        if (date) {
          return Drupal.t('Authored on @date', {
            '@date': date
          });
        }
      });
    }
  };
})(jQuery, Drupal);