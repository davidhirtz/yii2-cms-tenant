<?php

namespace davidhirtz\yii2\cms\tenant\migrations;

use davidhirtz\yii2\cms\models\Entry;
use davidhirtz\yii2\tenant\models\Tenant;
use davidhirtz\yii2\skeleton\db\traits\MigrationTrait;
use yii\db\Migration;

/**
 * @noinspection PhpUnused
 */

class M240819124325CmsTenant extends Migration
{
    use MigrationTrait;

    public function safeUp(): void
    {
        $tenantId = Tenant::find()->select('id')->scalar();

        $this->addColumn(Entry::tableName(), 'tenant_id', $this->integer()
            ->unsigned()
            ->notNull()
            ->after('type'));

        $this->update(Entry::tableName(), ['tenant_id' => $tenantId]);

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

        $this->addColumn(Tenant::tableName(), 'entry_count', $this->integer()
            ->unsigned()
            ->notNull()
            ->defaultValue(0)
            ->after('language'));
    }

    public function safeDown(): void
    {
        $this->dropColumn(Tenant::tableName(), 'entry_count');

        $tableName = $this->getDb()->getSchema()->getRawTableName(Entry::tableName());
        $this->dropForeignKey("{$tableName}_tenant_id_ibfk", Entry::tableName());

        $this->dropIndex('tenant_id', Entry::tableName());
        $this->dropColumn(Entry::tableName(), 'tenant_id');
    }
}
