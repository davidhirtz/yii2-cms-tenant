<?php
declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms\fields;

use davidhirtz\yii2\cms\tenant\models\Entry;
use davidhirtz\yii2\skeleton\widgets\forms\fields\SelectField;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use Yii;

/**
 * @template T of Entry
 * @property Entry $model
 */
class TenantIdField extends SelectField
{
    protected function configure(): void
    {
        $this->property ??= 'tenant_id';
        $this->label ??= Yii::t('tenant', 'TENANT_NAME');

        if (!$this->items) {
            foreach (TenantCollection::getAll() as $tenant) {
                $this->items[$tenant->id] = [
                    'label' => !$tenant->isEnabled()
                        ? ('[' . $tenant->getStatusName() . "] $tenant->name")
                        : $tenant->name,
                    'data-value' => $tenant->getAbsoluteUrl(),
                ];
            }
        }

        parent::configure();
    }
}