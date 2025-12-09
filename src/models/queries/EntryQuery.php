<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\models\queries;

use Hirtz\Tenant\models\queries\traits\TenantQueryTrait;

class EntryQuery extends \Hirtz\Cms\Models\queries\EntryQuery
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
