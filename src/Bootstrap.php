<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant;

use Hirtz\Cms\Models\Entry;
use Hirtz\Cms\Models\Queries\EntryQuery;
use Hirtz\Cms\Modules\Admin\Data\EntryActiveDataProvider;
use Hirtz\Cms\Modules\Admin\Widgets\Forms\EntryActiveForm;
use Hirtz\Cms\Modules\Admin\Widgets\Forms\Fields\EntryParentIdSelectField;
use Hirtz\Cms\Modules\Admin\Widgets\Grids\EntryGridView;
use Hirtz\Cms\Modules\Admin\Widgets\Grids\SectionParentEntryGridView;
use Hirtz\Cms\Tenant\Behaviors\EntryTenantBehavior;
use Hirtz\Cms\Tenant\Behaviors\TenantEntryBehavior;
use Hirtz\Skeleton\Filters\PageCache;
use Hirtz\Skeleton\Web\Application;
use Hirtz\Tenant\Models\Tenant;
use Hirtz\Tenant\Modules\Admin\Widgets\Grids\TenantGridView;
use Hirtz\Tenant\Web\UrlManager;
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
            'variations' => function () {
                $manager = Yii::$app->getUrlManager();
                return $manager instanceof UrlManager ? $manager->tenant?->id : null;
            },
        ]);

        $definitions = [
            Entry::class => Models\Entry::class,
            EntryActiveDataProvider::class => Data\EntryActiveDataProvider::class,
            EntryActiveForm::class => Modules\Admin\Widgets\Forms\EntryActiveForm::class,
            EntryGridView::class => Modules\Admin\Widgets\Grids\EntryGridView::class,
            EntryParentIdSelectField::class => Modules\Admin\Widgets\Forms\Fields\EntryParentIdSelectField::class,
            EntryQuery::class => Models\Queries\EntryQuery::class,
            PageCache::class => Filters\PageCache::class,
            SectionParentEntryGridView::class => Modules\Admin\Widgets\Grids\SectionParentEntryGridView::class,
            TenantGridView::class => Modules\Admin\Widgets\Grids\TenantGridView::class,
        ];

        foreach ($definitions as $oldClass => $newClass) {
            $this->setDefaultClassDefinition($oldClass, $newClass);
        }

        $app->setModule('cms', [
            ...(Yii::$app->getModules()['cms'] ?? []),
            'enableI18nTables' => false,
        ]);

        $app->setMigrationNamespace('Hirtz\Cms\Tenant\Migrations');
    }

    protected function attachEntryTenantBehavior(): void
    {
        Event::on(Entry::class, Entry::EVENT_INIT, function (Event $event): void {
            /** @var Entry $entry */
            $entry = $event->sender;
            $entry->attachBehavior('EntryTenantBehavior', EntryTenantBehavior::class);
        });
    }

    protected function attachTenantEntryBehavior(): void
    {
        Event::on(Tenant::class, Tenant::EVENT_INIT, function (Event $event): void {
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
