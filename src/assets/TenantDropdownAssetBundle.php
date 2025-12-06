<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\assets;

class TenantDropdownAssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = __DIR__ . '/dist';
    public $js = ['dropdown.js'];
}
