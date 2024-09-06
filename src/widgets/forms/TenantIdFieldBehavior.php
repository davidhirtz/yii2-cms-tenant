<?php

namespace davidhirtz\yii2\cms\tenant\widgets\forms;

use davidhirtz\yii2\cms\modules\admin\widgets\forms\EntryActiveForm;
use davidhirtz\yii2\cms\tenant\Bootstrap;
use davidhirtz\yii2\cms\tenant\models\Entry;
use davidhirtz\yii2\skeleton\helpers\Html;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use davidhirtz\yii2\tenant\models\Tenant;
use Yii;
use yii\base\Behavior;
use yii\widgets\ActiveField;

/**
 * TenantIdFieldBehavior extends {@see EntryActiveForm} to add a tenant select field. It only shows tenants that
 * are not already linked to another entry.  This behavior is attached on startup by {@see Bootstrap}.
 *
 * All methods can be overridden in the form class to customize the behavior.
 *
 * @property EntryActiveForm $owner
 */
class TenantIdFieldBehavior extends Behavior
{
    /**
     * @param EntryActiveForm $owner
     */
    public function attach($owner): void
    {
        /** @var Entry $entry */
        $entry = $owner->model;

        if ($entry->getIsNewRecord()) {
            $tenantId = Yii::$app->getRequest()->get('tenant');
            $tenant = TenantCollection::getAll()[$tenantId] ?? Yii::$app->get('tenant');
            $entry->populateTenantRelation($tenant);
        }

        parent::attach($owner);
    }

    public function tenantIdField(array $options = []): ActiveField|string
    {
        /** @var static $form */
        $form = $this->owner;
        $items = $form->getTenantIdItems();

        if (count($items) > 1) {
            return $this->owner->field($this->owner->model, 'tenant_id', $options)
                ->label(Yii::t('tenant', 'TENANT_NAME'))
                ->dropDownList($items);
        }

        return Html::activeHiddenInput($this->owner->model, 'tenant_id', [
            'value' => key($items),
        ]);
    }

    /**
     * @see static::tenantIdField()
     */
    public function getTenantIdItems(): array
    {
        $tenants = Tenant::find()
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $items = [];

        foreach ($tenants as $tenant) {
            $items[$tenant->id] = !$tenant->isEnabled()
                ? ('[' . $tenant->getStatusName() . "] $tenant->name")
                : $tenant->name;
        }

        return $items;
    }
}
