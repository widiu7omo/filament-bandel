# Changelog

## 3.0.0 (unreleased)

Features

- Support Filament 3, 4 and 5 from a single branch. Actions are now built as
  composition factories that detect the installed Filament major at runtime
  (`src/Support/FilamentVersion.php`). On v3 table actions resolve to
  `Filament\Tables\Actions\*`; on v4/v5 they resolve to the unified
  `Filament\Actions\*` namespace.

Breaking changes

- Require PHP 8.2+ and Laravel 11.8+/12/13 (Laravel 10 support dropped).
- Action classes are now `final` composition factories instead of extended
  base actions; customise the returned instance by chaining after `make()`
  instead of subclassing.
- `make()` no longer accepts a custom name argument.

Fixes

- Bulk ban/unban now re-reads record state from the database before filtering,
  so stale in-memory models can no longer cause skipped or duplicated bans.
- Bulk operations run inside a database transaction.
- Ban form validates `expired_at` (cannot be in the past).
- Removed dead `Commands\FilamentBanCommand` import left over from the fork.
- Rebuilt test suite (previously inherited broken namespaces from upstream and
  only contained a placeholder assertion).

Housekeeping

- CI matrix covers Filament 3/4/5 x Laravel 11/12/13 on PHP 8.2-8.5.
- Dependabot now also watches composer dependencies.
- Remove committed .DS_Store files; track phpunit.xml.

## 1.0.2

Changelog

- Fix typo [en] translation
- Add Arabic translation. Thanks to @mohamedsabil83

## 1.0.1

Features

- Translation/ Multi-language notification message
- Single Model Un/Ban
- Multiple Models Un/Ban
- Support Laravel 10

Changelog

- Initial release
