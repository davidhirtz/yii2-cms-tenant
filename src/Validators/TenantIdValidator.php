<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Validators;

use Hirtz\Cms\Tenant\Behaviors\EntryTenantBehavior;
use Hirtz\Cms\Tenant\Models\Entry;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Override;
use yii\base\NotSupportedException;
use yii\validators\Validator;

/**
 * TenantIdValidator validates the entry's `tenant_id`. The validator is automatically added to the model's validators
 * by {@see EntryTenantBehavior}.
 */
class TenantIdValidator extends Validator
{
    public $attributes = ['tenant_id'];
    public $skipOnEmpty = false;

    /**
     * @param Entry $model
     */
    #[Override]
    public function validateAttribute($model, $attribute): void
    {
        $tenantId = (int)$model->getAttribute($attribute);
        $tenant = TenantCollection::getAll()[$tenantId] ?? null;

        if ($tenantId && !$tenant) {
            $model->addInvalidAttributeError('tenant_id');
        }

        $model->populateTenantRelation($tenant);
    }

    #[Override]
    public function validate($value, &$error = null): bool
    {
        throw new NotSupportedException(static::class . ' does not support validate().');
    }
}
