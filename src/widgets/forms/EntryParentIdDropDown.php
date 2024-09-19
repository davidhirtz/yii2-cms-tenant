<?php

namespace davidhirtz\yii2\cms\tenant\widgets\forms;

use davidhirtz\yii2\cms\models\queries\EntryQuery;
use davidhirtz\yii2\cms\tenant\assets\AssetBundle;
use davidhirtz\yii2\skeleton\helpers\Html;
use Yii;
use yii\helpers\Json;

/**
 *
 */
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
        $view = Yii::$app->getView();
        $bundle = AssetBundle::register($view);

        $slugs = [];
        $slugs = Json::htmlEncode($slugs);

        $js = <<<JS
import init from "$bundle->baseUrl/dropdown.js";
init("{$this->getTenantIdDropdownSelector()}", "#{$this->getId()}", $slugs)
JS;

        $view->registerJs($js, $view::POS_MODULE);
    }

    protected function getTenantIdDropdownSelector(): string
    {
        return '#' . Html::getInputId($this->model, 'tenant_id');
    }
}