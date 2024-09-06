<?php

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
                    'options' => ['class' => 'col-12 col-md'],
                    'visible' => count(TenantCollection::getAll()) > 1,
                ],
                [
                    'content' => $this->typeDropdown(),
                    'options' => ['class' => 'col-12 col-md'],
                    'visible' => $this->showTypeDropdown,
                ],
                [
                    'content' => $this->categoryDropdown(),
                    'options' => ['class' => 'col-12 col-md'],
                    'visible' => $this->showCategoryDropdown,
                ],
                [
                    'content' => $this->getSearchInput(),
                    'options' => ['class' => 'col-12 col-md'],
                ],
                'options' => [
                    'class' => 'justify-content-between',
                ],
            ],
        ];
    }
}