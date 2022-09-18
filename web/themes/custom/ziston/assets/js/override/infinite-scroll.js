/**
 * @file
 * Infinite Scroll JS.
 */

(function ($, Drupal, debounce) {
  "use strict";

  // Cached reference to $(window).
  var $window = $(window);

  // The threshold for how far to the bottom you should reach before reloading.
  var scrollThreshold = 200;

  // The selector for the automatic pager.
  var automaticPagerSelector = '[data-drupal-views-infinite-scroll-pager="automatic"]';

  // The selector for both manual load and automatic pager.
  var pagerSelector = '[data-drupal-views-infinite-scroll-pager]';

  // The selector for the automatic pager.
  var contentWrapperSelectorPortfolio = '[data-drupal-views-infinite-scroll-content-wrapper-gvaloadmore]';

  var contentWrapperSelectorListings = '[data-drupal-views-infinite-scroll-content-wrapper-gvaloadmorelistings]';

  var contentWrapperSelector = '[data-drupal-views-infinite-scroll-content-wrapper]';

  // The event and namespace that is bound to window for automatic scrolling.
  var scrollEvent = 'scroll.views_infinite_scroll';

  /**
   * Insert a views infinite scroll view into the document.
   *
   * @param {jQuery} $newView
   *   New content detached from the DOM.
   */
  $.fn.infiniteScrollInsertView = function ($newView) {
    // Extract the view DOM ID from the view classes.
    var matches = /(js-view-dom-id-\w+)/.exec(this.attr('class'));

    if (!matches) {
      return;
    }

    var currentViewId = matches[1].replace('js-view-dom-id-', 'views_dom_id:');

    // Get the existing ajaxViews object.
    var view = Drupal.views.instances[currentViewId];
    // Remove once so that the exposed form and pager are processed on
    // behavior attach.
    view.$view.removeOnce('ajax-pager');
    view.$exposed_form.removeOnce('exposed-form');
    // Make sure infinite scroll can be reinitialized.
    var $existingPager = view.$view.find(pagerSelector);
    $existingPager.removeOnce('infinite-scroll');

    //-- Gavias Custom
    if($newView.find(contentWrapperSelectorPortfolio).length > 0){
      contentWrapperSelector = contentWrapperSelectorPortfolio;
    }

    if($newView.find(contentWrapperSelectorListings).length > 0){
      contentWrapperSelector = contentWrapperSelectorListings;
    }
    //-- End Gavias Custom

    var $newRows = $newView.find(contentWrapperSelector).children();
    var $newPager = $newView.find('.js-pager__items');

    // Add the new rows to existing view.
    
    //-- Gavias Custom
    if($newView.find(contentWrapperSelectorPortfolio).length > 0){
      view.$view.find(contentWrapperSelector).isotope('insert', $newRows);
      view.$view.find(contentWrapperSelector).imagesLoaded(function() {
        view.$view.find(contentWrapperSelector).isotope('layout');
      });
    }else if($newView.find(contentWrapperSelectorListings).length > 0){
      view.$view.find(contentWrapperSelector).append($newRows);
      var i = 0;
      $('.listing-items .listing-items-wrapper').each(function(){
        $(this).attr('data-marker', i);
        i = i + 1;
      });
      var map = L.DomUtil.get('listing-main-map');
      map.remove();
      $('.main-map-wrapper').html('<div class="map-full-height map-sticky listing-map-sticky"><div id="listing-main-map" class="listing-main-map"></div></div>');
      map_init();
      if (window.innerWidth < 992) {
         $('.map-sticky').trigger("sticky_kit:detach");
      } else {
         $('.map-sticky').stick_in_parent({
            offset_top: 100
         });
      }
      $('.listing-block').each(function(){
        $(this).magnificPopup({
          delegate: 'a.image-popup', 
          type: 'image',
          gallery: {
            enabled: true
          },
        });
      });

      $('.popup-video').magnificPopup({
        type: 'iframe',
        fixedContentPos: false
      });

      var marker_id = 0;
      $('.listing-items').each(function(){ 
        $(this).find('.item-columns .listing-item-wrapper').each(function(){
          $(this).attr('data-marker', marker_id);
          marker_id = marker_id + 1;
        });
      });
      map_action();
    }else{
      view.$view.find(contentWrapperSelector).append($newRows);
      
      $('.listing-block').each(function(){
        $(this).magnificPopup({
          delegate: 'a.image-popup', 
          type: 'image',
          gallery: {
            enabled: true
          },
        });
      });

      $('.popup-video').magnificPopup({
        type: 'iframe',
        fixedContentPos: false
      });

      $('.listing-items').each(function(){ 
        $(this).find('.item-columns .listing-item-wrapper').each(function(){
          $(this).attr('data-marker', marker_id);
          marker_id = marker_id + 1;
        });
      });
      if (typeof map_action !== 'undefined' && $.isFunction(map_action)) {
        map_action();
      }
    }
    //-- End Gavias Custom

    // Replace the pager link with the new link and ajaxPageState values.
    $existingPager.replaceWith($newPager);

    // Run views and VIS behaviors.
    Drupal.attachBehaviors(view.$view[0]);
  };

  /**
   * Handle the automatic paging based on the scroll amount.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Initialize infinite scroll pagers and bind the scroll event.
   * @prop {Drupal~behaviorDetach} detach
   *   During `unload` remove the scroll event binding.
   */
  Drupal.behaviors.views_infinite_scroll_automatic = {
    attach : function (context, settings) {
      $(context).find(automaticPagerSelector).once('infinite-scroll').each(function () {
        var $pager = $(this);
        $pager.addClass('visually-hidden');
        var isLoadNeeded = function () {
          return window.innerHeight + window.pageYOffset > $pager.offset().top - scrollThreshold;
        };
        $window.on(scrollEvent, debounce(function () {
          if (isLoadNeeded()) {
            $pager.find('[rel=next]').click();
            $window.off(scrollEvent);
          }
        }, 200));
        if (isLoadNeeded()) {
          $window.trigger(scrollEvent);
        }
      });
    },
    detach: function (context, settings, trigger) {
      // In the case where the view is removed from the document, remove it's
      // events. This is important in the case a view being refreshed for a reason
      // other than a scroll. AJAX filters are a good example of the event needing
      // to be destroyed earlier than above.
      if (trigger === 'unload') {
        if ($(context).find(automaticPagerSelector).removeOnce('infinite-scroll').length) {
          $window.off(scrollEvent);
        }
      }
    }
  };

  /**
   * Views AJAX pagination filter.
   *
   * In case the Entity View Attachment is rendered in a view context,
   * the default filter function prevents the required 'Use AJAX' setting
   * to work.
   *
   * @return {Boolean}
   *   Whether to apply the Views AJAX paginator. VIS requires this setting
   *   for pagination.
   */
  Drupal.views.ajaxView.prototype.filterNestedViews = function () {
    return this.$view.hasClass('view-eva') || !this.$view.parents('.view').length;
  };

})(jQuery, Drupal, Drupal.debounce);
