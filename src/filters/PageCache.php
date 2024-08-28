<?php

namespace davidhirtz\yii2\cms\tenant\filters;

use Yii;

class PageCache extends \davidhirtz\yii2\skeleton\filters\PageCache
{
    public function init(): void
    {
        parent::init();

        if (is_array($this->variations)) {
            $this->variations[] = Yii::$app->get('tenant')->id;
        }
    }
}