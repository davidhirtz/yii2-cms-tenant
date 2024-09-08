<?php

namespace davidhirtz\yii2\cms\tenant\widgets\forms;

use davidhirtz\yii2\cms\modules\admin\widgets\forms\EntryActiveForm;
use davidhirtz\yii2\cms\tenant\Bootstrap;
use davidhirtz\yii2\cms\tenant\models\Entry;
use davidhirtz\yii2\skeleton\helpers\Html;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use Yii;
use yii\base\Behavior;
use yii\widgets\ActiveField;

/**
 * TenantIdFieldBehavior extends {@see EntryActiveForm} to add a tenant select field. It only shows tenants that
 * are not already linked to another entry. This behavior is attached on startup by {@see Bootstrap}.
 *
 * @property EntryActiveForm $owner
 */
class TenantIdFieldBehavior extends Behavior
{
    /**
     * Sets the tenant relation on the entry model from the request or the model itself. If the tenant is not found
     * in the request, the default tenant is used.
     *
     * This is used for both new entries and on change of the tenant id dropdown, as the list of available parent
     * entries depends on the tenant and might need to be reloaded.
     *
     * @param EntryActiveForm $owner
     */
    public function attach($owner): void
    {
        /** @var Entry $entry */
        $entry = $owner->model;

        $tenantId = Yii::$app->getRequest()->get('tenant') ?? $entry->tenant_id;
        $tenant = TenantCollection::getAll()[$tenantId] ?? Yii::$app->get('tenant');
        $entry->populateTenantRelation($tenant);

        parent::attach($owner);
    }

    public function tenantIdField(array $options = []): ActiveField|string
    {
        /** @var static $form */
        $form = $this->owner;
        $items = $form->getTenantIdItems();

        if (count($items) > 1) {
            foreach (TenantCollection::getAll() as $tenant) {
                $options['options'][$tenant->id]['data-value'][] = $tenant->getAbsoluteUrl();
            }

            return $this->owner->field($this->owner->model, 'tenant_id')
                ->label(Yii::t('tenant', 'TENANT_NAME'))
                ->dropDownList($items, $options);
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
        $items = [];

        foreach (TenantCollection::getAll() as $tenant) {
            $items[$tenant->id] = !$tenant->isEnabled()
                ? ('[' . $tenant->getStatusName() . "] $tenant->name")
                : $tenant->name;
        }

        return $items;
    }
}
