## 3.0.0

- `Models\Queries\PermalinkQuery::whereUri()` scopes the site controller's permalink lookup to the current
  tenant, taking over from the `EntryQuery::whereSlug()` override the controller no longer calls
- Scoped permalinks to a tenant. Added `Models\Permalink` and `Models\Queries\PermalinkQuery`, both
  registered in `Bootstrap`, `Entry::getPermalinkAttributes()`, and migration `M260909140000PermalinkTenant`,
  which adds `tenant_id` to the permalink table and widens its unique index to `(tenant_id, language, uri)`.
  Without it two tenants cannot serve the same slug: the second one to be saved silently lost its permalink
- Skipped version 2 for major refactor to align with other packages
- Removed `TenantIdFieldBehavior`
- Renamed `TenantDropdownTrait`, `EntryCountColumnTrait` and `AssetBundle`

## 1.2.4 (Jan 28, 2026)

- PHP 8.5 compatibility fixes

## 1.2.3 (Dec 6, 2025)

- Fixed `TenantEntryBehavior::onBeforeDelete()` to prevent deletion of a tenant that still has entries

## 1.2.2 (Nov 24, 2025)

- Added `EntryGridViewTrait` and `SectionParentEntryGridView`

## 1.2.1 (Jul 15, 2025)

- Added slug index creation

## 1.2.0 (May 26, 2025)

- Requires PHP 8.3+
- Added GitHub CI
- Changed `\Hirtz\Cms\Module::$enableI18nTables` to `false` as I18N tables for entries are not supported
  with tenants enabled.

## 1.1.1 (Dec 23, 2024)

- Fixed typecast for `tenant_id` in `EntryActiveDataProvider`
- Fixed `TenantIdValidator` for empty `tenant_id`

## 1.1.0 (Nov 29, 2024)

- Fixed tenants for I18N entry tables

## 1.0.4 (Oct 2, 2024)

- Fixed `EntryQuery::selectSitemapAttributes()`

## 1.0.3 (Sep 24, 2024)

- Enhanced `TenantIdFieldBehavior` tenant selection based on request parameter

## 1.0.2 (Sep 19, 2024)

- Fixed default base slug for `EntryActiveForm` (Issue #1)
- Fixed `Tenant::$entry_count` calculation after update (Issue #2)

## 1.0.1 (Sep 8, 2024)

- Added tenant scope to `Entry::findSiblings()`
- Fixed `tenant_id` validation for strings
- Fixed default base slug for `EntryActiveForm`