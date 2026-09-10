<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Migrations;

use Hirtz\Cms\Models\Entry;
use Hirtz\Cms\Models\Permalink;
use Hirtz\Skeleton\Db\Traits\MigrationTrait;
use Hirtz\Tenant\Models\Tenant;
use yii\db\Migration;

/**
 * Scopes permalinks to a tenant. Without this the unique index is `(language, uri)`, so the second tenant to use a
 * slug silently loses its permalink to the first.
 *
 * @noinspection PhpUnused
 */
class M260909140000PermalinkTenant extends Migration
{
    use MigrationTrait;

    public function safeUp(): void
    {
        $this->addColumn(Permalink::tableName(), 'tenant_id', (string)$this->integer()
            ->unsigned()
            ->null()
            ->after('model_id'));

        $this->backfillTenantIds();

        $this->dropIndexIfExists('uri', Permalink::tableName());
        $this->createIndex('uri', Permalink::tableName(), ['tenant_id', 'language', 'uri'], true);

        $this->addForeignKey(
            $this->getForeignKeyName(Permalink::tableName(), 'tenant_id') . '_ibfk',
            Permalink::tableName(),
            'tenant_id',
            Tenant::tableName(),
            'id',
            'CASCADE'
        );
    }

    public function safeDown(): void
    {
        $this->dropForeignKey(
            $this->getForeignKeyName(Permalink::tableName(), 'tenant_id') . '_ibfk',
            Permalink::tableName()
        );

        $this->dropIndexIfExists('uri', Permalink::tableName());
        $this->dropColumn(Permalink::tableName(), 'tenant_id');

        $this->createIndex('uri', Permalink::tableName(), ['language', 'uri'], true);
    }

    /**
     * Entry permalinks written before this migration carry no tenant, so they are matched back to their entry.
     */
    protected function backfillTenantIds(): void
    {
        $db = $this->getDb();
        $schema = $db->getSchema();

        $entry = Entry::create();

        $permalinks = $db->quoteTableName($schema->getRawTableName(Permalink::tableName()));
        $entries = $db->quoteTableName($schema->getRawTableName($entry::tableName()));

        // Matched on the canonical model class the permalink stores, and filtered by it so a category sharing an id
        // with an entry cannot inherit its tenant.
        $this->execute("
            UPDATE $permalinks AS [[permalink]]
            INNER JOIN $entries AS [[entry]]
                ON [[entry]].[[id]] = [[permalink]].[[model_id]]
            SET [[permalink]].[[tenant_id]] = [[entry]].[[tenant_id]]
            WHERE [[permalink]].[[model]] = {$db->quoteValue($entry->getPermalinkModelClass())}
        ");
    }
}
