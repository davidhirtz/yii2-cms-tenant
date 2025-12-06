# 3.0.0

- Skipped version 2 for major refactor to align with other packages
- Removed `TenantIdFieldBehavior`
- Renamed `TenantDropdownTrait` and `EntryCountColumnTrait`

# 1.2.3 (Dec 6, 2025)

- Fixed `TenantEntryBehavior::onBeforeDelete()` to prevent deletion of a tenant that still has entries

# 1.2.2 (Nov 24, 2025)

- Added `EntryGridViewTrait` and `SectionParentEntryGridView`

# 1.2.1 (Jul 15, 2025)

- Added slug index creation

# 1.2.0 (May 26, 2025)

- Requires PHP 8.3+
- Added GitHub CI
- Changed `\davidhirtz\yii2\cms\Module::$enableI18nTables` to `false` as I18N tables for entries are not supported
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