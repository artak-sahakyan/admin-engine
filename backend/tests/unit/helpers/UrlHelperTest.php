<?php

namespace backend\tests\helpers;

use common\helpers\UrlHelper;

class UrlHelperTest extends \Codeception\Test\Unit
{

    // tests
    public function testGetDomain()
    {
        $domains = [
            'sovets.net' => [
                'sovets.net',
                'http://sovets.net',
                'http://sovets.net/',
                'http://sovets.net/admin',
                'http://sovets.net/admin/article',
                'http://sovets.net/',
                'http://sovets.net/admin',
                'https://sovets.net/admin/article',
            ],
            'v2dev.sovets.net' => [
                'v2dev.sovets.net',
                'http://v2dev.sovets.net/',
                'http://v2dev.sovets.net/',
                'http://v2dev.sovets.net/admin',
                'https://v2dev.sovets.net/admin/article',
            ],
        ];

        foreach ($domains as $expected => $groups) {
            foreach ($groups as $url) {
                $domain = UrlHelper::getDomain($url);

                $this->assertEquals($expected, $domain);
            }
        }
    }
}