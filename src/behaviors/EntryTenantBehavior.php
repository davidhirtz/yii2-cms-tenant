<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\behaviors;

use Hirtz\Cms\Bootstrap;
use Hirtz\Cms\Models\Entry;
use Hirtz\Cms\tenant\validators\TenantIdValidator;
use davidhirtz\yii2\datetime\DateTime;
use Hirtz\Skeleton\Models\Events\CreateValidatorsEvent;
use Hirtz\Tenant\models\collections\TenantCollection;
use Hirtz\Tenant\models\queries\TenantQuery;
use Hirtz\Tenant\models\Tenant;
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
    #[\Override]
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => $this->onBeforeValidate(...),
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

    protected function onBeforeValidate(): void
    {
        /** @var self $model */
        $model = $this->owner;

        if (!$model->tenant_id) {
            $model->populateTenantRelation(Yii::$app->get('tenant'));
        }
    }

    protected function onAfterValidate(): void
    {
        if (
            $this->owner->parent
            && $this->owner->parent->getAttribute('tenant_id') !== $this->owner->getAttribute('tenant_id')
        ) {
            $this->owner->addInvalidAttributeError('parent_id');
        }
    }

    protected function onAfterDelete(): void
    {
        $this->recalculateTenantEntryCount($this->owner->getAttribute('tenant_id'));
    }

    protected function onAfterInsert(): void
    {
        $this->recalculateTenantEntryCount($this->owner->getAttribute('tenant_id'));
    }

    protected function onAfterUpdate(AfterSaveEvent $event): void
    {
        if (array_key_exists('tenant_id', $event->changedAttributes)) {
            if ($this->owner->getAttribute('entry_count')) {
                Yii::debug('Updating descendants tenant...', __METHOD__);

                $descendantIds = $this->owner->findDescendants()
                    ->select('id')
                    ->column();

                if ($descendantIds) {
                    $attributes = [
                        'tenant_id' => $this->owner->getAttribute('tenant_id'),
                        'updated_by_user_id' => $this->owner->updated_by_user_id,
                        'updated_at' => $this->owner->updated_at,
                    ];

                    $this->owner::updateAll($attributes, ['id' => $descendantIds]);
                }
            }

            $this->recalculateTenantEntryCount($event->changedAttributes['tenant_id']);
            $this->recalculateTenantEntryCount($this->owner->getAttribute('tenant_id'));
        }
    }

    protected function onCreateValidators(CreateValidatorsEvent $event): void
    {
        $event->validators->append(new TenantIdValidator());
    }

    protected function recalculateTenantEntryCount(int $tenantId): void
    {
        $entryCount = Entry::find()
            ->where(['tenant_id' => $tenantId])
            ->count();

        $attributes = [
            $this->getTenantEntryCountAttributeName() => $entryCount,
            'updated_at' => new DateTime(),
        ];

        Tenant::updateAll($attributes, ['id' => $tenantId]);
    }

    public function getTenantEntryCountAttributeName(): string
    {
        return $this->owner::getModule()->enableI18nTables
            ? Yii::$app->getI18n()->getAttributeName('entry_count')
            : 'entry_count';
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
