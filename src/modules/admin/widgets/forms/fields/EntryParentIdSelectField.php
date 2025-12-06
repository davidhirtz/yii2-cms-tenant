<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms\fields;

use davidhirtz\yii2\cms\models\queries\EntryQuery;
use davidhirtz\yii2\cms\tenant\models\Entry;
use Yii;

/**
 * @property Entry $model
 */
class EntryParentIdSelectField extends \davidhirtz\yii2\cms\modules\admin\widgets\forms\fields\EntryParentIdSelectField
{
    protected function configure(): void
    {
        $this->attributes['data-id'] ??= 'parent';
        $this->attributes['promptAttributes']['data-value'][0] = $this->model->tenant->getAbsoluteUrl();

        parent::configure();
    }

    protected function getEntryQuery(): EntryQuery
    {
        return parent::getEntryQuery()
            ->andWhere(['tenant_id' => $this->model->tenant_id]);
    }
}
