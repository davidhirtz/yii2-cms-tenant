<?php

namespace davidhirtz\yii2\cms\tenant\widgets\grids\traits;

use davidhirtz\yii2\skeleton\modules\admin\widgets\grids\columns\CounterColumn;
use davidhirtz\yii2\tenant\models\Tenant;
use Yii;

trait EntryCountColumnTrait
{
    public function entryCountColumn(): array
    {
        return [
            'class' => CounterColumn::class,
            'attribute' => 'entry_count',
            'label'  => Yii::t('cms', 'Entries'),
            'route' => fn (Tenant $tenant) => ['/admin/entry/index', 'tenant' => $tenant->id],
        ];
    }
}