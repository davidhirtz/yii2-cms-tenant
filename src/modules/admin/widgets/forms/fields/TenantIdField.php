<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\Modules\Admin\Widgets\Forms\Fields;

use Hirtz\Cms\tenant\assets\TenantDropdownAssetBundle;
use Hirtz\Cms\tenant\models\Entry;
use Hirtz\Skeleton\Widgets\Forms\Fields\SelectField;
use Hirtz\Tenant\models\collections\TenantCollection;
use Yii;

/**
 * @template T of Entry
 * @property Entry $model
 */
class TenantIdField extends SelectField
{
    protected function configure(): void
    {
        $this->attributes['data-id'] ??= 'tenant';

        $this->label ??= Yii::t('tenant', 'TENANT_NAME');
        $this->property ??= 'tenant_id';

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

        $this->registerClientScript();

        parent::configure();
    }

    protected function registerClientScript(): void
    {
        $this->view->registerAssetBundle(TenantDropdownAssetBundle::class);
    }
}
