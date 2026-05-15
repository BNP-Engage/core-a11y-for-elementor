(function () {
  'use strict';

  var mediaQuery = '(prefers-reduced-motion: reduce)';
  var rootClass = 'core-a11y-prefers-reduced-motion';

  /* Checking & save the user's preference */
  var mql = window.matchMedia ? window.matchMedia(mediaQuery) : null;

  /*
   * Elementor Pro Atomic Interactions targets:
   *   data-interaction-id  — current method (centralized script-tag data)
   *   data-interactions    — legacy method (per-element attribute)
   *   .e-atomic-element    — Atomic element class
   *   data-e-type          — Atomic element type marker
   */
  var targetSelector = [
    '[data-interaction-id]',
    '[data-interactions]',
    '.e-atomic-element',
    '[data-e-type]'
  ].join(',');

  var patchedFlag = '__coreA11yElementorAtomicReducedMotionPatched';

  function prefersReducedMotion() {
    return Boolean(mql && mql.matches);
  }

  /* add .core-a11y-prefers-reduced-motion class to html element for finer control */
  function markRoot() {
    if (document.documentElement) {
      document.documentElement.classList.toggle(rootClass, prefersReducedMotion());
    }
  }

  
  function isElement(value) {
    return Boolean(value && value.nodeType === Node.ELEMENT_NODE);
  }

  /* check if the elements meet the criteria for animated atomic element based on our selectors */
  function isTarget(element) {
    if (!isElement(element) || !element.matches) {
      return false;
    }

    return (
      element.matches(targetSelector) ||
      Boolean(element.closest && element.closest(targetSelector))
    );
  }

  
  /**
   * Returns true if Elementor has already set motion - related inline styles on this element
   * @param {*} element 
   * @returns 
   */
  function hasInlineMotionStyles(element) {
    if (!element || !element.style) {
      return false;
    }

    return Boolean(
      element.style.opacity ||
      element.style.transform ||
      element.style.transition ||
      element.style.animation ||
      element.style.filter ||
      element.style.clipPath
    );
  }

  /**
   * Elementor hides elements before animating them in so we have to un-hide them
   * 
   * @param {*} element 
   * @param {*} reduced 
   * @returns 
   */
  function revealElement(element, reduced) {
    if (!reduced || !isTarget(element)) {
      return;
    }

    element.style.setProperty('transition', 'none', 'important');
    element.style.setProperty('animation', 'none', 'important');

    /*
     * Only normalize transform/filter/opacity when Elementor has already placed motion-related inline styles on the element. 
     */
    if (hasInlineMotionStyles(element)) {
      element.style.setProperty('opacity', '1', 'important');
      element.style.setProperty('transform', 'none', 'important');
      element.style.setProperty('filter', 'none', 'important');
      element.style.setProperty('clip-path', 'none', 'important');
    }
  }

  /**
   * Walks a DOM context and reveals all matching elements that reduced motion applies to.
   * 
   * @param {*} context 
   * @returns 
   */
  function revealTree(context) {
    var reduced = prefersReducedMotion();

    if (!reduced || !context) {
      return;
    }

    markRoot();

    if (isElement(context)) {
      revealElement(context, reduced);
    }

    if (!context.querySelectorAll) {
      return;
    }

    context.querySelectorAll(targetSelector).forEach(function (element) {
      revealElement(element, reduced);
    });
  }

  /*
  * Elementor Pro uses Motion One (window.Motion) as its animation engine, which calls the native element.animate(). 
  * This patches that so when reduced motion is on, instead of hiding and animating an element, we immediately show it fully visible.
  */
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
        revealElement(this, true);

        var animation = nativeAnimate.call(
          this,
          [
            { opacity: 1, transform: 'none', filter: 'none' },
            { opacity: 1, transform: 'none', filter: 'none' }
          ],
          {
            duration: 1,
            delay: 0,
            endDelay: 0,
            iterations: 1,
            fill: 'both'
          }
        );

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

    /*
     * Buffer mutations and flush in a single requestAnimationFrame to avoid layout thrashing on pages where Elementor updates many elements in a short burst (e.g. scroll events writing inline styles simultaneously).
     */
    var rafPending = false;
    var pendingMutations = [];

    var observer = new MutationObserver(function (mutations) {
      if (!prefersReducedMotion()) {
        return;
      }

      pendingMutations = pendingMutations.concat(mutations);

      if (rafPending) {
        return;
      }

      rafPending = true;

      requestAnimationFrame(function () {
        rafPending = false;

        var batch = pendingMutations;
        pendingMutations = [];

        var reduced = prefersReducedMotion();

        if (!reduced) {
          return;
        }

        batch.forEach(function (mutation) {
          if (mutation.type === 'attributes') {
            revealElement(mutation.target, reduced);
            return;
          }

          mutation.addedNodes.forEach(function (node) {
            if (isElement(node)) {
              revealTree(node);
            }
          });
        });
      });
    });

    observer.observe(document.documentElement, {
      subtree: true,
      childList: true,
      attributes: true,
      attributeFilter: ['style', 'class']
    });
  }

  /**
   * If someone switches their reduced motion setting while the page is open, applies or remove our updates
   * @returns 
   */
  function bindPreferenceChanges() {
    if (!mql) {
      return;
    }

    var handleChange = function () {
      markRoot();
      revealTree(document);
    };

    if (mql.addEventListener) {
      mql.addEventListener('change', handleChange);
    } else if (mql.addListener) {
      mql.addListener(handleChange);
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

  /*
   * Elementor wraps its entire init in waitForAnimateFunction(), which defers execution until Motion One is available — potentially well after DOMContentLoaded.
   * The window.load pass catches elements that Motion One hid during that deferred initialization window.
   */
  window.addEventListener('load', function () {
    revealTree(document);
  });

})();