<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Forms\Fields;

use Hirtz\Cms\Models\Queries\EntryQuery;
use Hirtz\Cms\Tenant\Models\Entry;
use Override;

/**
 * @property Entry $model
 */
class EntryParentIdSelectField extends \Hirtz\Cms\Modules\Admin\Widgets\Forms\Fields\EntryParentIdSelectField
{
    #[Override]
    protected function configure(): void
    {
        $this->attributes['data-id'] ??= 'parent';
        $this->attributes['promptAttributes']['data-value'][0] = $this->model->tenant->getAbsoluteUrl();

        parent::configure();
    }

    #[Override]
    protected function getEntryQuery(): EntryQuery
    {
        return parent::getEntryQuery()
            ->andWhere(['tenant_id' => $this->model->tenant_id]);
    }
}
