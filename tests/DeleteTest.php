<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;

class DeleteTest extends ApiTestCase
{
    use RecreateDatabaseTrait;

    public function testDelete()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/users/1');

        $this->assertResponseIsSuccessful();
    }
}
