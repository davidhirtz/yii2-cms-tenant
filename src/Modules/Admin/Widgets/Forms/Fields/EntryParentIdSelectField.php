<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Modules\Admin\Widgets\Forms\Fields;

use Hirtz\Cms\Models\Queries\EntryQuery;
use Hirtz\Cms\Tenant\Models\Entry;
use Override;

/**
 * @extends \Hirtz\Cms\Modules\Admin\Widgets\Forms\Fields\EntryParentIdSelectField<Entry>
 *
 * @property Entry $model
 */
class EntryParentIdSelectField extends \Hirtz\Cms\Modules\Admin\Widgets\Forms\Fields\EntryParentIdSelectField
{
    #[Override]
    protected function configure(): void
    {
        $this->attributes['data-id'] ??= 'parent';
        parent::configure();
    }

    /**
     * @return EntryQuery<Entry>
     */
    #[Override]
    protected function getEntryQuery(): EntryQuery
    {
        return parent::getEntryQuery()
            ->andWhere(['tenant_id' => $this->model->tenant_id]);
    }
}
