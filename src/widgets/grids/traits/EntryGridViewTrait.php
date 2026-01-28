<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\widgets\grids\traits;

use davidhirtz\yii2\tenant\models\collections\TenantCollection;

trait EntryGridViewTrait
{
    use TenantDropdownTrait;

    protected function initHeader(): void
    {
        $this->header ??= [
            [
                [
                    'content' => $this->tenantDropdown(),
                    'options' => ['class' => 'col-12 col-md-3'],
                    'visible' => count(TenantCollection::getAll()) > 1,
                ],
                [
                    'content' => $this->typeDropdown(),
                    'options' => ['class' => 'col-12 col-md-3'],
                    'visible' => $this->showTypeDropdown,
                ],
                [
                    'content' => $this->categoryDropdown(),
                    'options' => ['class' => 'col-12 col-md-3'],
                    'visible' => $this->showCategoryDropdown,
                ],
                [
                    'content' => $this->getSearchInput(),
                    'options' => ['class' => 'col-12 col-md-3'],
                ],
                'options' => [
                    'class' => 'justify-content-between',
                ],
            ],
        ];
    }
}
