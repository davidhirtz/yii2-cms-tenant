<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant\models\queries;

use Hirtz\Tenant\models\queries\traits\TenantQueryTrait;

class EntryQuery extends \Hirtz\Cms\models\queries\EntryQuery
{
    use TenantQueryTrait;

    public function whereIndex(): static
    {
        return parent::whereIndex()
            ->andWhereCurrentTenant();
    }

    public function whereSlug(string $slug): static
    {
        return parent::whereSlug($slug)
            ->andWhereCurrentTenant();
    }

    public function selectSitemapAttributes(): static
    {
        return parent::selectSitemapAttributes()
            ->addSelect($this->prefixColumns(['tenant_id']))
            ->andWhereCurrentTenant();
    }
}
