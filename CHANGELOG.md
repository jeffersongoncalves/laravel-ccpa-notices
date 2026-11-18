# Changelog

All notable changes to this project will be documented in this file.

## 1.1.0 - 2026-11-18

### Changed

- Requires the 2.0 of the suite core, which runs on [laravel-privacy](https://github.com/jeffersongoncalves/laravel-privacy), the engine shared by the privacy suites. No change to this package's API, config or tables.

## 1.0.0 - 2026-11-17

First release: CCPA/CPRA notices for Laravel, part of the laravel-ccpa suite.

- Processing activities (business purpose, consumers, sources, recipients by role, retention) joined with the `#[PersonalData]` columns of their models.
- `<x-ccpa-notices::privacy-policy>` covers what Cal. Civ. Code §1798.130(a)(5) and Regulations §7011 require: categories collected, sensitive categories, purposes, sources, recipients, retention, the categories sold or shared, the consumer rights, how to submit requests and the last update.
- `<x-ccpa-notices::notice-at-collection>` is the notice for forms (§1798.100(b)).
- `<x-ccpa-notices::opt-out-links>` shows "Do Not Sell or Share My Personal Information" and "Limit the Use of My Sensitive Personal Information", each one only when you sell or share, or collect sensitive information (§1798.135(a)).
- English and Spanish, plus `ccpa:notices [--json]`.

## [Unreleased]
