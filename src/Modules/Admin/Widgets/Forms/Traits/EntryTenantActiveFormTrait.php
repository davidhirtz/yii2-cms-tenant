<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Forms\Traits;

use Hirtz\Cms\Tenant\Modules\Admin\Widgets\Forms\Fields\TenantIdField;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Hirtz\Tenant\Web\UrlManager;
use Stringable;
use Yii;

trait EntryTenantActiveFormTrait
{
    protected function setTenantFromRequest(): void
    {
        $tenant = TenantCollection::getFromRequest();

        if (null === $tenant) {
            $manager = Yii::$app->getUrlManager();
            $tenant = $manager instanceof UrlManager ? $manager->tenant : null;
        }

        $this->model->populateTenantRelation($tenant ?? TenantCollection::getDefault());
    }

    protected function getTenantIdField(): ?Stringable
    {
        return TenantIdField::make();
    }
}
