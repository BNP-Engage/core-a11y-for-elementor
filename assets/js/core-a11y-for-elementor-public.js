"use strict";

(function ($) {
  'use strict';

  $(window).on('elementor/frontend/init', function () {

    /*
     * WIDGET: Loop Grid
     * Add role="listitem" attribute to loop items since container is set to role="list"
     *
     */
    elementorFrontend.hooks.addAction('frontend/element_ready/loop-grid.post', function ($scope) {
      var widget = $scope.find('.elementor-loop-container');
      var items = widget.find('.e-loop-item');
      if (items.length) {
        $(items).each(function () {
          $(this).attr('role', 'listitem');
        });
      }
    });


  // End - 	elementor/frontend/init
  });

})(jQuery);