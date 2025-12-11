<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Data;

use Hirtz\Cms\tenant\Models\Queries\EntryQuery;
use Hirtz\Tenant\Web\UrlManager;
use Override;
use Yii;

/**
 * @property EntryQuery $query
 */
class EntryActiveDataProvider extends \Hirtz\Cms\Modules\Admin\Data\EntryActiveDataProvider
{
    public ?int $tenantId;

    #[Override]
    public function init(): void
    {
        $tenantId = (int)Yii::$app->getRequest()->get('tenant');

        if (!$tenantId) {
            $manager = Yii::$app->getUrlManager();
            $tenantId = $manager instanceof UrlManager ? $manager->tenant->id : null;
        }

        $this->tenantId ??= $tenantId;

        parent::init();
    }

    #[Override]
    protected function initQuery(): void
    {
        if ($this->tenantId) {
            $this->query->andWhere(['tenant_id' => $this->tenantId]);
        }

        parent::initQuery();
    }
}
