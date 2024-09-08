<?php

namespace davidhirtz\yii2\cms\tenant\behaviors;

use davidhirtz\yii2\cms\Bootstrap;
use davidhirtz\yii2\cms\models\Entry;
use davidhirtz\yii2\cms\tenant\validators\TenantIdValidator;
use davidhirtz\yii2\datetime\DateTime;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use davidhirtz\yii2\tenant\models\queries\TenantQuery;
use davidhirtz\yii2\tenant\models\Tenant;
use davidhirtz\yii2\skeleton\models\events\CreateValidatorsEvent;
use Yii;
use yii\base\Behavior;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * EntryTenantBehavior extends {@see Entry} by providing `tenant_id` validation. This behavior is attached on module
 * bootstrap by {@see Bootstrap}.
 *
 * @property Entry $owner
 * @property int $tenant_id
 * @property Tenant $tenant {@see self::getTenant()}
 */
class EntryTenantBehavior extends Behavior
{
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_VALIDATE => $this->onAfterValidate(...),
            BaseActiveRecord::EVENT_AFTER_DELETE => $this->onAfterDelete(...),
            BaseActiveRecord::EVENT_AFTER_INSERT => $this->onAfterInsert(...),
            BaseActiveRecord::EVENT_AFTER_UPDATE => $this->onAfterUpdate(...),
            CreateValidatorsEvent::EVENT_CREATE_VALIDATORS => $this->onCreateValidators(...),
        ];
    }

    public function getTenant(): TenantQuery
    {
        /** @var TenantQuery $relation */
        $relation = $this->owner->hasOne(Tenant::class, ['id' => 'tenant_id']);
        return $relation;
    }

    public function populateTenantRelation(?Tenant $tenant): void
    {
        $this->owner->populateRelation('tenant', $tenant);
        $this->owner->setAttribute('tenant_id', $tenant?->id);
    }

    protected function onAfterValidate(): void
    {
        if (
            $this->owner->parent
            && $this->owner->parent->getAttribute('tenant_id') != $this->owner->getAttribute('tenant_id')
        ) {
            $this->owner->addInvalidAttributeError('parent_id');
        }
    }

    protected function onAfterDelete(): void
    {
        $this->recalculateEntryCount();
    }

    protected function onAfterInsert(): void
    {
        $this->recalculateEntryCount();
    }

    protected function onAfterUpdate(AfterSaveEvent $event): void
    {
        if (in_array('tenant_id', $event->changedAttributes)) {
            if ($this->owner->getAttribute('entry_count')) {
                Yii::debug('Updating descendants tenant...', __METHOD__);

                $descendantIds = $this->owner->findDescendants()
                    ->select('id')
                    ->column();

                if ($descendantIds) {
                    $this->owner::updateAll([
                        'tenant_id' => $this->owner->getAttribute('tenant_id'),
                        'updated_by_user_id' => $this->owner->updated_by_user_id,
                        'updated_at' => $this->owner->updated_at,
                    ], [
                        'id' => $descendantIds,
                    ]);
                }
            }

            $this->recalculateEntryCount();
        }
    }

    protected function onCreateValidators(CreateValidatorsEvent $event): void
    {
        $event->validators->append(new TenantIdValidator());
    }

    protected function recalculateEntryCount(): void
    {
        $tenantId = $this->owner->getAttribute('tenant_id');
        $entryCount = Entry::find()->where(['tenant_id' => $tenantId])->count();

        Tenant::updateAll(['entry_count' => $entryCount, 'updated_at' => new DateTime()], [
            'id' => $tenantId,
        ]);
    }

    /**
     * @noinspection PhpUnused
     */
    public function getTenantRouteParams(): false|array
    {
        return [
            'tenant' => TenantCollection::getAll()[$this->owner->getAttribute('tenant_id')],
        ];
    }
}
