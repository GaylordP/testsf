<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="index",
     *     methods="GET"
     * )
     */
    public function index(): Response {
        return $this->render('app/index.html.twig');
    }

    /**
     * @Route(
     *     "/article/{slug}",
     *     name="show",
     *     methods="GET"
     * )
     */
    public function show(
        Article $article
    ): Response {
        return $this->render('app/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route(
     *     "/creer",
     *     name="new",
     *     methods={"GET", "POST"}
     * )
     */
    public function new(): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        return $this->render('app/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
