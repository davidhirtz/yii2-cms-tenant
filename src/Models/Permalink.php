<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Models;

use Hirtz\Cms\Tenant\Models\Queries\PermalinkQuery;
use Hirtz\Tenant\Models\Tenant;
use Override;
use Yii;

/**
 * Scopes a permalink to one tenant, so two tenants can serve the same URL.
 *
 * @property int|null $tenant_id
 */
class Permalink extends \Hirtz\Cms\Models\Permalink
{
    public array $uriTargetAttribute = ['tenant_id', 'language', 'uri'];

    /**
     * @return PermalinkQuery<static>
     */
    #[Override]
    public static function find(): PermalinkQuery
    {
        return Yii::createObject(PermalinkQuery::class, [static::class]);
    }

    #[Override]
    public function attributeLabels(): array
    {
        return [
            ...parent::attributeLabels(),
            'tenant_id' => Tenant::instance()->getAttributeLabel('name'),
        ];
    }
}
