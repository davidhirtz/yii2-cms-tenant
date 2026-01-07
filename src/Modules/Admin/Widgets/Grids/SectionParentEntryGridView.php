<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Grids;

use Hirtz\Cms\Tenant\Modules\Admin\Widgets\Grids\Traits\EntryTenantGridViewTrait;

class SectionParentEntryGridView extends \Hirtz\Cms\Modules\Admin\Widgets\Grids\SectionParentEntryGridView
{
    use EntryTenantGridViewTrait;

    #[\Override]
    public function configure(): void
    {
        parent::configure();

        $this->header = [
            $this->getTenantDropdown(),
            ...$this->header,
        ];
    }
}
