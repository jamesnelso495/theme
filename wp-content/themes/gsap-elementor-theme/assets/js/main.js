(function(){
  'use strict';

  var settings = window.GSAPThemeSettings || {
    enable: true,
    useScrollTrigger: true,
    ease: 'power1.out',
    duration: 0.6,
    enableReveal: true,
    revealSelector: '.gsap-anim',
    animateOnMobile: true,
    respectReduceMotion: true,
    debug: false
  };

  function log(){ if(settings.debug && typeof console !== 'undefined') console.log.apply(console, arguments); }

  function isReducedMotion(){
    try { return settings.respectReduceMotion && window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches; }
    catch(e){ return false; }
  }

  function isMobile(){
    return /Mobi|Android/i.test(navigator.userAgent) || (typeof window.orientation !== 'undefined');
  }

  function shouldAnimateOnThisDevice(){
    return settings.animateOnMobile || !isMobile();
  }

  function setupGsap(){
    if (!window.gsap) { log('GSAP not found'); return false; }
    if (settings.useScrollTrigger && window.ScrollTrigger) {
      try { window.gsap.registerPlugin(window.ScrollTrigger); } catch(_){}
    }
    return true;
  }

  function animateElement(el){
    if (!window.gsap) return;
    if (el.__geAnimated) return; // prevent duplicate animations
    el.__geAnimated = true;

    var fromY = parseFloat(el.getAttribute('data-gsap-y') || '24');
    var fromOpacity = parseFloat(el.getAttribute('data-gsap-opacity') || '0');
    var ease = el.getAttribute('data-gsap-ease') || settings.ease;
    var duration = parseFloat(el.getAttribute('data-gsap-duration') || settings.duration);
    var stagger = parseFloat(el.getAttribute('data-gsap-stagger') || '0');

    var targets = el.hasAttribute('data-gsap-children') ? el.children : el;

    var tweenOpts = { y: 0, opacity: 1, ease: ease, duration: duration, overwrite: 'auto' };
    var fromOpts  = { y: fromY, opacity: fromOpacity };

    if (settings.useScrollTrigger && window.ScrollTrigger) {
      window.gsap.fromTo(targets, fromOpts, Object.assign({
        scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' },
        stagger: stagger
      }, tweenOpts));
    } else if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function(entries, obs){
        entries.forEach(function(entry){
          if (entry.isIntersecting) {
            obs.unobserve(entry.target);
            window.gsap.fromTo(targets, fromOpts, Object.assign({ stagger: stagger }, tweenOpts));
          }
        });
      }, { root: null, rootMargin: '0px 0px -10% 0px', threshold: 0.15 });
      io.observe(el);
    } else {
      window.gsap.fromTo(targets, fromOpts, Object.assign({ stagger: stagger }, tweenOpts));
    }
  }

  function animateInScope(root){
    if (!settings.enable || !shouldAnimateOnThisDevice() || isReducedMotion()) return;
    var selector = settings.revealSelector || '.gsap-anim';
    var nodes = (root || document).querySelectorAll(selector);
    nodes.forEach(animateElement);
    log('Animated elements:', nodes.length);
  }

  function init(){
    if (!setupGsap()) { return; }
    animateInScope(document);
  }

  // Public API
  window.GSAPTheme = window.GSAPTheme || {
    refresh: function(scope){ animateInScope(scope || document); },
    settings: settings
  };

  // DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else { init(); }

  // Elementor compatibility
  if (window.jQuery && window.jQuery(window).on) {
    window.jQuery(window).on('elementor/frontend/init', function(){
      // When Elementor loads widgets dynamically, re-run animations
      if (window.elementorFrontend && window.elementorFrontend.hooks) {
        window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function(scope){
          animateInScope(scope);
        });
      }
      animateInScope(document);
    });
  }
})();