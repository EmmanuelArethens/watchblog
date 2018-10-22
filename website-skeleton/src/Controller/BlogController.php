<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        return $this->render('blog/homepage.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/blog/newarticle", name="new_article")
     */
    public function new()
    {
        $article = new Article();

        $form = $this ->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();

        return $this->render('blog/new.html.twig', [
            'controller_name' => 'BlogController',
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/{id}", name="show_article")
     */
    public function showArticle($id)
    {   $repo = $this->getDoctrine()->getRepository(Article::class);

        $article = $repo->find($id);

        return $this->render('blog/article.html.twig', [
            'controller_name' => 'BlogController',
            'article' => $article,
        ]);
    }
}



