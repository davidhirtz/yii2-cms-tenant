<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\widgets\grids;

use davidhirtz\yii2\cms\tenant\widgets\grids\traits\TenantDropdownTrait;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;

class EntryGridView extends \davidhirtz\yii2\cms\modules\admin\widgets\grids\EntryGridView
{
    use TenantDropdownTrait;

    protected function initHeader(): void
    {
        $this->header ??= [
            [
                count(TenantCollection::getAll()) > 1 ? $this->getTenantDropdown() : null,
                $this->showTypeDropdown ? $this->getTypeDropdown() : null,
                $this->showCategoryDropdown  ? $this->getCategoryDropdown() : null,
                $this->search->getToolbarItem(),
            ],
        ];
    }
}
