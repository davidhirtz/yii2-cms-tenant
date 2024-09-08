<?php

namespace davidhirtz\yii2\cms\tenant\widgets\forms;

use davidhirtz\yii2\cms\models\queries\EntryQuery;
use davidhirtz\yii2\cms\tenant\assets\AssetBundle;
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
        $view = $this->getView();

        AssetBundle::register($view);
        $view->registerJs(
            "initParentIdDropdown(\"#{$this->getTenantIdDropdownSelector()}\", \"#{$this->getId()}\");",
            $view::POS_END
        );
    }

    protected function getTenantIdDropdownSelector(): string
    {
        return '#' . Html::getInputId($this->model, 'tenant_id');
    }
}