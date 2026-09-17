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

This extension gives you the field and puts the pixel on the page. Everything
around it stays manual: where the pixels come from, which ones are still free,
which text a pixel belongs to, who wrote it, and the report to VG Wort itself.
For a handful of texts that is fine. It stops being fine at a few hundred pages
and more than one author.

**VG Wort Pro** is a separate extension that does that part. Contact
[b13](https://b13.com) for more information.

- **Pixel pool.** Import pixels from CSV or order them through the API, then
  assign one to a page by picking it from the pool. Pro keeps track of which are
  free and which are taken, and says so before you run out—instead of a
  spreadsheet next to the backend.
- **Authors** with their VG Wort card numbers, checked against VG Wort before a
  text goes out.
- **Text extraction** from the page as it is actually delivered, not assembled
  from database fields, so the reported text is the published one.
- **Reporting.** Pro builds the METIS report and sends it, and reads existing
  registrations back from VG Wort, so the backend shows what has been reported
  and what has not.
- **Checks before sending**, because **a report cannot be corrected** afterwards:
  the same pixel on two pages, a page that does not actually deliver its pixel, a
  text that changed since it was prepared, a language missing a part, a METIS
  limit exceeded.
- **Texts spread over several pages** as one report: one pixel across all parts,
  one extract, one webrange with every URL in reading order. You can do that by
  hand here as well, by putting the same pixel in several pixel fields—what Pro
  adds is that reading order, extract, and URL list stay in step with the pages.

## Privacy & GDPR

VG Wort tracking may be subject to local data protection regulations. It is the responsibility of the site operator to ensure compliance with applicable privacy laws.

## License

Like TYPO3 Core, `EXT:vgwort` is licensed under **GPL-2.0-or-later**.

## Credits

VG Wort was created by David Steeb and is maintained by
[b13 GmbH](https://b13.com), Stuttgart, Germany.

[Find more TYPO3 extensions we have developed](https://b13.com/useful-typo3-extensions-from-b13-to-you?utm_source=vgwort&utm_medium=readme)
that help us deliver value in client projects. As part of our work, we focus on
testing and best practices to ensure long-term performance, reliability, and
results in all our code.
