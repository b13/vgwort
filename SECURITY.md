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

At render time the extension emits an image tag pointing at `met.vgwort.de`. Loading it
transmits the visitor's IP address and user agent to VG Wort, which is what makes the access
countable. That transfer is the purpose of the extension, and it happens on every page
carrying a pixel. Deciding whether it is lawful for a given site, and disclosing it in the
site's privacy policy, is the operator's responsibility.

The extension sends nothing to b13 and stores no visitor data of its own.

## Scope

In scope: everything in this repository. Out of scope: vulnerabilities in TYPO3 itself,
which belong to the [TYPO3 Security Team](https://typo3.org/community/teams/security), and
anything about the VG Wort service itself.
