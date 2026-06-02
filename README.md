# Resize Image Before Upload (Secure Fork)

> **Maintained by [Stealths Works](https://stealths.works)** ·
> [Repository](https://github.com/stealths-labs/resize-image-before-upload-secure) ·
> Support: support@stealths.works
>
> **This is a hardened fork**, not the upstream plugin.
>
> **Forked from:** [Resize Image Before Upload](https://wordpress.org/plugins/resize-image-before-upload/)
> by TMM Technology, **v1.0.4** (GPLv3).
>
> **Why we forked:** the upstream plugin (even at its latest version) bundles
> **Swiper 8.2.4**, which carries **CVE-2026-27212 (CRITICAL)**. Swiper is used
> only to render the vendor's promotional *"drip notifications"* carousel — an
> ad feed pulled hourly from `wpplugins-midlayer.tmm-technology.com` and shown
> via `admin_notices`. The upstream author has not refreshed the library, so the
> only way to clear the CRITICAL was to fork and remove the vulnerable code.
>
> **What this fork changes:**
> - Disabled the `Notifications` class — removes **all ads/promotions** and the
>   remote notification feed (no more hourly external request, no admin_notices ads).
> - Removed the Swiper registration + enqueue dependencies.
> - **Deleted** the bundled Swiper library (`vendor/tmmtech/wp-plugins-core/libs/swiper`).
> - Set `Update URI: false` so WordPress never overwrites this fork from wp.org.
>
> The core feature — **client-side image resize before upload** — is unchanged.
> Full record: see `SECURITY-vuln-exceptions.md` at the repository root.

# About

A WordPress plugin for client-side uploaded images resize (to do it client-side instead of backend-side and make the life of backend easier).

## Libs

### JS

| Name                                                      | Description                                                                             |
|-----------------------------------------------------------|-----------------------------------------------------------------------------------------|
| [Plupload v 2.2.1](https://github.com/moxiecode/plupload) | Plupload v. 2.2.1 to replace the 2.1.9 used in WP that has the bad resizing algorithm.  |
