<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Behaviors;

use davidhirtz\yii2\datetime\DateTime;
use Hirtz\Cms\Bootstrap;
use Hirtz\Cms\Models\Entry;
use Hirtz\Cms\Tenant\Validators\TenantIdValidator;
use Hirtz\Skeleton\Models\Events\CreateValidatorsEvent;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Hirtz\Tenant\Models\Queries\TenantQuery;
use Hirtz\Tenant\Models\Tenant;
use Override;
use Yii;
use yii\base\Behavior;
use yii\db\AfterSaveEvent;
use yii\db\BaseActiveRecord;

/**
 * EntryTenantBehavior extends {@see Entry} by providing `tenant_id` validation. This behavior is attached on module
 * bootstrap by {@see Bootstrap}.
 *
 * @property int $tenant_id
 * @property Tenant $tenant {@see self::getTenant()}
 *
 * @extends Behavior<Entry>
 */
class EntryTenantBehavior extends Behavior
{
    /**
     * @return array<string, callable>
     */
    #[Override]
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

    /**
     * @return TenantQuery<Tenant>
     */
    public function getTenant(): TenantQuery
    {
        /** @var TenantQuery<Tenant> $relation */
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

    protected function recalculateTenantEntryCount(?int $tenantId): void
    {
        if (null === $tenantId) {
            return;
        }

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
        return 'entry_count';
    }

    /**
     * @return array<string, Tenant|null>
     */
    public function getTenantRouteParams(): array
    {
        $tenantId = $this->owner->getAttribute('tenant_id');

        return [
            'tenant' => $tenantId ? TenantCollection::getAll()[$tenantId] ?? null : null,
        ];
    }
}
