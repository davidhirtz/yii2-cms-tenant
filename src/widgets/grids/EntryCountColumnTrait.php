<?php

namespace davidhirtz\yii2\cms\tenant\widgets\grids;

use davidhirtz\yii2\skeleton\modules\admin\widgets\grids\columns\CounterColumn;
use davidhirtz\yii2\tenant\models\Tenant;

trait EntryCountColumnTrait
{
    public function entryCountColumn(): array
    {
        return [
            'class' => CounterColumn::class,
            'attribute' => 'entry_count',
            'route' => fn (Tenant $tenant) => ['/admin/entry/index', 'tenant' => $tenant->id],
        ];
    }
}