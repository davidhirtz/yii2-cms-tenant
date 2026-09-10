<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Migrations;

use Hirtz\Cms\Migrations\Traits\SlugIndexTrait;
use Hirtz\Cms\Models\Entry;
use Hirtz\Skeleton\Db\Traits\MigrationTrait;
use Hirtz\Tenant\Models\Tenant;
use yii\db\Migration;

/**
 * @noinspection PhpUnused
 */
class M240819124325CmsTenant extends Migration
{
    use MigrationTrait;
    use SlugIndexTrait;

    public function safeUp(): void
    {
        $tenantId = Tenant::find()->select('id')->scalar();

        $this->addColumn(Entry::tableName(), 'tenant_id', (string)$this->integer()
            ->unsigned()
            ->null()
            ->after('type'));

        if ($tenantId) {
            $this->update(Entry::tableName(), ['tenant_id' => $tenantId]);
        }

        $this->createIndex('tenant_id', Entry::tableName(), ['tenant_id', 'status', 'position']);

        $tableName = $this->getDb()->getSchema()->getRawTableName(Entry::tableName());

        $this->addForeignKey(
            "{$tableName}_tenant_id_ibfk",
            Entry::tableName(),
            'tenant_id',
            Tenant::tableName(),
            'id',
            'CASCADE',
        );

        $after = 'language';

        foreach ($this->getEntryCountAttributeNames() as $attributeName) {
            $this->addColumn(Tenant::tableName(), $attributeName, (string)$this->integer()
                ->unsigned()
                ->notNull()
                ->defaultValue(0)
                ->after($after));

            $after = $attributeName;
        }

        $this->dropSlugIndex();
        $this->createSlugIndex();
    }

    public function safeDown(): void
    {
        foreach ($this->getEntryCountAttributeNames() as $attributeName) {
            $this->dropColumn(Tenant::tableName(), $attributeName);
        }

        $tableName = $this->getDb()->getSchema()->getRawTableName(Entry::tableName());
        $this->dropForeignKey("{$tableName}_tenant_id_ibfk", Entry::tableName());

        $this->dropIndex('tenant_id', Entry::tableName());
        $this->dropColumn(Entry::tableName(), 'tenant_id');
    }

    /**
     * @return array<array-key, string>
     */
    protected function getEntryCountAttributeNames(): array
    {
        return ['entry_count'];
    }
}
