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

    /*
     * POPUP: On Open
     * Fix a11y issues related to the role used
     *
     */
    elementorFrontend.elements.$document.on( "elementor/popup/show", (event, id) => {
      var popup = $('#elementor-popup-modal-'+id);
      var headings = popup.find(':header');

      // If there are headings, add an ID to the first one so it can be used in aria-labelledby
      if (headings.length) {
        headings.first().attr('id', 'popup_'+id+'_heading');
        popup.attr('aria-labelledby', 'popup_'+id+'_heading');
      }

      // Set role to dialog to validate aria label
      popup.attr('role','dialog');

    });


  // End - 	elementor/frontend/init
  });

})(jQuery);