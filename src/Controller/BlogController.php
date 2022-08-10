<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue sur le blog',
            'age' => 36
        ]);
    }


    #[Route('/blog', name: 'app_blog')]
    public function index(ArticleRepository $repo): Response

    {
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'tabArticles' => $articles,
        ]);
    }


    #[Route('/blog/show/{id}', name: 'blog_show')]
    public function show($id, ArticleRepository $repo): Response
    {
        $article =  $repo->find($id);

        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/edit/{id}', name: 'blog_edit')]
    public function form(Request $superglobals, EntityManagerInterface $manager, Article $article = null)
    {

        if (!$article)
        {
            $article = new Article;
            $article->setCreatedAt(new \DateTime());
        }
        //$article = new Article;

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($superglobals);

        dump($article);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute("blog_show", [
                'id' => $article->getId()
            ]);
        }

        return $this->renderForm("blog/form.html.twig", [
            'formArticle' => $form,
            'editMode' => $article->getId() !== null
        ]);
    }



/**
     * @Route("/blog/delete/{id}", name="blog_delete")
     */
    public function delete(EntityManagerInterface $manager, $id, ArticleRepository $repo)
    {
        $article = $repo->find($id);

        // remove() prepare la suppression d'un article
        $manager->remove($article);

        // Execution de la requete preparée
        $manager->flush();

        // addFlash() permet de créer un message de notification
        // Le 1er argument est le type du message que l'on veut
        // Le 2nd argument est le message
        $this->addFlash('success', "L'article a bien été supprimé !");

        return $this->redirectToRoute('app_blog');
    }}
