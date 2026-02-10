# VG Wort Tracking for TYPO3

Adds a VG Wort tracking pixel field to TYPO3 pages and renders the pixel in the frontend.

This is the **basic extension** providing pixel tracking only. For pixel pool management, METIS API integration, text registration, and author management, see **VG Wort Pro** — contact [b13](https://b13.com) for more information.

## TYPO3 compatibility

This extension supports **TYPO3 v14 LTS**.

## Installation

```bash
composer require b13/vgwort
```

## Configuration

Include the VG Wort site set in your site package's `config.yaml`:

```yaml
dependencies:
  - b13/vgwort
```

This adds the VG Wort tracking pixel automatically after the opening `<body>` tag on every page that has a pixel assigned.

### VG Wort server domain

The tracking pixel URL uses a VG Wort server subdomain (vg01-vg09). The default is `vg08`.

To change this, configure it in your site's `settings.yaml`:

```yaml
settings:
  vgwort:
    domain: 'vg05'
```

Check your VG Wort pixel order confirmation for the correct subdomain.

### Alternative: Fluid partial

If you prefer to control where the pixel is rendered, add the extension's partial path to your template configuration:

```typoscript
lib.contentElement.partialRootPaths.5 = EXT:vgwort/Resources/Private/Partials
```

Then render the partial in your page template:

```html
<f:render
  partial="VgwortTracking"
  arguments="{vgwort_pixel: record.tx_vgwort_pixel, vgwort_domain: site.settings.vgwort.domain}"/>
```

## Page fields

The extension adds the following fields to page records in a **VG Wort** tab:

| Field | Description |
|-------|-------------|
| `tx_vgwort_pixel` | VG Wort tracking pixel code (public code) |
| `tx_vgwort_ignore` | Exclude this page from VG Wort tracking |

Fields are only shown for **standard pages** (doktype 1) by default.

### Custom doktypes

If your site uses custom page types with trackable content, add them in your site package's `ext_localconf.php`:

```php
$GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['vgwort']['contentDoktypes'][] = 116;
```

Your site package must declare `b13/vgwort` as a Composer dependency so the default doktypes array is initialized first.

### Info module

The extension adds a **VG Wort Tracking Overview** to the Info module (Web > Info), listing all pages with their pixel codes.

## Upgrading to VG Wort Pro

For pixel pool management, METIS API text registration, author management, and a dashboard widget, **VG Wort Pro** is available as a separate extension. Contact [b13](https://b13.com) for more information.

VG Wort Pro builds on top of this extension and adds:

- **Pixel Pool** — import/order pixels, auto-assign to pages
- **Lock field** — pixel field becomes read-only once filled
- **Author management** — create/verify authors with VG Wort card numbers
- **Text registration** — extract page content and submit to VG Wort METIS API
- **Sync** — sync existing registrations from VG Wort
- **Dashboard widget** — overview of tracking status across sites

## Privacy & GDPR

VG Wort tracking may be subject to local data protection regulations. It is the responsibility of the site operator to ensure compliance with applicable privacy laws.

## License

Like TYPO3 Core, `EXT:vgwort` is licensed under **GPL-2.0-or-later**.

## Background, authors & maintenance

This extension was created by David Steeb in 2025 for [b13 GmbH, Stuttgart](https://b13.com).

[Find more TYPO3 extensions we have developed](https://b13.com/useful-typo3-extensions-from-b13-to-you) that help us deliver value in client projects. As part of our work,
we focus on testing and best practices to ensure long-term performance, reliability, and results in all our code.
