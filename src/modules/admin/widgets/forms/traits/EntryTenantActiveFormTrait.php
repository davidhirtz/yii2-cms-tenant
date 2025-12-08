<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms\traits;

use davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms\fields\TenantIdField;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
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
