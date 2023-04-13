<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;

class PostTest extends ApiTestCase
{
    use RecreateDatabaseTrait;

    public function testCreateUser(): void
    {
        static::createClient()->request('POST', '/api/users',[
            'json' => [
                'date_update' => '1985-07-31',
                'date_create' => '1985-07-31',
                'username' => 'test_name',
                'password' => 'test_word'
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame(
            'Content-Type', 'application/ld+json; charset=utf-8'
        );
        $this->assertJsonContains(['username' => 'test_name']);
    }
}