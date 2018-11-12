<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;



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
     * @Route("/streaming", name="stream_page")
     */
    public function stream_page()
    {
        return $this->render('blog/stream.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/blog/newarticle", name="new_article")
     * @Route("/blog/{id}/edit", name="edit_article")
     */

    public function articleForm(Article $article = null, Request $request, ObjectManager $manager)
    {
        if(!$article) {
            $article = new Article();
        }

        $form = $this ->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class, array('attr' => array('class' => 'ckeditor')))
            ->add('image')
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
           $manager->flush();

           return $this->redirectToRoute('show_article', ['id' => $article->getId()]);
        }

        return $this->render('blog/new.html.twig', [
            'controller_name' => 'BlogController',
            'articleForm' => $form->createView(),
            'modify' => $article->getId() !==null,
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



