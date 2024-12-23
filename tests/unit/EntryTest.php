<?php

namespace davidhirtz\yii2\cms\tenant\tests\unit;

use Codeception\Test\Unit;
use davidhirtz\yii2\cms\tenant\models\Entry;
use davidhirtz\yii2\tenant\models\collections\TenantCollection;
use davidhirtz\yii2\tenant\models\Tenant;
use Yii;

class EntryTest extends Unit
{
    public function _before(): void
    {
        Yii::$app->set('tenant', current(TenantCollection::getAll()));
    }

    public function testCreateIndexEntry(): void
    {
        $entry = Entry::create();
        $entry->name = 'Home';
        $entry->slug = $entry::getModule()->entryIndexSlug;

        self::assertFalse($entry->save());
        self::assertNotEmpty($entry->getErrors('tenant_id'));

        $tenant = Yii::$app->get('tenant');
        $entry->populateTenantRelation($tenant);

        self::assertTrue($entry->save());
        self::assertTrue($entry->isIndex());
        self::assertEquals($entry->tenant_id, $tenant->id);

        $tenant->refresh();
        self::assertEquals(1, $tenant->getAttribute('entry_count'));

    }

    public function testCreateEntryValidationErrors()
    {
        $entry = Entry::create();
        $entry->name = 'Invalid';
        $entry->tenant_id = 12345;

        self::assertFalse($entry->save());
        self::assertNotEmpty($entry->getErrors('tenant_id'));
    }

//    public function testCreateI18nEntry(): void
//    {
//        Yii::$app->language = 'de';
//
//        self::assertEquals(0, TestEntry::find()->count());
//
//        $entry = TestEntry::create();
//        $entry->name = 'Startseite';
//        $entry->slug = $entry::getModule()->entryIndexSlug;
//
//        self::assertTrue($entry->save());
//        self::assertTrue($entry->isIndex());
//    }

    public function testUpdateEntry(): void
    {
        $tenant = Yii::$app->get('tenant');

        $entry = Entry::create();
        $entry->name = 'Test';
        $entry->populateTenantRelation($tenant);

        self::assertTrue($entry->save());

        $tenant->refresh();
        self::assertEquals(1, $tenant->getAttribute('entry_count'));

        $newTenant = Tenant::create();
        $newTenant->loadDefaultValues();
        $newTenant->name = 'New Tenant';
        $newTenant->language = Yii::$app->sourceLanguage;
        $newTenant->url = 'https://example.com';
        $newTenant->save();

        self::assertTrue($newTenant->save());

        $entry->tenant_id = $newTenant->id;

        self::assertTrue($entry->save());
        self::assertEquals($newTenant->id, $entry->tenant_id);

        $tenant->refresh();
        self::assertEquals(0, $tenant->getAttribute('entry_count'));

        $newTenant->refresh();
        self::assertEquals(1, $newTenant->getAttribute('entry_count'));
    }

//    public function testDeleteEntry(): void
//    {
//        $entry = $this->tester->grabEntryFixture('page-enabled');
//        $post = $this->tester->grabEntryFixture('post-1');
//
//        self::assertTrue(!!$entry->delete());
//        self::assertNull(TestEntry::findOne($entry->id));
//        self::assertNull(TestEntry::findOne($post->id));
//    }

//    public function testEntryAssets(): void
//    {
//        $entry = $this->tester->grabEntryFixture('page-enabled');
//
//        self::assertEquals(6, count($entry->assets));
//        self::assertEquals(1, count($entry->getVisibleAssets()));
//
//        $entry->populateAssetRelations();
//        self::assertEquals(2, count($entry->assets));
//    }
}
