<?php

/**
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace Hirtz\Cms\tenant\tests\functional;

use Hirtz\Cms\tenant\tests\support\FunctionalTester;
use Hirtz\Skeleton\Codeception\fixtures\UserFixtureTrait;
use Hirtz\Skeleton\Codeception\functional\BaseCest;
use Hirtz\Skeleton\Models\User;
use Hirtz\Tenant\Models\Tenant;
use Yii;

class AuthCest extends BaseCest
{
    use UserFixtureTrait;

    public function checkIndexAsGuest(FunctionalTester $I): void
    {
        $I->amOnPage('/admin/tenant/index');
        $I->seeElement("#login-form");
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

        $I->amOnPage('/admin/tenant/index');
        $I->seeElement("#tenants");
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
