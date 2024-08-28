Multi-tenant support for the [Yii 2](http://www.yiiframework.com/)
extension [yii2-cms](https://github.com/davidhirtz/yii2-cms/).

- Override default `Entry::getRoute()` method to include the `EntryTenantBehavior::getTenantRouteParams()`
- Make sure `app\modules\admin\widgets\forms\EntryActiveForm::$fields` includes `tenant_id` to display the tenant drop
  down
