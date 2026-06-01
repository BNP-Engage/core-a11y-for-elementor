(function () {
  'use strict';

  var mediaQuery = '(prefers-reduced-motion: reduce)';
  var rootClass = 'core-a11y-prefers-reduced-motion';

  /*
   * Do not target [data-e-type]. It is too broad and exists on regular
   * Atomic widgets whether they are animated or not.
   */
  var targetSelector = '[data-interaction-id]';

  var patchedFlag = '__coreA11yElementorAtomicReducedMotionPatched';
  var revealedAttribute = 'data-core-a11y-motion-disabled';

  function prefersReducedMotion() {
    return Boolean(
      window.matchMedia &&
      window.matchMedia(mediaQuery).matches
    );
  }

  function markRoot() {
    document.documentElement.classList.toggle(rootClass, prefersReducedMotion());
  }

  function isElement(value) {
    return Boolean(value && value.nodeType === Node.ELEMENT_NODE);
  }

  function isTarget(element) {
    return Boolean(
      isElement(element) &&
      element.matches &&
      element.matches(targetSelector)
    );
  }

  function getInlineStyle(element, property) {
    if (!element || !element.style) {
      return '';
    }

    return element.style.getPropertyValue(property);
  }

  function hasMotionInlineStyles(element) {
    if (!element || !element.style) {
      return false;
    }

    var opacity = getInlineStyle(element, 'opacity');
    var transform = getInlineStyle(element, 'transform');
    var transition = getInlineStyle(element, 'transition');
    var animation = getInlineStyle(element, 'animation');
    var filter = getInlineStyle(element, 'filter');
    var clipPath = getInlineStyle(element, 'clip-path');

    return Boolean(
      transition ||
      animation ||
      filter ||
      clipPath ||
      opacity === '0' ||
      opacity === '0.0' ||
      opacity === '0.00' ||
      (transform && transform !== 'none')
    );
  }

  function hasActiveAnimations(element) {
    if (!element || typeof element.getAnimations !== 'function') {
      return false;
    }

    return element.getAnimations().some(function (animation) {
      return animation && animation.playState !== 'finished';
    });
  }

  function shouldRevealElement(element) {
    return Boolean(
      prefersReducedMotion() &&
      isTarget(element) &&
      (
        hasMotionInlineStyles(element) ||
        hasActiveAnimations(element)
      )
    );
  }

  function revealElement(element) {
    if (!shouldRevealElement(element)) {
      return;
    }

    element.setAttribute(revealedAttribute, 'true');

    /*
     * Only write inline styles when the element appears to be controlled by
     * Elementor's interaction animation system.
     */
    element.style.setProperty('transition', 'none', 'important');
    element.style.setProperty('animation', 'none', 'important');
    element.style.setProperty('opacity', '1', 'important');
    element.style.setProperty('transform', 'none', 'important');
    element.style.setProperty('filter', 'none', 'important');
    element.style.setProperty('clip-path', 'none', 'important');

    if (typeof element.getAnimations === 'function') {
      element.getAnimations().forEach(function (animation) {
        try {
          animation.cancel();
        } catch (error) {
          // Ignore browser-specific animation cancellation errors.
        }
      });
    }
  }

  function revealTree(context) {
    if (!prefersReducedMotion() || !context) {
      return;
    }

    markRoot();

    if (isTarget(context)) {
      revealElement(context);
    }

    if (!context.querySelectorAll) {
      return;
    }

    context.querySelectorAll(targetSelector).forEach(function (element) {
      revealElement(element);
    });
  }

  function patchWebAnimationsApi() {
    if (
      window[patchedFlag] ||
      !window.Element ||
      !Element.prototype ||
      !Element.prototype.animate
    ) {
      return;
    }

    window[patchedFlag] = true;

    var nativeAnimate = Element.prototype.animate;

    Element.prototype.animate = function (keyframes, options) {
      if (prefersReducedMotion() && isTarget(this)) {
        this.setAttribute(revealedAttribute, 'true');

        var animation = nativeAnimate.call(
          this,
          [
            {
              opacity: 1,
              transform: 'none',
              filter: 'none',
              clipPath: 'none'
            },
            {
              opacity: 1,
              transform: 'none',
              filter: 'none',
              clipPath: 'none'
            }
          ],
          {
            duration: 1,
            delay: 0,
            endDelay: 0,
            iterations: 1,
            fill: 'both'
          }
        );

        this.style.setProperty('transition', 'none', 'important');
        this.style.setProperty('animation', 'none', 'important');
        this.style.setProperty('opacity', '1', 'important');
        this.style.setProperty('transform', 'none', 'important');
        this.style.setProperty('filter', 'none', 'important');
        this.style.setProperty('clip-path', 'none', 'important');

        try {
          animation.finish();
        } catch (error) {
          // Some browsers may throw if the animation is not ready.
        }

        return animation;
      }

      return nativeAnimate.call(this, keyframes, options);
    };
  }

  function observeMutations() {
    if (!window.MutationObserver || !document.documentElement) {
      return;
    }

    var observer = new MutationObserver(function (mutations) {
      if (!prefersReducedMotion()) {
        return;
      }

      mutations.forEach(function (mutation) {
        /*
         * Only respond to Elementor/new DOM changes. Do not blindly re-apply
         * styles to every style mutation unless the element now actually looks
         * motion-controlled.
         */
        if (mutation.type === 'attributes') {
          if (
            mutation.attributeName === 'style' &&
            shouldRevealElement(mutation.target)
          ) {
            revealElement(mutation.target);
          }

          return;
        }

        mutation.addedNodes.forEach(function (node) {
          if (isElement(node)) {
            revealTree(node);
          }
        });
      });
    });

    observer.observe(document.documentElement, {
      subtree: true,
      childList: true,
      attributes: true,
      attributeFilter: ['style']
    });
  }

  function bindPreferenceChanges() {
    if (!window.matchMedia) {
      return;
    }

    var mediaQueryList = window.matchMedia(mediaQuery);

    var handleChange = function () {
      markRoot();

      if (prefersReducedMotion()) {
        revealTree(document);
      }
    };

    if (mediaQueryList.addEventListener) {
      mediaQueryList.addEventListener('change', handleChange);
      return;
    }

    if (mediaQueryList.addListener) {
      mediaQueryList.addListener(handleChange);
    }
  }

  markRoot();
  patchWebAnimationsApi();
  observeMutations();
  bindPreferenceChanges();

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      revealTree(document);
    });
  } else {
    revealTree(document);
  }

  window.addEventListener('load', function () {
    revealTree(document);
  });
})();