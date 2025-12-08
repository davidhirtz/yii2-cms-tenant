<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\assets;

use yii\web\AssetBundle;

class TenantDropdownAssetBundle extends AssetBundle
{
    public $js = ['dropdown.js'];
    public $sourcePath = __DIR__ . '/../../assets/dist';
}
