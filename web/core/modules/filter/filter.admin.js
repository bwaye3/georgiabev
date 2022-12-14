/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/
(function ($, Drupal) {
  Drupal.behaviors.filterStatus = {
    attach: function attach(context, settings) {
      var $context = $(context);
      once('filter-status', '#filters-status-wrapper input.form-checkbox', context).forEach(function (checkbox) {
        var $checkbox = $(checkbox);
        var $row = $context.find("#".concat($checkbox.attr('id').replace(/-status$/, '-weight'))).closest('tr');
        var $filterSettings = $context.find("[data-drupal-selector='".concat($checkbox.attr('id').replace(/-status$/, '-settings'), "']"));
        var filterSettingsTab = $filterSettings.data('verticalTab');
        $checkbox.on('click.filterUpdate', function () {
          if ($checkbox.is(':checked')) {
            $row.show();
            if (filterSettingsTab) {
              filterSettingsTab.tabShow().updateSummary();
            } else {
              $filterSettings.show();
            }
          } else {
            $row.hide();
            if (filterSettingsTab) {
              filterSettingsTab.tabHide().updateSummary();
            } else {
              $filterSettings.hide();
            }
          }
          Drupal.tableDrag['filter-order'].restripeTable();
        });
        if (filterSettingsTab) {
          filterSettingsTab.details.drupalSetSummary(function () {
            return $checkbox.is(':checked') ? Drupal.t('Enabled') : Drupal.t('Disabled');
          });
        }
        $checkbox.triggerHandler('click.filterUpdate');
      });
    }
  };
})(jQuery, Drupal);