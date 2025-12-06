<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\grids\traits;

use davidhirtz\yii2\cms\tenant\models\Entry;
use davidhirtz\yii2\skeleton\widgets\grids\columns\BadgeColumn;
use davidhirtz\yii2\skeleton\widgets\grids\columns\Column;
use davidhirtz\yii2\tenant\models\Tenant;
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
