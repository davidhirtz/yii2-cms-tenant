<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Forms\Fields;

use Hirtz\Skeleton\I18n\Lang;
use Hirtz\Cms\Tenant\Assets\TenantDropdownAssetBundle;
use Hirtz\Cms\Tenant\Models\Entry;
use Hirtz\Skeleton\Widgets\Forms\Fields\SelectField;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Override;
use Yii;

/**
 * @template T of Entry
 * @property Entry $model
 */
class TenantIdField extends SelectField
{
    #[Override]
    protected function configure(): void
    {
        $this->attributes['data-id'] ??= 'tenant';
        $this->attributes['required'] ??= true;

        $this->label ??= Lang::t('tenant', 'TENANT_NAME');
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
