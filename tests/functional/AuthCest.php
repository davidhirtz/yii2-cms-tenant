<?php

/**
 * @noinspection PhpUnused
 */

namespace davidhirtz\yii2\cms\tenant\tests\functional;

use davidhirtz\yii2\cms\tenant\tests\support\FunctionalTester;
use davidhirtz\yii2\tenant\models\Tenant;
use davidhirtz\yii2\tenant\modules\admin\data\TenantActiveDataProvider;
use davidhirtz\yii2\tenant\modules\admin\widgets\grids\TenantGridView;
use davidhirtz\yii2\skeleton\codeception\fixtures\UserFixtureTrait;
use davidhirtz\yii2\skeleton\codeception\functional\BaseCest;
use davidhirtz\yii2\skeleton\models\User;
use davidhirtz\yii2\skeleton\modules\admin\widgets\forms\LoginActiveForm;
use Yii;

class AuthCest extends BaseCest
{
    use UserFixtureTrait;

    public function checkIndexAsGuest(FunctionalTester $I): void
    {
        $I->amOnPage('/admin/tenant/index');

        $widget = Yii::createObject(LoginActiveForm::class);
        $I->seeElement("#$widget->id");
    }

    public function checkIndexWithoutPermission(FunctionalTester $I): void
    {
        $this->getLoggedInUser();

        $I->amOnPage('/admin/tenant/index');
        $I->seeResponseCodeIs(403);
    }

    public function checkIndexWithPermission(FunctionalTester $I): void
    {
        $user = $this->getLoggedInUser();
        $auth = Yii::$app->getAuthManager()->getPermission(Tenant::AUTH_TENANT_UPDATE);
        Yii::$app->getAuthManager()->assign($auth, $user->id);

        $widget = Yii::$container->get(TenantGridView::class, [], [
            'dataProvider' => Yii::createObject(TenantActiveDataProvider::class),
        ]);

        $I->amOnPage('/admin/tenant/index');
        $I->seeElement("#$widget->id");
    }

    protected function getLoggedInUser(): User
    {
        $user = User::find()->one();

        $webuser = Yii::$app->getUser();
        $webuser->loginType = 'test';
        $webuser->login($user);

        return $user;
    }
}
