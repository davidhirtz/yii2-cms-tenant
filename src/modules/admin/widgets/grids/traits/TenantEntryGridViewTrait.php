<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\modules\admin\widgets\grids\traits;

use Hirtz\Cms\tenant\models\Entry;
use Hirtz\Skeleton\widgets\grids\columns\BadgeColumn;
use Hirtz\Skeleton\widgets\grids\columns\Column;
use Hirtz\Tenant\models\Tenant;
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
