<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Models;

use Hirtz\Skeleton\I18n\Lang;
use Hirtz\Cms\Models\Queries\EntryQuery;
use Hirtz\Cms\Tenant\Behaviors\EntryTenantBehavior;
use Override;

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
    /**
     * @return array<string, mixed>|false
     */
    #[Override]
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

    #[Override]
    public function getRouteParams(): array
    {
        return [
            ...parent::getRouteParams(),
            ...$this->getEntryTenantBehavior()->getTenantRouteParams(),
        ];
    }

    /**
     * @return EntryQuery<static>
     */
    #[Override]
    public function findSiblings(): EntryQuery
    {
        return parent::findSiblings()->andWhere(['tenant_id' => $this->tenant_id]);
    }

    #[Override]
    public function getPermalinkAttributes(): array
    {
        return [
            ...parent::getPermalinkAttributes(),
            'tenant_id' => $this->getAttribute('tenant_id'),
        ];
    }

    public function getEntryTenantBehavior(): EntryTenantBehavior
    {
        /** @var EntryTenantBehavior $behavior */
        $behavior = $this->getBehavior('EntryTenantBehavior');
        return $behavior;
    }

    #[Override]
    public function attributeLabels(): array
    {
        return [
            ...parent::attributeLabels(),
            'tenant_id' => Lang::t('tenant', 'TENANT_NAME'),
        ];
    }
}
