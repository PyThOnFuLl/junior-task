<?php

namespace App\Tests;

use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetTest extends WebTestCase
{
    use RecreateDatabaseTrait;

    public function testGetUser(): void
    {
        $client = static::createClient();
        $user = new User();
        $user->setUsername('test_user');
        $user->setPassword('test_password');
        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $client->request('GET', '/api/users/' . $user->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('test_user', $client->getResponse()->getContent());
    }
}
