<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\modules\admin\widgets\grids;

use Hirtz\Cms\tenant\modules\admin\widgets\grids\traits\EntryTenantGridViewTrait;

class SectionParentEntryGridView extends \Hirtz\Cms\modules\admin\widgets\grids\SectionParentEntryGridView
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
