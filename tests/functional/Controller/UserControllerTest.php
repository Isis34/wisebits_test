<?php

namespace App\Tests\functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    // @see DataFixtures\UserFixture;
    public function testGetUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/user/1');

        $content = $client->getResponse()->getContent();
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertResponseStatusCodeSame(200);
        $this->assertStringStartsWith('{"id":1,"name":"alex1234567","email":"alex@gmail.com","created":"', $content);
        $this->assertStringEndsWith(',"deleted":null,"notes":"some notes"}', $content);
    }

    public function testUpdateUser(): void
    {
        $client = static::createClient();
        $client->request('PUT', '/user/2', [
            'name' => 'somename',
            'email' => 'somemail@gmail.com',
            'notes' => '',
        ]);

        $content = $client->getResponse()->getContent();
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertResponseStatusCodeSame(200);
        $this->assertStringContainsString('"name":"somename","email":"somemail@gmail.com","created":"', $content);
        $this->assertStringEndsWith(',"deleted":null,"notes":""}', $content);
    }
}
