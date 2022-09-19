(function ($) {
  Drupal.behaviors.gabev = {
    attach: function(context) {
      /*--------------------------------*/
      /* Bubbler                        */
      /*--------------------------------*/
      var bubbleCount = 0;
      function generateBubble() {
        bubbleCount++;
        console.log(bubbleCount);
        var $div = $('<div>', { 'class': 'bubble_' + (Math.round(Math.random() * 3) + 1) });
        var left = (Math.random() * 80) + 10;
        $div.appendTo('#page')
            .css('left', left + '%')
            .animate({ bottom: '100%', opacity: '-0.4' }, (Math.random() * 4000) + 2000, function() {
                  $(this).remove();
                }
            );
      }
      (function loop() {
        var rand = Math.round(Math.random() * (10000)) + 5000;
        setTimeout(function() {
          generateBubble();
          loop();
        }, rand);
      }());

    }
  }
})(jQuery);
