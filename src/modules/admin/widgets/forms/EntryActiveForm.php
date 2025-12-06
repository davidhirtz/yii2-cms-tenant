<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms;

use davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms\fields\TenantIdField;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use Stringable;

class EntryActiveForm extends \davidhirtz\yii2\cms\modules\admin\widgets\forms\EntryActiveForm
{
    protected function configure(): void
    {
        parent::configure();

        if (count(TenantCollection::getAll()) > 1) {
            array_unshift($this->rows, [$this->getTenantIdField()]);
        }
    }

    protected function getTenantIdField(): ?Stringable
    {
        return TenantIdField::make();
    }
}