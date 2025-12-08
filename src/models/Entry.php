<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\models;

use Hirtz\Cms\Models\queries\EntryQuery;
use Hirtz\Cms\tenant\behaviors\EntryTenantBehavior;
use Yii;

/**
 * This class can either be extended by the actual implementation or used as a reference for the implementation of the
 * extended `getRoute()` method.
 *
 * All other functionality is provided by the automatically attached `EntryTenantBehavior` class.
 *
 * @mixin EntryTenantBehavior
 */
class Entry extends \Hirtz\Cms\Models\Entry
{
    public array|string|null $slugTargetAttribute = [
        'tenant_id',
        'slug',
        'parent_slug',
    ];

    public function getRoute(): false|array
    {
        $route = parent::getRoute();

        return $route
            ? [
                ...$route,
                ...$this->getEntryTenantBehavior()->getTenantRouteParams()
            ]
            : false;
    }

    public function findSiblings(): EntryQuery
    {
        return parent::findSiblings()->andWhere(['tenant_id' => $this->tenant_id]);
    }

    public function getEntryTenantBehavior(): EntryTenantBehavior
    {
        /** @var EntryTenantBehavior $behavior */
        $behavior = $this->getBehavior('EntryTenantBehavior');
        return $behavior;
    }

    public function attributeLabels(): array
    {
        return [
            ...parent::attributeLabels(),
            'tenant_id' => Yii::t('tenant', 'TENANT_NAME'),
        ];
    }
}
