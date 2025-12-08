<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\modules\admin\widgets\forms;

use Hirtz\Cms\tenant\models\Entry;
use Hirtz\Cms\tenant\modules\admin\widgets\forms\fields\TenantIdField;
use Hirtz\Cms\tenant\modules\admin\widgets\forms\traits\EntryTenantActiveFormTrait;
use Hirtz\Tenant\models\collections\TenantCollection;
use Stringable;
use Yii;

/**
 * @template T of Entry
 * @property Entry $model
 */
class EntryActiveForm extends \Hirtz\Cms\modules\admin\widgets\forms\EntryActiveForm
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
