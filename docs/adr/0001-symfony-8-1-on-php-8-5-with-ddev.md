# ADR-0001: Symfony 8.1 on PHP 8.5, run with DDEV

- **Status:** accepted
- **Date:** 2026-10-27

## Context

ItemFlow is a new project with no legacy code and no existing dependencies to stay compatible with.
It is a small service that is actively developed, not a system that has to run untouched for years.
Two decisions are needed before the first line of code:

1. **Which Symfony and PHP version.** In October 2026 the options are (dates from symfony.com/releases
   and php.net/supported-versions):

   | Version | Released | Supported until | Minimum PHP |
   |---|---|---|---|
   | Symfony 7.4 LTS | Nov 2025 | bug fixes Nov 2028, security fixes Nov 2029 | 8.2 |
   | Symfony 8.1 | May 2026 | Jan 2027 | 8.4 |
   | Symfony 8.2 | Nov 2026 (planned) | Jul 2027 | 8.4 |
   | PHP 8.4 | Nov 2024 | active Dec 2026, security Dec 2028 | – |
   | PHP 8.5 | Nov 2025 | active Dec 2027, security Dec 2029 | – |

   The next Symfony LTS, 8.4, follows the two-year cadence and is expected in November 2027.

2. **How the project runs locally.** Anyone who clones the repository should get a working setup with
   one command, without installing a matching PHP version and its extensions on their machine.

## Options

**Symfony / PHP**

1. **Symfony 7.4 LTS** – longest support window, no forced upgrades until 2028.
   Cons: the 8.x deprecation removals are still ahead, so upgrade work is only postponed;
   new features of 8.x are not available.
2. **Symfony 8.1 (current stable)** – current APIs, no deprecated 7.x layer.
   Cons: support ends in January 2027, so the project must follow every minor release until 8.4 LTS.
3. **Wait for Symfony 8.2** – also not LTS; it moves the same trade-off by a few weeks and blocks the start.

For PHP, 8.5 is the current version and supported two years longer than 8.4. Symfony 8.1 runs on both.

**Local runtime**

1. **PHP on the host + Docker Compose for MySQL** – fast, but every developer needs PHP 8.5 with the
   right extensions; versions drift between machines.
2. **Plain Docker Compose with a custom PHP image** – fully reproducible, but the Dockerfile, web server,
   HTTPS and helper commands have to be written and maintained by hand.
3. **DDEV** – PHP, web server, MySQL and HTTPS from one `.ddev/config.yaml`; PHP and database versions
   are single settings. Cons: an extra tool to install, and its conventions (database `db`, user `db`).

## Decision

We use **Symfony 8.1 on PHP 8.5** and follow the Symfony minor releases up to the 8.4 LTS.
The project runs in **DDEV** (PHP 8.5, MySQL 8.4, nginx-fpm); `ddev start` is the only command needed.

## Consequences

- **Positive:** current APIs and PHP features from day one; no deprecation backlog. A fresh clone
  starts with `ddev start`, which also installs dependencies, creates the test database and runs migrations.
  The PHP version is pinned in one place (`.ddev/config.yaml`) and in `composer.json` (`php >=8.5`).
- **Negative / accepted risks:**
  - An upgrade every six months (8.2 in November 2026, then 8.3 and 8.4). Each minor release keeps
    Symfony's backward-compatibility promise, so the work is mostly fixing deprecations, which the test
    suite reports. `extra.symfony.require` pins `8.1.*` so that upgrades happen deliberately.
  - A third-party bundle might lag behind a new Symfony minor release.
  - Contributors need Docker and DDEV.
- **Upgrade path:** when 8.2 is released, raise `extra.symfony.require` and the `symfony/*` constraints,
  run the test suite with deprecations enabled, fix what it reports, and repeat for each minor up to 8.4 LTS.
- **What would make us revisit this:** a required bundle that does not support the current Symfony
  minor within a few weeks; the project going into long-term operation without capacity for
  six-monthly upgrades (then move to the next LTS); DDEV no longer offering the needed PHP or MySQL version.
