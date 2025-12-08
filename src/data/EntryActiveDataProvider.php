<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\data;

use Hirtz\Cms\tenant\models\queries\EntryQuery;
use Yii;

/**
 * @property EntryQuery $query
 */
class EntryActiveDataProvider extends \Hirtz\Cms\modules\admin\data\EntryActiveDataProvider
{
    public int $tenantId;

    public function init(): void
    {
        $this->tenantId ??= (int)(Yii::$app->getRequest()->get('tenant') ?? Yii::$app->get('tenant')->id);
        parent::init();
    }

    protected function initQuery(): void
    {
        $this->query->andWhere(['tenant_id' => $this->tenantId]);
        parent::initQuery();
    }
}
