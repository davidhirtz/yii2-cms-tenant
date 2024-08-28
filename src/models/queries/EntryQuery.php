<?php

namespace davidhirtz\yii2\cms\tenant\models\queries;

use davidhirtz\yii2\tenant\models\queries\traits\TenantQueryTrait;
use Yii;

class EntryQuery extends \davidhirtz\yii2\cms\models\queries\EntryQuery
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
        return $this->selectSitemapAttributes()
            ->addSelect($this->prefixColumns(['tenant_id']))
            ->andWhereCurrentClient();
    }
}