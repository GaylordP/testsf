<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadArticles($manager);
    }

    private function loadArticles(ObjectManager $manager): void
    {
        foreach ($this->getData() as $data) {
            $article = new Article();
            $article->setTitle($data['title']);

            if (array_key_exists('leading', $data)) $article->setLeading($data['leading']);
            if (array_key_exists('body', $data)) $article->setLeading($data['body']);

            $article->setCreatedBy($data['createdBy']);

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getData(): iterable
    {
        yield [
            'title' => 'Article 1',
            'leading' => null,
            'body' => null,
            'createdBy' => 'Author',
        ];
        yield [
            'title' => 'Article 2',
            'leading' => null,
            'body' => 'Body article 2',
            'createdBy' => 'Author',
        ];
        yield [
            'title' => 'Article 3',
            'leading' => 'Leading article 3',
            'body' => 'Body article 3',
            'createdBy' => 'Author',
        ];
    }
}
