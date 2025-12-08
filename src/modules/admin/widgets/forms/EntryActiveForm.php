<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms;

use davidhirtz\yii2\cms\tenant\models\Entry;
use davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms\fields\TenantIdField;
use davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms\traits\EntryTenantActiveFormTrait;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use Stringable;
use Yii;

/**
 * @template T of Entry
 * @property Entry $model
 */
class EntryActiveForm extends \davidhirtz\yii2\cms\modules\admin\widgets\forms\EntryActiveForm
{
    use EntryTenantActiveFormTrait;

    protected function configure(): void
    {
        $this->setTenantFromRequest();

        parent::configure();

        if (count(TenantCollection::getAll()) > 1) {
            array_unshift($this->rows, [$this->getTenantIdField()]);
        }
    }
}
