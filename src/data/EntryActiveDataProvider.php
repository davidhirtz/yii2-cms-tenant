<?php

namespace davidhirtz\yii2\cms\tenant\data;

use davidhirtz\yii2\cms\tenant\models\queries\EntryQuery;
use Yii;

/**
 * @property EntryQuery $query
 */
class EntryActiveDataProvider extends \davidhirtz\yii2\cms\modules\admin\data\EntryActiveDataProvider
{
    public int $tenantId;

    public function init(): void
    {
        $this->tenantId ??= Yii::$app->getRequest()->get('tenant') ?? Yii::$app->get('tenant')->id;
        parent::init();
    }

    protected function initQuery(): void
    {
        $this->query->andWhere(['tenant_id' => $this->tenantId]);
        parent::initQuery();
    }
}