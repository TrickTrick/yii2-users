<?php
/**
 * Created by PhpStorm.
 * User: TrickTrick
 * Date: 03-Mar-17
 * Time: 13:05
 */

namespace frontend\tests\functional;

use frontend\tests\ApiTester;

class RestCest
{
    /**
     * @param ApiTester $I
     */
    public function signupUser(ApiTester $I)
    {
        $I->wantTo('signup user');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', '*/*');
        $I->haveHttpHeader('Cache-Control', 'no-cache');
        $I->sendPOST('users', ['username' => 'example', 'email' => 'testo@test.com', 'password' => 'qweqwe', "country" => "USA", "birthday" => "2017-02-28", "role" => "10"]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 200
        $I->seeResponseIsJson();
    }
}