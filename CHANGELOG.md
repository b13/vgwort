# Changelog

All notable changes to `b13/vgwort` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] — 2026-09-17

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
