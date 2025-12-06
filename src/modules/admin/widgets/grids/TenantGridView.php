<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\grids;

use davidhirtz\yii2\cms\tenant\modules\admin\widgets\grids\traits\EntryCountColumnTrait;

class TenantGridView extends \davidhirtz\yii2\tenant\modules\admin\widgets\grids\TenantGridView
{
    use EntryCountColumnTrait;

    public function init(): void
    {
        $this->columns = $this->columns ?: [
            $this->statusColumn(),
            $this->nameColumn(),
            $this->entryCountColumn(),
            $this->updatedAtColumn(),
            $this->buttonsColumn(),
        ];

        parent::init();
    }
}
