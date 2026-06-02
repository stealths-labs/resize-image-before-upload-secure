=== Resize Image Before Upload (Secure Fork) ===
Contributors: wiiiimm
Plugin URI: https://github.com/stealths-labs/resize-image-before-upload-secure
Tags: image-resize, images, resize, optimization
Requires at least: 6.0
Tested up to: 7.0
Stable tag: 1.0.4-secure.1
Requires PHP: 7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==

A WordPress plugin for client-side uploaded images resize (to do it client-side instead of backend-side and minimize the backend performance hit).

== Fork notice ==

Maintained by Stealths Works (https://stealths.works). This is a hardened fork of
"Resize Image Before Upload" by TMM Technology
(https://wordpress.org/plugins/resize-image-before-upload/), forked from v1.0.4.

Reason for the fork: upstream bundles Swiper 8.2.4 (CVE-2026-27212, CRITICAL),
used only to render a remote promotional "drip notifications" carousel. The
upstream author has not refreshed the library. This fork disables all
ads/promotions and the remote notification feed, removes the Swiper enqueues,
and deletes the bundled Swiper library. "Update URI: false" prevents WordPress
from overwriting the fork from wordpress.org. The core image-resize feature is
unchanged. See SECURITY-vuln-exceptions.md at the repository root.

== Settings ==

Plugin settings are located under "Media" tab ("Resize before upload"), there you can configure:

- Max image size in pixels (The images bigger than this threshold are scaled down to it);
- Use WP-compat (old) assets uploading script.
- A setting to enable / disable on front end / admin part separately.

== Bug reports / Questions / Suggestions ==

This fork is maintained by Stealths Works.

- Support: support@stealths.works
- Issues: https://github.com/stealths-labs/resize-image-before-upload-secure/issues

For the original, unmodified upstream plugin, contact TMM Technology (wp@tmm.ventures).

== Changelog ==

= 1.0.4-secure.1 =

#### Security / Fork

* Forked from upstream v1.0.4.
* Removed all ads/promotions and the remote notification feed (disabled the Notifications class).
* Deleted the bundled Swiper 8.2.4 library (CVE-2026-27212, CRITICAL) and its enqueue dependencies.
* Set "Update URI: false" so WordPress does not overwrite this fork from wp.org.

= 1.0.4 2023-09-09 =

#### Enhancements

* Improved composer structure.
* Capitalize menu name.

= 1.0.2 2023-07-18 =

#### Enhancements

* Set min PHP version to 7.4.

= 1.0.0 2023-05-16 =

#### Enhancements

* Decrease plugin size by removing unnecessary files.

= 0.11 2023-05-16 =

#### Enhancements

* Add a setting to enable / disable on front end / admin separately.

= 0.1 2023-01-29 =

#### Enhancements

* Changelog added.
