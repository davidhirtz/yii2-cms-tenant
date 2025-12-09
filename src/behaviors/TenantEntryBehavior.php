<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\behaviors;

use Hirtz\Cms\Models\Entry;
use Hirtz\Cms\Models\traits\EntryRelationTrait;
use Hirtz\Cms\Module;
use Hirtz\Cms\tenant\Bootstrap;
use Hirtz\Tenant\models\Tenant;
use Yii;
use yii\base\Behavior;
use yii\base\ModelEvent;

/**
 * TenantEntryBehavior extends {@see Tenant} by updating related entries on deletion. This behavior is attached on
 * bootstrap by {@see Bootstrap}.
 *
 * @property Tenant $owner
 */
class TenantEntryBehavior extends Behavior
{
    use EntryRelationTrait;

    #[\Override]
    public function events(): array
    {
        return [
            Tenant::EVENT_AFTER_INSERT => $this->onAfterSave(...),
            Tenant::EVENT_AFTER_UPDATE => $this->onAfterSave(...),
            Tenant::EVENT_BEFORE_DELETE => $this->onBeforeDelete(...),
        ];
    }

    protected function onAfterSave(): void
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('cms');
        $module->invalidatePageCache();
    }

    protected function onBeforeDelete(ModelEvent $event): void
    {
        $hasEntries = Entry::find()
            ->andWhere(['tenant_id' => $this->owner->id])
            ->exists();

        $event->isValid = !$hasEntries;
    }
}
