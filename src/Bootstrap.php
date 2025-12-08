<?php

declare(strict_types=1);

namespace Hirtz\Cms\tenant;

use Hirtz\Cms\models\Entry;
use Hirtz\Cms\models\queries\EntryQuery;
use Hirtz\Cms\modules\admin\data\EntryActiveDataProvider;
use Hirtz\Cms\modules\admin\widgets\forms\EntryActiveForm;
use Hirtz\Cms\modules\admin\widgets\forms\fields\EntryParentIdSelectField;
use Hirtz\Cms\modules\admin\widgets\grids\EntryGridView;
use Hirtz\Cms\modules\admin\widgets\grids\SectionParentEntryGridView;
use Hirtz\Cms\tenant\behaviors\EntryTenantBehavior;
use Hirtz\Cms\tenant\behaviors\TenantEntryBehavior;
use Hirtz\Skeleton\filters\PageCache;
use Hirtz\Skeleton\web\Application;
use Hirtz\Tenant\models\Tenant;
use Hirtz\Tenant\modules\admin\widgets\grids\TenantGridView;
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
            Entry::class => models\Entry::class,
            EntryActiveDataProvider::class => data\EntryActiveDataProvider::class,
            EntryActiveForm::class => modules\admin\widgets\forms\EntryActiveForm::class,
            EntryGridView::class => modules\admin\widgets\grids\EntryGridView::class,
            EntryParentIdSelectField::class => modules\admin\widgets\forms\fields\EntryParentIdSelectField::class,
            EntryQuery::class => models\queries\EntryQuery::class,
            PageCache::class => filters\PageCache::class,
            SectionParentEntryGridView::class => modules\admin\widgets\grids\SectionParentEntryGridView::class,
            TenantGridView::class => modules\admin\widgets\grids\TenantGridView::class,
        ];

        foreach ($definitions as $oldClass => $newClass) {
            $this->setDefaultClassDefinition($oldClass, $newClass);
        }

        $app->setModule('cms', [
            ...Yii::$app->getModules()['cms'],
            'enableI18nTables' => false,
        ]);

        $app->setMigrationNamespace('Hirtz\Cms\tenant\migrations');
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
