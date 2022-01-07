<?php

namespace App\Tests\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Article;

class ApiTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/articles');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Article',
            '@id' => '/api/articles',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 3,
        ]);

        $this->assertCount(3, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Article::class);
    }

    public function testCreateArticle(): void
    {
        $response = static::createClient()->request('POST', '/api/articles', ['json' => [
            'title' => 'Test / Article',
            'leading' => 'Test / Leading',
            'body' => 'Test / Body',
            'createdBy' => 'Test / Author',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Article',
            '@type' => 'Article',
            'title' => 'Test / Article',
            'leading' => 'Test / Leading',
            'body' => 'Test / Body',
            'createdBy' => 'Test / Author',
        ]);
        $this->assertMatchesResourceItemJsonSchema(Article::class);
    }

    public function testCreateArticleWithNull(): void
    {
        $response = static::createClient()->request('POST', '/api/articles', ['json' => [
            'title' => 'Test / Article',
            'createdBy' => 'Test / Author',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Article',
            '@type' => 'Article',
            'title' => 'Test / Article',
            'leading' => null,
            'body' => null,
            'createdBy' => 'Test / Author',
        ]);
    }

    /*
        Test des slugs identiques
    */
    public function testCreateArticleWithSameTitle(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $response = static::createClient()->request('POST', '/api/articles', ['json' => [
                'title' => 'Test / Article / Slug',
                'createdBy' => 'Test / Author',
            ]]);

            $this->assertResponseStatusCodeSame(201);

            $this->assertJsonContains([
                '@context' => '/api/contexts/Article',
                '@type' => 'Article',
                'slug' => 'test-article-slug' . (0 === $i ? '' : ('-' . ($i + 1)))
            ]);
        }
    }
}
