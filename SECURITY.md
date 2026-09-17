# Security Policy

## Reporting a Vulnerability

Please report security issues to [security@b13.com](mailto:security@b13.com) — **not**
through the public issue tracker. A public report tells everyone about the problem before
anyone can fix it, including the people running affected installations.

A good report tells us which version you found it in, which TYPO3 version it runs on, what
an attacker can do with it, and how to reproduce it. If you have a proof of concept, send
it; if you do not, send the report anyway.

## What to Expect

We read every report and reply to it. You will hear back from us with an assessment of the
finding, and we agree a disclosure date with you before anything is published.

We do not publish fixed response times. We would rather answer you quickly than name a
deadline we might miss—if a date matters for your own disclosure process, say so in your
report and we will agree one with you.

## Supported Versions

Fixes go into the current minor of each supported major. There are no backports to earlier
minors, so the fix for a security issue is an update within your major.

| Version | Supported |
| --- | --- |
| 1.x | yes |
| 0.1.x | no—update to 1.x, which is the same code |

## Data This Extension Handles

The extension stores one value per page: the VG Wort pixel code in `pages.tx_vgwort_pixel`.
It is not personal data, and it is public by design—the pixel is rendered into the page and
visible to every visitor.

At render time the extension emits an image tag pointing at `met.vgwort.de`. The visitor's
browser fetches it, which is what makes the access countable, and VG Wort sets a session
cookie so the same reader is not counted twice in one session. That request happens on every
page carrying a pixel.

VG Wort states that no personal data is processed in the course of this counting, and that
therefore neither the GDPR nor—in their reading—the consent requirement of § 25 (1) TDDDG
applies. Their *Teilnahmebedingungen* set that out in full, and offer a passage you can put
in your privacy policy; this is the July 2026 edition, checked 17 September 2026. The
counting itself is carried out for VG Wort by Fifty5Blue Deutschland GmbH.

We report their position rather than form one. What to disclose, and whether to gate the
pixel behind consent, is the operator's decision—see the README for why gating it is not
free.

The extension sends nothing to b13 and stores no visitor data of its own.

## Scope

In scope: everything in this repository. Out of scope: vulnerabilities in TYPO3 itself,
which belong to the [TYPO3 Security Team](https://typo3.org/community/teams/security), and
anything about the VG Wort service itself.
