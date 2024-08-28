<?php

namespace davidhirtz\yii2\cms\tenant\behaviors;

use davidhirtz\yii2\cms\models\Entry;
use davidhirtz\yii2\cms\models\traits\EntryRelationTrait;
use davidhirtz\yii2\cms\Module;
use davidhirtz\yii2\cms\tenant\Bootstrap;
use davidhirtz\yii2\tenant\models\Tenant;
use Yii;
use yii\base\Behavior;

/**
 * TenantEntryBehavior extends {@see Tenant} by updating related entries on deletion. This behavior is attached on
 * bootstrap by {@see Bootstrap}.
 *
 * @property Tenant $owner
 */
class TenantEntryBehavior extends Behavior
{
    use EntryRelationTrait;

    public function events(): array
    {
        return [
            Tenant::EVENT_AFTER_INSERT => $this->onAfterSave(...),
            Tenant::EVENT_AFTER_UPDATE => $this->onAfterSave(...),
            Tenant::EVENT_BEFORE_DELETE => $this->onBeforeDelete(...),
        ];
    }

    public function onAfterSave(): void
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('cms');
        $module->invalidatePageCache();
    }

    public function onBeforeDelete(): void
    {
        if ($entry = Entry::findOne(['tenant_id' => $this->owner->id])) {
            $entry->status = Entry::STATUS_DISABLED;
            $entry->update();
        }
    }
}
