<?php

namespace davidhirtz\yii2\cms\tenant\validators;

use davidhirtz\yii2\cms\models\Entry;
use davidhirtz\yii2\cms\tenant\behaviors\EntryTenantBehavior;
use davidhirtz\yii2\tenant\models\Tenant;
use yii\base\NotSupportedException;
use yii\validators\Validator;

/**
 * TenantIdValidator validates the entry's `tenant_id`. The validator is automatically added to the model's validators
 * by {@see EntryTenantBehavior}.
 */
class TenantIdValidator extends Validator
{
    /**
     * @var array|string
     */
    public $attributes = ['tenant_id'];

    /**
     * @param Entry $model
     */
    public function validateAttribute($model, $attribute): void
    {
        if ($model->isAttributeChanged($attribute)) {
            $tenantId = $model->getAttribute($attribute);
            $exists = Tenant::find()->where(['id' => $tenantId])->exists();

            if (!$exists) {
                $model->addInvalidAttributeError($attribute);
            }
        }
    }

    public function validate($value, &$error = null): bool
    {
        throw new NotSupportedException(static::class . ' does not support validate().');
    }
}
