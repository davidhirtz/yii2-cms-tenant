<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\filters;

use Yii;

class PageCache extends \Hirtz\Skeleton\filters\PageCache
{
    public function init(): void
    {
        parent::init();

        if (!is_callable($this->variations)) {
            $this->variations[] = Yii::$app->get('tenant')->id;
        }
    }
}
