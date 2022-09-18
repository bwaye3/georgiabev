(function ($) {

    window.map_init = function( mode ){
      if($('.listing-main-map').length == 0)
        return;
      var addressPoints = [];
      
      $('.listing-items .listing-block').each(function(){
        var lat = $(this).find('.data-lat').html();
        var lon = $(this).find('.data-lon').html();
        var html = $(this).find('.data-html').html();
        var icon = '', cat_color = '';
        $(this).find('.listing-category .data-icon').each(function(){
          if($(this).html()){
            icon = $(this).html();
          }
        }); 
         $(this).find('.listing-category .data-category-color').each(function(){
          if($(this).html()){
            cat_color = $(this).html();
          }
        }); 

        var tmp = [lat,lon,html,icon, cat_color];
        addressPoints.push(tmp); 

      })

      //Set default Latlng when emptry
      if(mode!='single'){
        if(drupalSettings.listing_setting.map_center_latitude && drupalSettings.listing_setting.map_center_longitude){
          latlng = L.latLng(drupalSettings.listing_setting.map_center_latitude, drupalSettings.listing_setting.map_center_longitude);
        }
      }

      //console.log(addressPoints);
      if(addressPoints[0]){
        var location_default = addressPoints[0];
        latlng = L.latLng(location_default[0], location_default[1]);
      }else{
        latlng = L.latLng('42.7247484', '-78.0127572');
      }
      
      var map_zoom = drupalSettings.listing_setting.map_zoom;

      var map = L.map('listing-main-map',{
         //zoomControl:false
      }).setView(latlng, map_zoom);

      //--Ctrl + mousewhell zoom
      map.scrollWheelZoom.disable();
        $("#listing-main-map").bind('mousewheel DOMMouseScroll', function (event) {
          event.stopPropagation();
          if (event.ctrlKey == true) {
            event.preventDefault();
            map.scrollWheelZoom.enable();
            $('#listing-main-map').removeClass('map-scroll');
            setTimeout(function(){
              map.scrollWheelZoom.disable();
            }, 1000);
          } else {
            map.scrollWheelZoom.disable();
            $('#listing-main-map').addClass('map-scroll');
          }
        });

        $(window).bind('mousewheel DOMMouseScroll', function (event) {
          $('#map').removeClass('map-scroll');
        })
       //-- End zoom

        var map_source = drupalSettings.listing_setting.map_source;
        var mapbox_access_token = drupalSettings.listing_setting.mapbox_access_token;
        var mapbox_id_style = drupalSettings.listing_setting.mapbox_id_style;

        if(map_source == 'mapbox'){
          // Skin color for Map
          //https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png
          //https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}
          //https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png
          L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: '',
            maxZoom: 18,
            id: mapbox_id_style,
            tileSize: 512,
            zoomOffset: -1,
            accessToken: mapbox_access_token,
          }).addTo(map)
         //-- End skin color
        }

        if(map_source == 'openstreetmap'){
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '',
          maxZoom: 18,
        }).addTo(map)
        }

        if(map_source == 'google'){   
          //--- Code use google map
         var styles = [];
         if(drupalSettings.listing_setting.google_map_style == 'gray'){
           styles = [{ "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [ { "color": "#444444" } ] }, { "featureType": "landscape", "elementType": "all", "stylers": [ { "color": "#f2f2f2" } ] }, { "featureType": "poi", "elementType": "all", "stylers": [ { "visibility": "off" } ] }, { "featureType": "road", "elementType": "all", "stylers": [ { "saturation": -100 }, { "lightness": 45 } ] }, { "featureType": "road.highway", "elementType": "all", "stylers": [ { "visibility": "simplified" } ] }, { "featureType": "road.arterial", "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] }, { "featureType": "transit", "elementType": "all", "stylers": [ { "visibility": "off" } ] }, { "featureType": "water", "elementType": "all", "stylers": [ { "color": "#cae5f0" }, { "visibility": "on" } ] }];
         }
         var styled = L.gridLayer.googleMutant({
          type: 'roadmap',
          maxZoom: 18,
          styles: styles,
          gestureHandling: 'greedy'
        }).addTo(map);
        // --- End code google map
        }


       var markers = L.markerClusterGroup();
       var markerList = [];
       var icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="54px" height="54px" viewBox="0 0 54 54" style="enable-background:new 0 0 54 54;" xml:space="preserve"><path class="pin-st0" d="M27,54C27,54,27,54,27,54c0.7,0,1.3-0.3,1.7-0.8C41.1,38,47,29.2,47,20.3C47,9.1,38,0,27,0S7,9.1,7,20.3c0,8.7,5.6,17.2,18.3,32.9C25.7,53.7,26.3,54,27,54z"></path><path class="pin-st1" d="M27,1C16.5,1,8,9.6,8,20.1c0,8.2,5.4,16.2,17.4,31.1c0.4,0.5,1,0.8,1.6,0.8c0,0,0,0,0,0c0.6,0,1.2-0.3,1.6-0.8C40.4,36.9,46,28.6,46,20.1C46,9.6,37.5,1,27,1z M27,36c-8.8,0-16-7.2-16-16S18.2,4,27,4c8.8,0,16,7.2,16,16S35.8,36,27,36z"></path></svg>';
      lat_lng_list = [];
      function populate() {
         for (var i = 0; i < addressPoints.length; i++) {
            var a = addressPoints[i];
            var popup = a[2];
            var cat_color = a[4];
            var icon = new L.DivIcon({
               iconSize: [54, 54],
               iconAnchor: [27, 54],
               popupAnchor: [0, -26],
               className: 'gva-icon-map color-' + cat_color,
               html: '<span class="icon-map">' + icon_svg + '<span class="icon-cat"><i class="'+a[3]+'"></i></span></span>'
            });
            var marker = L.marker(L.latLng(a[0], a[1]), { icon: icon }).bindPopup(popup, {'maxWidth': '500','className' : 'custom'});
            markers.addLayer(marker);
            markerList.push(marker);
            lat_lng_list.push([a[0], a[1]]);
         }

        navigator.geolocation.getCurrentPosition(function(location) {
            var marker = L.marker(L.latLng(location.coords.latitude, location.coords.longitude));
            markers.addLayer(marker);
            markerList.push(marker);
        });

      }

      populate();

      map.addLayer(markers);

      if(mode != 'single'){
        bounds = new L.LatLngBounds(lat_lng_list);
        var fit_bounds = map.fitBounds(bounds, { 'padding': [80, 80] });  
      }
        
      $('.gva-reset-map').on('click', function(e){
        e.preventDefault();
        map.setView(latlng, drupalSettings.listing_setting.map_zoom);
      });

      $('.gva-current-map').on('click', function(e){
        e.preventDefault();
        navigator.geolocation.getCurrentPosition(function(location) {
          var latlng_current = new L.LatLng(location.coords.latitude, location.coords.longitude);
          map.setView(latlng_current, drupalSettings.listing_setting.map_zoom);
        });
      });


      // $('#hover-show-map').on('change', function(){
      //   map.setView(latlng, drupalSettings.listing_setting.map_zoom);
      // });

      $('.listing-block .show-in-map a').on('click', function(e){
        e.preventDefault();
        var marker_id = $(this).parents('.listing-item-wrapper').data('marker');
        var m =  markerList[marker_id];
        markers.zoomToShowLayer(m, function () {
           m.openPopup();
        });
      });

      $('.listing-item-wrapper').each(function(e){
        $(this).hover(function(){
          if($('#hover-show-map').is(":checked")){
            var marker_id = $(this).data('marker');
            var m =  markerList[marker_id];
            markers.zoomToShowLayer(m, function () {
               m.openPopup();
            });
          }
        })
      })

      $(document).ready(function(){

        window.map_action = function(){
          $('.lt-map-action .control-search a').on('click', function(e){
            e.preventDefault();
            var btn = $(this);
            if(btn.hasClass('open-mode')){
              btn.parents('.gva-view').find('.views-exposed-form').first().removeClass('open');
              btn.removeClass('open-mode');
            }else{
              btn.parents('.gva-view').find('.views-exposed-form').first().addClass('open');
              btn.addClass('open-mode');
            }
          });

          $('a.close-exposed-form').on('click', function(e){
            e.preventDefault();
            $(this).parents('.views-exposed-form').removeClass('open');
          });

          if($('.mode-filter-fixed .views-exposed-form').length){
            $('.mode-filter-fixed .views-exposed-form').mCustomScrollbar();
          };
        }
        $('.views-exposed-form .js-form-type-checkbox').each(function(){
          if(!$(this).hasClass('pretty')){
            $(this).addClass('pretty p-default p-round');
            $(this).find('label').wrap( '<div class="state p-success-o"></div>' );
          }
        });
        map_action();


        $('.listing-nav a[href*="#"]:not([href="#"]), .listing-action a[href*="#"]:not([href="#"])').click(function() {
          if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
              $('html, body').animate({
                scrollTop: target.offset().top - 150
              }, 600);
              return false;
            }
          }
        });

      })
   }

  jQuery(document).ajaxComplete(function(event, xhr, settings) {
    if(settings.data){ 
      if (settings.data.indexOf( "view_name=listing_content") != -1) {
        $('.views-exposed-form .js-form-type-checkbox').each(function(){
          if(!$(this).hasClass('pretty')){
            $(this).addClass('pretty p-default p-round');
            $(this).find('label').wrap( '<div class="state p-success-o"></div>' );
          }
        });
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

      }
    }  
  });

})(jQuery);



