<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Tests\Models;

use Hirtz\Cms\Tenant\Models\Entry;
use Hirtz\Cms\Tenant\Models\Permalink;
use Hirtz\Tenant\Models\Collections\TenantCollection;
use Hirtz\Tenant\Models\Tenant;
use Hirtz\Tenant\Test\TestCase;
use Hirtz\Tenant\Test\Traits\TenantFixtureTrait;
use Override;
use Yii;

/**
 * Two tenants may serve the same slug, so a permalink is only unique per tenant.
 */
class EntryPermalinkTest extends TestCase
{
    use TenantFixtureTrait;

    #[Override]
    protected function setUp(): void
    {
        $this->config = require(__DIR__ . '/../../config/test.php');
        parent::setUp();
    }

    public function testPermalinkIsScopedToItsTenant(): void
    {
        $first = TenantCollection::getDefault();
        $second = $this->createTenant();

        $one = $this->createEntry('home', $first);
        $two = $this->createEntry('home', $second);

        self::assertNotNull($this->findPermalink($one), 'The first tenant has no permalink.');
        self::assertNotNull($this->findPermalink($two), 'The second tenant lost its permalink to the first.');

        self::assertSame($first->id, $this->findPermalink($one)->tenant_id);
        self::assertSame($second->id, $this->findPermalink($two)->tenant_id);
    }

    public function testPermalinkIsFoundForTheCurrentTenantOnly(): void
    {
        $first = TenantCollection::getDefault();
        $second = $this->createTenant();

        $this->createEntry('home', $first);
        $this->createEntry('home', $second);

        $permalinks = Permalink::find()
            ->whereUri('home')
            ->andWhereTenant($second)
            ->all();

        self::assertCount(1, $permalinks);
        self::assertSame($second->id, $permalinks[0]->tenant_id);
    }

    protected function findPermalink(Entry $entry): ?Permalink
    {
        return Permalink::find()
            ->whereModel($entry::class, $entry->id)
            ->whereLanguage()
            ->one();
    }

    protected function createTenant(): Tenant
    {
        $tenant = Tenant::create();
        $tenant->loadDefaultValues();
        $tenant->name = 'Second Tenant';
        $tenant->language = Yii::$app->sourceLanguage;
        $tenant->url = 'https://www.second-domain.localhost';

        self::assertTrue($tenant->save(), implode(' ', $tenant->getErrorSummary(true)));

        return $tenant;
    }

    protected function createEntry(string $slug, Tenant $tenant): Entry
    {
        $entry = Entry::create();
        $entry->name = ucfirst($slug);
        $entry->slug = $slug;
        $entry->populateTenantRelation($tenant);

        self::assertTrue($entry->save(), implode(' ', $entry->getErrorSummary(true)));

        return $entry;
    }
}
