<?php

namespace davidhirtz\yii2\cms\tenant\models;

use davidhirtz\yii2\cms\tenant\behaviors\EntryTenantBehavior;

/**
 * This class can either be extended by the actual implementation or used as a reference for the implementation of the
 * extended `getRoute()` method.
 *
 * All other functionality is provided by the automatically attached `EntryTenantBehavior` class.
 */
class Entry extends \davidhirtz\yii2\cms\models\Entry
{
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

    public function getEntryTenantBehavior(): EntryTenantBehavior
    {
        /** @var EntryTenantBehavior $behavior */
        $behavior = $this->getBehavior('EntryTenantBehavior');
        return $behavior;
    }
}