<?php

namespace davidhirtz\yii2\cms\tenant\widgets\grids\traits;

use davidhirtz\yii2\skeleton\widgets\bootstrap\ButtonDropdown;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use Yii;
use yii\helpers\Url;

trait TenantDropdownTrait
{
    public ?int $tenantId = null;
    public string $tenantParamName = 'tenant';


    public function tenantDropdown(): string
    {
        $tenantId = $this->tenantId ?? Yii::$app->request->get($this->tenantParamName);
        $tenant = TenantCollection::getAll()[$tenantId] ?? Yii::$app->get('tenant');

        return ButtonDropdown::widget([
            'label' => $tenant->name,
            'items' => $this->tenantDropdownItems(),
            'paramName' => $this->tenantParamName,
            'defaultItem' => false,
        ]);
    }

    protected function tenantDropdownItems(): array
    {
        $items = [];

        foreach (TenantCollection::getAll() as $tenant) {
            $items[] = [
                'label' => $tenant->name,
                'url' => Url::current([
                    $this->tenantParamName => $tenant->id,
                    'parent' => null,
                    'page' => null,
                ]),
            ];
        }

        return $items;
    }
}