<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\Modules\Admin\Widgets\Grids;

use Hirtz\Cms\tenant\Modules\Admin\Widgets\Grids\Traits\TenantEntryGridViewTrait;

class TenantGridView extends \Hirtz\Tenant\Modules\Admin\Widgets\Grids\TenantGridView
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
