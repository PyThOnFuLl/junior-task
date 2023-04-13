<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;

class UpdateTest extends ApiTestCase
{
    use RecreateDatabaseTrait;

    public function testUpdateUser(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/api/users/2', ['json' => [
                'password' => 'test_password',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/users/2',
            'password' => 'test_password',
        ]);
    }
}