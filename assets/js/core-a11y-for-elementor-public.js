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
     * WIDGET: Loop Carousel
     * Add role="region" attribute to the tabs widget to validate the aria-label
     *
     */
    elementorFrontend.hooks.addAction('frontend/element_ready/loop-carousel.post', function ($scope) {
      var widget = $scope.find('.elementor-loop-container');
      if (widget.length) {
        widget.attr('role', 'region');
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
      var submitButton = gform.length ? gform.find('input[type="submit"]') : null

      // Get all form field inputs
      var inputs = popup.find('input, select, textarea')
      // Find the first one that isn't a honeypot
      var firstInput = inputs.first().closest('.gfield').hasClass('gfield--type-honeypot') ? inputs.eq(2) : inputs.first()

      // Set role to dialog to validate aria label
      popup.attr('role','dialog');

      // If there are headings, add an ID to the first one so it can be used in aria-labelledby, and then focus on it
      if (headings.length) {
        headings.first().attr('id', 'popup_'+id+'_heading');
        headings.first().attr('tabindex', '-1');
        popup.attr('aria-labelledby', 'popup_'+id+'_heading');
        headings.first().focus();
      } else {
        // Intercept Elementor stealing focus to the close button
        closeButton[0].addEventListener('focusin', () => {
          firstInput.focus();
        }, { once: true });
        firstInput.focus();
      }

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

    /*
     * WIDGET: Image
     * Add role="presentation" attribute to images with that control set to 'Yes'
     *
     */
    elementorFrontend.hooks.addAction('frontend/element_ready/image.default', function ($scope) {
      var image = $scope.find('img');
      if ($scope.hasClass('core-a11y-pres-img-yes')) {
        image.attr('role', 'presentation');
      }
    });

    /*
     * WIDGET: Tabs
     * Add role="region" attribute to the tabs widget to validate the aria-label
     *
     */
    elementorFrontend.hooks.addAction('frontend/element_ready/nested-tabs.default', function ($scope) {
      var container = $scope.find('.e-n-tabs');
      if (container) {
        container.attr('role', 'region');
      }
    });

    /*
     * WIDGET: Accordion
     * Add role="region" attribute to the accordion widget to validate the aria-label
     *
     */
    elementorFrontend.hooks.addAction('frontend/element_ready/nested-accordion.default', function ($scope) {
      var container = $scope.find('.e-n-accordion');
      if (container) {
        container.attr('role', 'region');
      }
    });

    /*
     * WIDGET: Flip Box
     * Add role="presentation" attribute to images with that control set to 'Yes'
     *
     */
    elementorFrontend.hooks.addAction('frontend/element_ready/flip-box.default', function ($scope) {
      var loopItem = $scope.closest('.e-loop-item');
      var loopClasses = loopItem.attr('class').split(/\s+/);
      var id = $scope.attr('data-id');
      $.each(loopClasses, function(index, item) {
        if (item.startsWith('e-loop-item-')) {
          id = item.replace('e-loop-item-','');
        }
      });
      var flipBox = $scope.find('.elementor-flip-box');
      var image = $scope.find('img');
      var title = $scope.find('.elementor-flip-box__layer__title');
      var description = $scope.find('.elementor-flip-box__layer__description');
      if ($scope.hasClass('core-a11y-pres-img-yes')) {
        image.attr('role', 'presentation');
      }
      if (title) {
        title.attr('id', 'elementor-flip-box__layer__title__'+id)
        flipBox.attr('aria-labelledby', 'elementor-flip-box__layer__title__'+id)
      }
      if (description) {
        description.attr('id', 'elementor-flip-box__layer__description__'+id)
        flipBox.attr('aria-describedby', 'elementor-flip-box__layer__description__'+id)
      }
    });


  // End - 	elementor/frontend/init
  });

})(jQuery);