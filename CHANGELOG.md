# Changelog

All notable changes to `b13/vgwort` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.1] — 2026-11-09

### Changed

- **`typo3/cms-frontend` is a declared requirement.** The extension ships TypoScript and a
  Fluid partial for the frontend but only required `typo3/cms-core`. `typo3/cms-info`, which
  provides the overview in the Info module, is suggested.
- **The TCA override reads the content doktypes from `ExtensionConfiguration`.** It kept its
  own copy of the same lookup. The method and its result are unchanged.

### Fixed

- **The Fluid partial `VgwortTracking` renders again.** It called `f:or` with an argument
  that does not exist, so using it threw an exception on TYPO3 v13 and v14 instead of
  printing the pixel.
- **Pages marked "Ignore for VG Wort" no longer print a pixel.** The field is hidden once
  the page is ignored but keeps its value, and `page.1` and the partial rendered it anyway.
  The partial takes an optional `vgwort_ignore` argument; calls without it behave as before.
- **A version that does not match the tag stops the TER upload.** A tag that disagreed with
  `ext_emconf.php` or `composer.json` used to publish a package reporting the wrong version.

### Security

- **The pixel value is escaped in the TypoScript output.** A quote in the field could end the
  `src` attribute and add attributes to the `img` tag. Only backend editors could enter such a
  value. The field also trims whitespace now. The Fluid partial already escaped the value.

### Tests

- **Functional tests render a real frontend page** and check the HTML from `page.1` and from
  the partial, for a pixel, no pixel, an ignored page and a value with quotes. Unit tests
  cover the doktype configuration and the TCA override. CI runs both against TYPO3 v13 and
  v14.

## [1.0.0] — 2026-11-04

### Added

- **Releases are published to the TER** when a version tag is pushed.
- **Tests run on every push and pull request**, against TYPO3 v13 and v14 — the two majors
  the extension declares support for.

### Changed

- **First stable release.** Nothing about how the extension works changes with this
  version. What changes is the promise: the TCA field `tx_vgwort_pixel`, the site set
  `b13/vgwort`, its settings, and the `VgwortTracking` partial are now a public interface
  that will not change within 1.x. The 0.1.x line carried the same code with no such
  commitment, and its version numbers made every new minor a breaking change for anything
  depending on it.

### Removed

- **A cache-hash exception for a query parameter this extension never produces.**
  `vgwort-markers` belongs to VG Wort Pro, which has the middleware and the token that use
  it; this package referenced it nowhere else. Installing both keeps working, because Pro
  declares the exception itself from the version that removes it here—update the two
  together.

### Fixed

- **The version the extension reports is the version you installed.** `ext_emconf.php` said
  `0.1.0` in every release up to and including `0.1.2`, so the extension manager showed the
  same number for three different versions.

### Documentation

- **The package contains the extension and nothing else.** Tests and build configuration are
  excluded from the archive, which previously shipped them to every installation.

## [0.1.2] and earlier

Released before this changelog was kept: the initial release plus two fixes that added
`role="presentation"` to the tracking pixel. See the README for what the extension does and
how to set it up.
