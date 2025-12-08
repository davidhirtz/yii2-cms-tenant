<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\modules\admin\widgets\grids\traits;

use Hirtz\Skeleton\widgets\grids\toolbars\FilterDropdown;
use Hirtz\Tenant\models\collections\TenantCollection;
use Hirtz\Tenant\models\Tenant;
use Yii;

trait EntryTenantGridViewTrait
{
    public ?int $tenantId = null;
    public string $tenantParamName = 'tenant';

    protected function getTenantDropdown(): ?FilterDropdown
    {
        $tenantId = $this->tenantId ?? Yii::$app->request->get($this->tenantParamName);
        $tenant = TenantCollection::getAll()[$tenantId] ?? Yii::$app->get('tenant');

        $items = $this->getTenantDropdownItems();

        return count($items) > 1
            ? FilterDropdown::make()
                ->label($tenant->name)
                ->items($this->getTenantDropdownItems())
                ->param($this->tenantParamName)
            : null;
    }

    protected function getTenantDropdownItems(): array
    {
        return array_map(fn (Tenant $tenant) => $tenant->name, TenantCollection::getAll());
    }
}
