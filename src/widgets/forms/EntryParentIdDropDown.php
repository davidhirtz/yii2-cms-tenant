<?php

namespace davidhirtz\yii2\cms\tenant\widgets\forms;

use davidhirtz\yii2\cms\models\queries\EntryQuery;

class EntryParentIdDropDown extends \davidhirtz\yii2\cms\modules\admin\widgets\forms\fields\EntryParentIdDropDown
{
    public function init(): void
    {
        parent::init();
    }

    protected function getEntryQuery(): EntryQuery
    {
        return parent::getEntryQuery()
            ->andWhere(['tenant_id' => $this->model->tenant_id]);
    }
}