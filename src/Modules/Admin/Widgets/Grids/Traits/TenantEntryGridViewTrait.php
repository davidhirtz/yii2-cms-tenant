<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Grids\Traits;

use Hirtz\Cms\Tenant\Models\Entry;
use Hirtz\Skeleton\Widgets\Grids\Columns\BadgeColumn;
use Hirtz\Skeleton\Widgets\Grids\Columns\Column;
use Hirtz\Tenant\Models\Tenant;
use Yii;

trait TenantEntryGridViewTrait
{
    protected function getEntryCountColumn(): ?Column
    {
        return BadgeColumn::make()
            ->property(Entry::instance()->getTenantEntryCountAttributeName())
            ->label(Yii::t('cms', 'Entries'))
            ->url(fn (Tenant $tenant) => ['/admin/entry/index', 'tenant' => $tenant->id]);
    }
}
