<?php

namespace davidhirtz\yii2\cms\tenant\behaviors;

use davidhirtz\yii2\cms\Bootstrap;
use davidhirtz\yii2\cms\models\Entry;
use davidhirtz\yii2\cms\tenant\validators\TenantIdValidator;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use davidhirtz\yii2\tenant\models\Tenant;
use davidhirtz\yii2\tenant\models\traits\TenantRelationTrait;
use davidhirtz\yii2\skeleton\models\events\CreateValidatorsEvent;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

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
            BaseActiveRecord::EVENT_AFTER_DELETE => $this->onAfterDelete(...),
            BaseActiveRecord::EVENT_AFTER_INSERT => $this->onAfterInsert(...),
            BaseActiveRecord::EVENT_AFTER_UPDATE => $this->onAfterUpdate(...),
            CreateValidatorsEvent::EVENT_CREATE_VALIDATORS => $this->onCreateValidators(...),
        ];
    }

    protected function onAfterDelete(ModelEvent $event): void
    {
        $this->recalculateEntryCount($event);
    }

    protected function onAfterInsert(AfterSaveEvent $event): void
    {
        $this->recalculateEntryCount($event);
    }

    protected function onAfterUpdate(AfterSaveEvent $event): void
    {
        $this->recalculateEntryCount($event);
    }

    protected function onCreateValidators(CreateValidatorsEvent $event): void
    {
        $event->validators->append(new TenantIdValidator());
    }

    protected function recalculateEntryCount(Event $event): void
    {
        /** @var Tenant $entry */
        $tenant = $event->sender->tenant;

        $tenant->setAttribute('entry_count', Entry::find()
            ->where(['tenant_id' => $tenant->id])
            ->count());

        $tenant->update();
    }

    public function getTenantRouteParams(): false|array
    {
        return [
            'tenant' => TenantCollection::getAll()[$this->owner->getAttribute('tenant_id')],
        ];
    }
}
