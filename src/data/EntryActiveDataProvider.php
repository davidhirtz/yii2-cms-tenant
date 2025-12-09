<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\data;

use Hirtz\Cms\tenant\models\queries\EntryQuery;
use Yii;

/**
 * @property EntryQuery $query
 */
class EntryActiveDataProvider extends \Hirtz\Cms\Modules\Admin\Data\EntryActiveDataProvider
{
    public int $tenantId;

    #[\Override]
    public function init(): void
    {
        $this->tenantId ??= (int)(Yii::$app->getRequest()->get('tenant') ?? Yii::$app->get('tenant')->id);
        parent::init();
    }

    #[\Override]
    protected function initQuery(): void
    {
        $this->query->andWhere(['tenant_id' => $this->tenantId]);
        parent::initQuery();
    }
}
