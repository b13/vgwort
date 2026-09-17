# VG Wort Tracking for TYPO3

Adds a VG Wort tracking pixel field to TYPO3 pages and renders the pixel in the frontend.

This is the **basic extension** providing pixel tracking only. For pixel pool management, METIS API integration, text registration, and author management, see [**VG Wort Pro**](https://b13.com/products/vg-wort-pro-for-typo3?utm_source=vgwort&utm_medium=readme).

## TYPO3 compatibility

This extension supports **TYPO3 v13 LTS and v14 LTS**, on PHP 8.2 and above.
Both majors are covered by the test suite on every change.

Release notes live in [CHANGELOG.md](CHANGELOG.md).

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

[**VG Wort Pro**](https://b13.com/products/vg-wort-pro-for-typo3?utm_source=vgwort&utm_medium=readme) is a separate
extension that does that part.

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

## Privacy, Cookies, and Consent Banners

**Do not put the pixel behind a consent banner without checking whether you have
to.** A pixel that only renders after someone clicks "accept" counts a fraction of
the accesses, and the counts are what the whole thing is for.

VG Wort states its own position in the *Teilnahmebedingungen für das Online
Meldesystem T.O.M.*, section 5—July 2026 edition, checked 17 September 2026:

> Klarstellend möchten wir Sie darauf hinweisen, dass im Rahmen der
> METIS-Zugriffszählung keine personenbezogenen Daten verarbeitet werden. Vor
> diesem Hintergrund finden die Regelungen der Datenschutzgrundverordnung (DSGVO)
> und des Bundesdatenschutzgesetzes (BDSG) keine Anwendung auf die
> METIS-Zugriffszählung.
>
> Zudem unterliegt die METIS-Zugriffszählung – einschließlich des dort verwendete
> Session-Cookies – nach unserer Rechtsauffassung auch nicht dem
> Einwilligungsbedürfnis nach § 25 Abs. 1 Telekommunikation-Digitale-Dienste-
> Datenschutz-Gesetz (TDDDG).

That is VG Wort's reading, not ours and not legal advice—the decision belongs to
whoever answers for privacy on your site. What it does mean is that treating the
pixel as ordinary consent-gated tracking is a choice with a price, not the obvious
default.

The same document offers a ready-made passage for your privacy policy, headed
*Cookies und Meldungen zu Zugriffszahlen*, which explains the pixel, the client ID,
and the session cookie. Take it from the current *Teilnahmebedingungen* rather than
from here, so you quote the version that is in force—and check the edition date
while you are there, because this section was written against the one named above.

Two facts you need for a processor list either way: the counting is carried out for
VG Wort by Fifty5Blue Deutschland GmbH, Saarbrücken, and the session cookie exists
to stop the same reader being counted twice within one browser session.

## License

Like TYPO3 Core, `EXT:vgwort` is licensed under **GPL-2.0-or-later**.

## Credits

VG Wort was created by David Steeb and is maintained by
[b13 GmbH](https://b13.com), Stuttgart, Germany.

[Find more TYPO3 extensions we have developed](https://b13.com/useful-typo3-extensions-from-b13-to-you?utm_source=vgwort&utm_medium=readme)
that help us deliver value in client projects. As part of our work, we focus on
testing and best practices to ensure long-term performance, reliability, and
results in all our code.
