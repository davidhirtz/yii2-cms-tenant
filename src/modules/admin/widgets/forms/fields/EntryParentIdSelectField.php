<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\modules\admin\widgets\forms\fields;

use davidhirtz\yii2\cms\models\queries\EntryQuery;

class EntryParentIdSelectField extends \davidhirtz\yii2\cms\modules\admin\widgets\forms\fields\EntryParentIdSelectField
{
    protected function configure(): void
    {
        parent::configure();
        $this->registerClientScript();
    }

    protected function getEntryQuery(): EntryQuery
    {
        return parent::getEntryQuery()
            ->andWhere(['tenant_id' => $this->model->getAttribute('tenant_id')]);
    }

    protected function registerClientScript(): void
    {
        dump('TODO');
    }
//        $view = Yii::$app->getView();
//        $bundle = AssetBundle::register($view);
//
//        $slugs = [];
//        $slugs = Json::htmlEncode($slugs);
//
//        $js = <<<JS
//import init from "$bundle->baseUrl/dropdown.js";
//init("{$this->getTenantIdDropdownSelector()}", "#{$this->getId()}", $slugs)
//JS;
//
//        $view->registerJs($js, $view::POS_MODULE);
//    }
//
//    protected function getTenantIdDropdownSelector(): string
//    {
//        return '#' . Html::getInputId($this->model, 'tenant_id');
//    }
}
