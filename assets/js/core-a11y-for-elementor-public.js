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

    /*
     * GLOBAL: Regions
     * Add aria labels to regions
     *
     */
    elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
      labelWithin($scope[0]);
    });

    var LANDMARKS = '[role="region"], section:not([role="presentation"]), aside, [role="complementary"], article';
    var MAX_LABEL = 80;
    var uid = 0;

    function labelLandmark(lm) {
      if (lm.hasAttribute('aria-label') || lm.hasAttribute('aria-labelledby')) return;
      var selector = lm.matches('article') ? 'h2, h3, h4, h5, h6' : 'h2';
      var headings = lm.querySelectorAll(selector);
      for (var i = 0; i < headings.length; i++) {
        var h = headings[i];
        if (h.closest(LANDMARKS) !== lm) continue;
        var text = h.textContent.trim();
        if (!text) continue;
        if (h.closest('[aria-hidden="true"]')) continue;
        if (text.length > MAX_LABEL) return;
        if (!h.id) h.id = 'auto-landmark-heading-' + (++uid);
        lm.setAttribute('aria-labelledby', h.id);
        return;
      }
    }

    function labelWithin(root) {
      if (!root) return;
      if (root.matches && root.matches(LANDMARKS)) labelLandmark(root);
      root.querySelectorAll(LANDMARKS).forEach(labelLandmark);
    }

    /*
     * GLOBAL: Container
     * Add play/puase button to sections with video backgrounds
     *
     */
    elementorFrontend.hooks.addAction('frontend/element_ready/container', function ($scope) {
      if ($scope.hasClass('core-a11y-video-bg-controls-yes')) {
        addPausePlayButtons($scope)
      }
    });

    function addPausePlayButtons(section) {
      var container = section.find('.elementor-background-video-container')
      var elementSettings = JSON.parse(section[0].getAttribute('data-settings'))
      var videoLink = elementSettings.background_video_link;
      var videoType = ''
      var video = []
      if (-1 !== videoLink.indexOf('vimeo.com')) {
        videoType = 'vimeo';
      } else if (videoLink.match(/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com)/)) {
        videoType = 'youtube';
      }
      var checkVideoReady = setInterval(function() {
        if ( videoType == 'youtube' || videoType == 'vimeo' ) {
          video = container.find('iframe')
        } else {
          video = container.find('video')
        }
        if (video.length) {
          clearInterval(checkVideoReady);
          section.append('<button class="play-pause-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" class="pause-btn" aria-label="Pause Video"><g fill="#fff"><path d="M4.8 0H1.2C.48 0 0 .571 0 1.429v17.142C0 19.429.48 20 1.2 20h3.6c.72 0 1.2-.571 1.2-1.429V1.429C6 .571 5.52 0 4.8 0" data-name="Path 464"></path><path d="M16.8 0h-3.6c-.72 0-1.2.571-1.2 1.429v17.142c0 .857.48 1.429 1.2 1.429h3.6c.72 0 1.2-.571 1.2-1.429V1.429C18 .571 17.52 0 16.8 0" data-name="Path 465"></path></g></svg><svg xmlns="http://www.w3.org/2000/svg" width="14.969" height="20" class="play-btn hidden" aria-label="Play Video"><path fill="#fff" d="M14.5 9 2 .25a1.249 1.249 0 0 0-2 1v17.5a1.249 1.249 0 0 0 2 1L14.5 11a1.3 1.3 0 0 0 0-2" data-name="Path 466"></path></svg></button>')
          var playButton = section.find('.play-btn')
          var pauseButton = section.find('.pause-btn')
          var button = section.find('.play-pause-btn')
          var isPaused = false
          if ( videoType == 'vimeo' ) {
            var player = new Vimeo.Player(video);
          } else {
            var player = video[0]
          }
          button.click( function( e ) {
            if ( videoType == 'youtube') {
              // Wait for iframe to load, then register
              player.addEventListener('load', registerYouTubeListener(video));
              // Listen for messages coming back from the YouTube iframe
              window.addEventListener('message', function (event) {
                // Security check: verify origin is YouTube
                if (event.origin !== 'https://www.youtube.com') return;

                try {
                  const data = JSON.parse(event.data);

                  // Check if it's a state change event
                  if (data.event === 'onStateChange' || (data.info && data.info !== undefined)) {
                    // YT Player States:
                    // -1 (unstarted), 0 (ended), 1 (playing), 2 (paused), 3 (buffering), 5 (cued)
                    const playerState = data.info.playerState;

                    if (playerState === 2) {
                      isPaused = true
                    } else if (playerState === 1) {
                      isPaused = false
                    }
                  }
                } catch (e) {
                  // Ignore non-JSON messages
                }
              });
            } else if ( videoType == 'vimeo' ) {
              // getPaused() is async, so toggle only once the promise resolves.
              // Otherwise the toggle below would run with the previous click's state.
              player.getPaused().then(function(paused) {
                isPaused = paused ? true : false;
                togglePlayback()
              })
              return
            } else {
              isPaused = player.paused ? true : false;
            }
            togglePlayback()

            function togglePlayback() {
              if (isPaused) {
                if ( videoType == 'youtube' ) {
                  player.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
                } else {
                  player.play()
                }
                pauseButton.removeClass('hidden')
                playButton.addClass('hidden')
              } else {
                if ( videoType == 'youtube' ) {
                  player.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                } else {
                  player.pause()
                }
                playButton.removeClass('hidden')
                pauseButton.addClass('hidden')
              }
            }
          });
        }
      }, 100)
    }

    // Function to tell the YouTube iframe to start sending events
    function registerYouTubeListener(iframe) {
      var videoID = iframe.attr('id')
      // 1. Tell iframe we are listening
      iframe[0].contentWindow.postMessage(
        JSON.stringify({ event: 'listening', id: videoID, channel: 'widget' }),
        'https://www.youtube.com'
      );

      // 2. Subscribe to the 'onStateChange' event
      iframe[0].contentWindow.postMessage(
        JSON.stringify({
          event: 'command',
          func: 'addEventListener',
          args: ['onStateChange'],
          id: 'yt-player',
          channel: 'widget'
        }),
        'https://www.youtube.com'
      );
    }

  // End - 	elementor/frontend/init
  });

})(jQuery);