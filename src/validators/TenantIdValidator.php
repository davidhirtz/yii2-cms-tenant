<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\validators;

use Hirtz\Cms\Models\Entry;
use Hirtz\Cms\tenant\behaviors\EntryTenantBehavior;
use Hirtz\Tenant\Models\Tenant;
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
    #[\Override]
    public function validateAttribute($model, $attribute): void
    {
        $tenantId = (int)$model->getAttribute($attribute);
        $model->setAttribute($attribute, $tenantId);

        if (!$model->isAttributeChanged($attribute)) {
            return;
        }

        $tenantId = $model->getAttribute($attribute);
        $exists = Tenant::find()->where(['id' => $tenantId])->exists();

        if (!$exists) {
            $model->addInvalidAttributeError($attribute);
        }
    }

    #[\Override]
    public function validate($value, &$error = null): bool
    {
        throw new NotSupportedException(static::class . ' does not support validate().');
    }
}
