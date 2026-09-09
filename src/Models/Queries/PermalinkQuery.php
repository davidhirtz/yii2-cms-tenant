<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Models\Queries;

use Hirtz\Cms\Tenant\Models\Permalink;
use Hirtz\Tenant\Models\Queries\Traits\TenantQueryTrait;

/**
 * @template T of Permalink
 * @extends \Hirtz\Cms\Models\Queries\PermalinkQuery<T>
 */
class PermalinkQuery extends \Hirtz\Cms\Models\Queries\PermalinkQuery
{
    use TenantQueryTrait;

    #[\Override]
    public function whereUri(string $uri, ?string $language = null): static
    {
        return parent::whereUri($uri, $language)
            ->andWhereCurrentTenant();
    }
}
