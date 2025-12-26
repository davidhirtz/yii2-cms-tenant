<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Forms;

use Hirtz\Cms\Tenant\Models\Entry;
use Hirtz\Cms\Tenant\Modules\Admin\Widgets\Forms\Traits\EntryTenantActiveFormTrait;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Override;

/**
 * @template T of Entry
 * @property Entry $model
 */
class EntryActiveForm extends \Hirtz\Cms\Modules\Admin\Widgets\Forms\EntryActiveForm
{
    use EntryTenantActiveFormTrait;

    #[Override]
    protected function configure(): void
    {
        $this->setTenantFromRequest();

        parent::configure();

        $this->rows = count(TenantCollection::getAll()) > 1
            ? [
                [$this->getTenantIdField()],
                ...$this->rows,
            ]
            : [
                ...$this->rows,
                [$this->getTenantIdField()],
            ];
    }
}
