<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant\migrations;

use davidhirtz\yii2\cms\migrations\traits\I18nTablesTrait;
use davidhirtz\yii2\cms\models\Entry;
use davidhirtz\yii2\skeleton\db\traits\MigrationTrait;
use davidhirtz\yii2\tenant\models\Tenant;
use Yii;
use yii\db\Migration;

/**
 * @noinspection PhpUnused
 */

class M240819124325CmsTenant extends Migration
{
    use MigrationTrait;
    use I18nTablesTrait;

    public function safeUp(): void
    {
        $tenantId = Tenant::find()->select('id')->scalar();

        $this->i18nTablesCallback(function () use ($tenantId) {
            $this->addColumn(Entry::tableName(), 'tenant_id', (string)$this->integer()
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
        });

        $after = 'language';

        foreach ($this->getEntryCountAttributeNames() as $attributeName) {
            $this->addColumn(Tenant::tableName(), $attributeName, (string)$this->integer()
                ->unsigned()
                ->notNull()
                ->defaultValue(0)
                ->after($after));

            $after = $attributeName;
        }
    }

    public function safeDown(): void
    {
        foreach ($this->getEntryCountAttributeNames() as $attributeName) {
            $this->dropColumn(Tenant::tableName(), $attributeName);
        }

        $this->i18nTablesCallback(function () {
            $tableName = $this->getDb()->getSchema()->getRawTableName(Entry::tableName());
            $this->dropForeignKey("{$tableName}_tenant_id_ibfk", Entry::tableName());

            $this->dropIndex('tenant_id', Entry::tableName());
            $this->dropColumn(Entry::tableName(), 'tenant_id');
        });
    }

    protected function getEntryCountAttributeNames(): array
    {
        return array_map(fn ($lang) => Yii::$app->getI18n()->getAttributeName('entry_count', $lang), $this->getLanguages());
    }
}
