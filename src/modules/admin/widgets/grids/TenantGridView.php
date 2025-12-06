<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\grids;

use davidhirtz\yii2\cms\tenant\modules\admin\widgets\grids\traits\TenantEntryGridViewTrait;

class TenantGridView extends \davidhirtz\yii2\tenant\modules\admin\widgets\grids\TenantGridView
{
    use TenantEntryGridViewTrait;

    protected function configure(): void
    {
        $this->columns ??= [
            $this->getStatusColumn(),
            $this->getNameColumn(),
            $this->getEntryCountColumn(),
            $this->getUpdatedAtColumn(),
            $this->getButtonColumn(),
        ];

        parent::configure();
    }
}
