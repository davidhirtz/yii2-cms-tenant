<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Grids\Traits;

use Hirtz\Skeleton\I18n\Lang;
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
            ->title(Lang::t('cms', 'COMMON_ENTRIES'))
            ->url(fn (Tenant $tenant) => ['/admin/cms/entry/index', 'tenant' => $tenant->id]);
    }
}
