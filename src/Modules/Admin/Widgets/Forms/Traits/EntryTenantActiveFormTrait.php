<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Forms\Traits;

use Hirtz\Cms\Tenant\Modules\Admin\Widgets\Forms\Fields\TenantIdField;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Stringable;
use Yii;

trait EntryTenantActiveFormTrait
{
    protected function setTenantFromRequest(): void
    {
        $tenantId = Yii::$app->getRequest()->get('tenant', $this->model->tenant_id);
        $tenant = TenantCollection::getAll()[$tenantId] ?? TenantCollection::getDefault();
        $this->model->populateTenantRelation($tenant);
    }

    protected function getTenantIdField(): ?Stringable
    {
        return TenantIdField::make();
    }
}
