<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Grids\Traits;

use Hirtz\Skeleton\Widgets\Grids\Toolbars\FilterDropdown;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Hirtz\Tenant\Models\Tenant;
use Hirtz\Tenant\Web\UrlManager;
use Yii;

trait EntryTenantGridViewTrait
{
    public ?int $tenantId = null;
    public string $tenantParamName = 'tenant';

    protected function getTenantDropdown(): ?FilterDropdown
    {
        $manager = Yii::$app->getUrlManager();
        $tenant = $manager instanceof UrlManager ? $manager->getTenantFromRequest(Yii::$app->getRequest()) : null;

        $items = $this->getTenantDropdownItems();

        return count($items) > 1
            ? FilterDropdown::make()
                ->label($tenant->name ?? Yii::t('tenant', 'TENANT_NAME_PLURAL'))
                ->items($this->getTenantDropdownItems())
                ->param($this->tenantParamName)
            : null;
    }

    protected function getTenantDropdownItems(): array
    {
        return array_map(fn (Tenant $tenant) => $tenant->name, TenantCollection::getAll());
    }
}
