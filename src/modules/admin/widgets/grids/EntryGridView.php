<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\modules\admin\widgets\grids;

use Hirtz\Cms\tenant\modules\admin\widgets\grids\traits\EntryTenantGridViewTrait;

class EntryGridView extends \Hirtz\Cms\modules\admin\widgets\grids\EntryGridView
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
