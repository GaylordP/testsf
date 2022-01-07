<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    public function testNew(): void
    {
        $client = static::createClient();
        $client->request('GET', '/creer');

        $this->assertResponseIsSuccessful();
    }
}
