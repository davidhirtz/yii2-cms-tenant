<?php

namespace davidhirtz\yii2\cms\tenant\models;

use davidhirtz\yii2\cms\models\queries\EntryQuery;
use davidhirtz\yii2\cms\tenant\behaviors\EntryTenantBehavior;
use Yii;

/**
 * This class can either be extended by the actual implementation or used as a reference for the implementation of the
 * extended `getRoute()` method.
 *
 * All other functionality is provided by the automatically attached `EntryTenantBehavior` class.
 *
 * @mixin EntryTenantBehavior
 */
class Entry extends \davidhirtz\yii2\cms\models\Entry
{
    public array|string|null $slugTargetAttribute = ['tenant_id', 'slug', 'parent_slug'];

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