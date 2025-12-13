<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Tests\Models;

use Hirtz\Cms\Models\Section;
use Hirtz\Cms\Tenant\Models\Entry;
use Hirtz\Skeleton\Helpers\Url;
use Hirtz\Skeleton\Test\TestCase;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Hirtz\Tenant\Models\Tenant;
use Override;
use Yii;

class EntryTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        $this->config = require(__DIR__ . '/../config.php');
        parent::setUp();

        TenantCollection::invalidateCache();
    }

    protected function tearDown(): void
    {
        TenantCollection::invalidateCache();
        parent::tearDown();
    }

    public function testCreateIndexEntry(): void
    {
        $tenant = TenantCollection::getDefault();

        $entry = Entry::create();
        $entry->name = 'Home';
        $entry->slug = $entry::getModule()->entryIndexSlug;

        self::assertFalse($entry->save());
        self::assertArrayHasKey('tenant_id', $entry->getErrors());

        $entry->populateTenantRelation($tenant);

        self::assertTrue($entry->save());
        self::assertTrue($entry->isIndex());
        self::assertEquals($tenant->id, $entry->tenant_id);

        $tenant->refresh();

        self::assertEquals(1, $tenant->getAttribute('entry_count'));
    }

    public function testCreateEntryValidationErrors(): void
    {
        $entry = Entry::create();
        $entry->name = 'Invalid';
        $entry->tenant_id = 12345;

        self::assertFalse($entry->save());
        self::assertNotEmpty($entry->getErrors('tenant_id'));
    }

    public function testUpdateAndDeleteEntry(): void
    {
        $tenant = TenantCollection::getDefault();

        $entry = Entry::create();
        $entry->name = 'Test';
        $entry->populateTenantRelation($tenant);

        self::assertTrue($entry->insert());

        $section = Section::create();
        $section->populateEntryRelation($entry);

        self::assertTrue($section->insert());

        self::assertEquals('https://www.domain.localhost/test', Url::toRoute($entry->getRoute()));

        $tenant->refresh();
        self::assertEquals(1, $tenant->getAttribute('entry_count'));

        $newTenant = Tenant::create();
        $newTenant->loadDefaultValues();
        $newTenant->name = 'New Tenant';
        $newTenant->language = Yii::$app->sourceLanguage;
        $newTenant->url = 'https://www.new-domain.localhost';
        $newTenant->save();

        self::assertTrue($newTenant->save());

        $entry->tenant_id = $newTenant->id;

        self::assertTrue($entry->save());
        self::assertEquals($newTenant->id, $entry->tenant_id);
        self::assertEquals('https://www.new-domain.localhost/test', Url::toRoute($entry->getRoute()));

        $tenant->refresh();
        self::assertEquals(0, $tenant->getAttribute('entry_count'));

        $newTenant->refresh();
        self::assertEquals(1, $newTenant->getAttribute('entry_count'));

        self::assertFalse($newTenant->delete());
        self::assertContains('This tenant cannot be deleted because it is linked to other relations.', $newTenant->getFirstErrors());

        self::assertEquals(1, $entry->delete());

        $newTenant->refresh();
        self::assertEquals(0, $newTenant->getAttribute('entry_count'));
        self::assertEquals(1, $newTenant->delete());
    }
}
