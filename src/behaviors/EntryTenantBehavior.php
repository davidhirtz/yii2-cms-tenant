<?php

namespace davidhirtz\yii2\cms\tenant\behaviors;

use davidhirtz\yii2\cms\Bootstrap;
use davidhirtz\yii2\cms\models\Entry;
use davidhirtz\yii2\cms\tenant\validators\TenantIdValidator;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use davidhirtz\yii2\tenant\models\traits\TenantRelationTrait;
use davidhirtz\yii2\skeleton\models\events\CreateValidatorsEvent;
use yii\base\Behavior;

/**
 * EntryTenantBehavior extends {@see Entry} by providing `tenant_id` validation. This behavior is attached on module
 * bootstrap by {@see Bootstrap}.
 *
 * @property Entry $owner
 */
class EntryTenantBehavior extends Behavior
{
    use TenantRelationTrait;

    public function events(): array
    {
        return [
            CreateValidatorsEvent::EVENT_CREATE_VALIDATORS => $this->onCreateValidators(...),
        ];
    }

    public function onCreateValidators(CreateValidatorsEvent $event): void
    {
        $event->validators->append(new TenantIdValidator());
    }

    public function getTenantRouteParams(): false|array
    {
        return [
            'tenant' => TenantCollection::getAll()[$this->owner->getAttribute('tenant_id')],
        ];
    }
}
