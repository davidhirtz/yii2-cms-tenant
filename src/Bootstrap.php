<?php

declare(strict_types=1);

namespace davidhirtz\yii2\cms\tenant;

use davidhirtz\yii2\cms\models\Entry;
use davidhirtz\yii2\cms\models\queries\EntryQuery;
use davidhirtz\yii2\cms\modules\admin\data\EntryActiveDataProvider;
use davidhirtz\yii2\cms\modules\admin\widgets\forms\fields\EntryParentIdSelectField;
use davidhirtz\yii2\cms\modules\admin\widgets\grids\EntryGridView;
use davidhirtz\yii2\cms\tenant\behaviors\EntryTenantBehavior;
use davidhirtz\yii2\cms\tenant\behaviors\TenantEntryBehavior;
use davidhirtz\yii2\skeleton\filters\PageCache;
use davidhirtz\yii2\skeleton\web\Application;
use davidhirtz\yii2\tenant\models\Tenant;
use davidhirtz\yii2\tenant\modules\admin\widgets\grids\TenantGridView;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;

class Bootstrap implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app): void
    {
        $this->attachEntryTenantBehavior();
        $this->attachTenantEntryBehavior();

        $app->extendComponent('sitemap', [
            'variations' => fn () => Yii::$app->get('tenant')->id,
        ]);

        $definitions = [
            EntryActiveDataProvider::class => data\EntryActiveDataProvider::class,
            EntryGridView::class => modules\admin\widgets\grids\EntryGridView::class,
            EntryParentIdSelectField::class => modules\admin\widgets\forms\fields\EntryParentIdSelectField::class,
            EntryQuery::class => models\queries\EntryQuery::class,
            TenantGridView::class => modules\admin\widgets\grids\TenantGridView::class,
            PageCache::class => filters\PageCache::class,
        ];

        foreach ($definitions as $oldClass => $newClass) {
            $this->setDefaultClassDefinition($oldClass, $newClass);
        }

        $app->setModule('cms', [
            ...Yii::$app->getModules()['cms'],
            'enableI18nTables' => false,
        ]);

        $app->setMigrationNamespace('davidhirtz\yii2\cms\tenant\migrations');
    }

    protected function attachEntryTenantBehavior(): void
    {
        Event::on(Entry::class, Entry::EVENT_INIT, function (Event $event) {
            /** @var Entry $entry */
            $entry = $event->sender;
            $entry->attachBehavior('EntryTenantBehavior', EntryTenantBehavior::class);
        });
    }

    protected function attachTenantEntryBehavior(): void
    {
        Event::on(Tenant::class, Tenant::EVENT_INIT, function (Event $event) {
            /** @var Tenant $tenant */
            $tenant = $event->sender;
            $tenant->attachBehavior('TenantEntryBehavior', TenantEntryBehavior::class);
        });
    }

    protected function setDefaultClassDefinition(string $oldClass, string $newClass): void
    {
        $definition = Yii::$container->getDefinitions()[$oldClass] ?? [];

        if (!is_string($definition)) {
            $definition['class'] ??= $newClass;
            Yii::$container->set($oldClass, $definition);
        }
    }
}
