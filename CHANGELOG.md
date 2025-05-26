# 1.2.0 (May 26, 2025)

- Requires PHP 8.3+
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