GSAP Elementor Theme
====================

A lightweight, Elementor-friendly WordPress theme with built-in GSAP (GreenSock) integration and a backend settings page to manage animation behavior.

Key features:
- Elementor-ready templates: Elementor Full Width and Canvas
- GSAP + optional ScrollTrigger loaded via CDN (configurable)
- Backend settings (Settings → GSAP) to control enable/disable, ease, duration, selector, mobile behavior, reduced motion, and debug mode
- Default reveal-on-scroll for elements matching `.gsap-anim`

How to use:
1. Activate the theme in Appearance → Themes
2. Configure animations in Settings → GSAP
3. In Elementor, add the class `gsap-anim` to widgets/sections you want to animate
4. Optionally override per-element with attributes: `data-gsap-duration`, `data-gsap-ease`, `data-gsap-y`, `data-gsap-opacity`, `data-gsap-stagger`

Notes:
- If using Elementor Pro Theme Builder, header/footer locations are registered when available
- The theme loads GSAP via CDN by default; you can switch URLs in the settings