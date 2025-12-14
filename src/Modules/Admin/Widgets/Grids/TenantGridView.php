<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Grids;

use Hirtz\Cms\Tenant\Modules\Admin\Widgets\Grids\Traits\TenantEntryGridViewTrait;

class TenantGridView extends \Hirtz\Tenant\Modules\Admin\Widgets\Grids\TenantGridView
{
    use TenantEntryGridViewTrait;

    #[\Override]
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
