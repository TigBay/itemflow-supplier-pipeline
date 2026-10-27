# ItemFlow

Asynchronous supplier item data pipeline in Symfony: supplier events are validated, normalized to one
canonical model with exact prices, processed via Symfony Messenger and stored idempotently.
All suppliers and data are fictional.

**Status:** work in progress. Setup and quality gates are in place; the import pipeline comes next.

**Stack:** PHP 8.5 · Symfony 8.1 · Doctrine ORM · Symfony Messenger · MySQL 8.4 · DDEV

## Getting started

Requirements: [Docker](https://docs.docker.com/engine/install/) and [DDEV](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/).

```bash
ddev start      # PHP, MySQL, dependencies, test database and migrations
ddev test       # PHPUnit: unit and integration suites
ddev stan       # PHPStan level 8, no baseline
ddev cs         # code style check (ddev cs-fix to fix)
```

## Decisions

Architecture decisions are recorded in [`docs/adr/`](docs/adr/).
