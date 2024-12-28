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
                [
                    'content' => $this->tenantDropdown(),
                    'visible' => count(TenantCollection::getAll()) > 1,
                ],
                [
                    'content' => $this->typeDropdown(),
                    'visible' => $this->showTypeDropdown,
                ],
                [
                    'content' => $this->categoryDropdown(),
                    'visible' => $this->showCategoryDropdown,
                ],
                $this->search->getColumn(),
            ],
        ];
    }
}
