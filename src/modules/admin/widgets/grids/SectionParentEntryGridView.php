<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\grids;

use davidhirtz\yii2\cms\tenant\modules\admin\widgets\grids\traits\EntryTenantGridViewTrait;

class SectionParentEntryGridView extends \davidhirtz\yii2\cms\modules\admin\widgets\grids\SectionParentEntryGridView
{
    use EntryTenantGridViewTrait;

    public function configure(): void
    {
        parent::configure();

        $this->header = [
            $this->getTenantDropdown(),
            ...$this->header,
        ];
    }
}
