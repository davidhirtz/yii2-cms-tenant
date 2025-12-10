<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\Modules\Admin\Widgets\Forms;

use Hirtz\Cms\tenant\Models\Entry;
use Hirtz\Cms\tenant\Modules\Admin\Widgets\Forms\Fields\TenantIdField;
use Hirtz\Cms\tenant\Modules\Admin\Widgets\Forms\Traits\EntryTenantActiveFormTrait;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Stringable;
use Yii;

/**
 * @template T of Entry
 * @property Entry $model
 */
class EntryActiveForm extends \Hirtz\Cms\Modules\Admin\Widgets\Forms\EntryActiveForm
{
    use EntryTenantActiveFormTrait;

    #[\Override]
    protected function configure(): void
    {
        $this->setTenantFromRequest();

        parent::configure();

        if (count(TenantCollection::getAll()) > 1) {
            array_unshift($this->rows, [$this->getTenantIdField()]);
        }
    }
}
