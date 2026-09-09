<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Models\Queries;

use Hirtz\Tenant\Models\Queries\Traits\TenantQueryTrait;

/**
 * @extends \Hirtz\Cms\Models\Queries\EntryQuery<\Hirtz\Cms\Tenant\Models\Entry>
 */
class EntryQuery extends \Hirtz\Cms\Models\Queries\EntryQuery
{
    use TenantQueryTrait;

    #[\Override]
    public function whereIndex(): static
    {
        return parent::whereIndex()
            ->andWhereCurrentTenant();
    }

    #[\Override]
    public function whereSlug(string $slug): static
    {
        return parent::whereSlug($slug)
            ->andWhereCurrentTenant();
    }

    #[\Override]
    public function selectSitemapAttributes(): static
    {
        return parent::selectSitemapAttributes()
            ->addSelect($this->prefixColumns(['tenant_id']))
            ->andWhereCurrentTenant();
    }
}
