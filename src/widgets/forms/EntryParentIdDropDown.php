<?php

namespace davidhirtz\yii2\cms\tenant\widgets\forms;

use davidhirtz\yii2\cms\models\queries\EntryQuery;
use davidhirtz\yii2\skeleton\helpers\Html;

class EntryParentIdDropDown extends \davidhirtz\yii2\cms\modules\admin\widgets\forms\fields\EntryParentIdDropDown
{
    public function init(): void
    {
        $this->setId($this->getId(false) ?? Html::getInputId($this->model, $this->attribute));
        $this->registerClientScript();

        parent::init();
    }

    protected function getEntryQuery(): EntryQuery
    {
        return parent::getEntryQuery()
            ->andWhere(['tenant_id' => $this->model->getAttribute('tenant_id')]);
    }

    protected function registerClientScript(): void
    {
        $this->getView()->registerJs(<<<JS
            (function () {
                const tenantIdDropdown = document.getElementById('{$this->getTenantIdDropdownId()}');
                const entryParentElementId = '{$this->getId()}';
                
                tenantIdDropdown.addEventListener('change', function () {
                   const url = new URL(window.location.href);
                   url.searchParams.set('tenant', tenantIdDropdown.value);
                   
                   const response = fetch(url).then(response => response.text()).then(text => {
                       const parser = new DOMParser();
        const doc = parser.parseFromString(text, 'text/html');

        // Find the element using the query selector
        const element = doc.getElementById(entryParentElementId);
        const dropdown = document.getElementById(entryParentElementId);
        dropdown.innerHTML = element.innerHTML;
        dropdown.dispatchEvent(new Event('change'));
                   });
                });
            })();
        JS
        );
    }

    protected function getTenantIdDropdownId(): string
    {
        return Html::getInputId($this->model, 'tenant_id');
    }
}