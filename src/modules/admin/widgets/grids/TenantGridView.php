<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\modules\admin\widgets\grids;

use Hirtz\Cms\tenant\modules\admin\widgets\grids\traits\TenantEntryGridViewTrait;

class TenantGridView extends \Hirtz\Tenant\modules\admin\widgets\grids\TenantGridView
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
