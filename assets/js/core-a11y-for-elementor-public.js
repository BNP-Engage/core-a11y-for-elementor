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

    // keep track of element that triggered the modal window.
    var previousElement = null;

    /*
     * POPUP: On Open
     * Fix a11y issues related to the role used
     *
     */
    elementorFrontend.elements.$document.on( "elementor/popup/show", (event, id) => {
      var popup = $('#elementor-popup-modal-'+id);
      var dialog = popup.find('[data-elementor-type="popup"]')
      var headings = popup.find(':header');
      var closeButton =  popup.find('.dialog-close-button')
      var gform = popup.find('.gform_wrapper form')
      if (gform) {
        var submitButton = gform.find('input[type="submit"]')
      }
      // Get all form field inputs
      var inputs = popup.find('input, select, textarea')
      // Find the first one that isn't a honeypot
      var firstInput = inputs.first().closest('.gfield').hasClass('gfield--type-honeypot') ? inputs.eq(2) : inputs.first()

      // If there are headings, add an ID to the first one so it can be used in aria-labelledby, and then focus on it
      if (headings.length) {
        headings.first().attr('id', 'popup_'+id+'_heading');
        headings.first().attr('tabindex', '-1');
        popup.attr('aria-labelledby', 'popup_'+id+'_heading');
        headings.first().focus()
      } else {
        // TODO: for some reason, focusing on the first element isn't working when a11y nav is turned off. when it's turned on, it focuses on it at first but then moves the focus back to the close button 😭
        firstInput.focus();
        // popup.focus();
      }

      // Set role to dialog to validate aria label
      popup.attr('role','dialog');

      // Only run if the popup is not already accessible
      if (dialog.data('elementor-settings')['a11y_navigation'] !== 'yes') {
        // track which element triggered the modal so that we can restore focus to that element when the modal is closed
        previousElement = document.activeElement;
        // Don't move focus from close button if tabbing backward, unless a gravity form submit button is present, then focus on that
        closeButton.keydown(function (e) {
          var key = e.which;
          if(key == 9 && e.shiftKey) {
            e.preventDefault();
            if (submitButton) {
              submitButton.focus();
            }
          }
        });
        // If tabbing from the submit button, bring focus back to close button
        if (submitButton) {
          submitButton.keydown(function (e) {
            var key = e.which;
            if(key == 9) {
              e.preventDefault();
              if (closeButton) {
                closeButton.focus();
              }
            }
          })
        }
      }

    });

    /*
     * POPUP: On Close
     * Return Focus to triggering item
     *
     */
    $(document).on( "elementor/popup/hide", (event, id) => {
      var popup = $('#elementor-popup-modal-'+id);
      var dialog = popup.find('[data-elementor-type="popup"]')
      // Make the previous element in focus
      if ( previousElement && dialog.data('elementor-settings')['a11y_navigation'] !== 'yes' ) {
        previousElement.focus();
        previousElement = null;
      }
    });

    /*
     * WIDGET: Counter
     * Disable animation for reduce motion preference
     *
     */
    elementorFrontend.hooks.addAction('frontend/element_ready/counter.default', function ($scope) {
      var isReduced = window.matchMedia(`(prefers-reduced-motion: reduce)`) === true || window.matchMedia(`(prefers-reduced-motion: reduce)`).matches === true;
      if ($scope.hasClass('core-a11y-counter-hide-animation-yes') && isReduced) {
        var number = $scope.find('.elementor-counter-number');
        if (number.length) {
          number.data('duration', '0');
          var endNumber = number.attr('data-to-value');
          number.text(endNumber);
        }
      }
    });


  // End - 	elementor/frontend/init
  });

})(jQuery);