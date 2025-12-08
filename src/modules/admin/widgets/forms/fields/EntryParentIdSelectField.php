<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\Modules\Admin\Widgets\Forms\Fields;

use Hirtz\Cms\Models\queries\EntryQuery;
use Hirtz\Cms\tenant\models\Entry;
use Yii;

/**
 * @property Entry $model
 */
class EntryParentIdSelectField extends \Hirtz\Cms\Modules\Admin\Widgets\Forms\Fields\EntryParentIdSelectField
{
    protected function configure(): void
    {
        $this->attributes['data-id'] ??= 'parent';
        $this->attributes['promptAttributes']['data-value'][0] = $this->model->tenant->getAbsoluteUrl();

        // Make sure only one option to ensure the JS can populate the options on change.
        $this->showSingleOption = true;

        parent::configure();
    }

    protected function getEntryQuery(): EntryQuery
    {
        return parent::getEntryQuery()
            ->andWhere(['tenant_id' => $this->model->tenant_id]);
    }
}
