<?php

namespace App\Controller\TechNews;


use App\Entity\Article;
use App\Entity\Categorie;
use App\Service\Article\ArticleProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{

    /**
     * Page d'accueil de notre site.
     * Configuration de notre route dans routes.yaml
     */
    public function index() {
        $repository = $this->getDoctrine()
            ->getRepository(Article::class);

        # Récupération des articles depuis la BDD
        $articles = $repository->findAll();

        # Récupération des articles du spotlight
        $spotlights = $repository->findSpotlightArticles();

        return $this->render('index/index.html.twig', [
            'articles' => $articles,
            'spotlights' => $spotlights
        ]);
    }

    /**
     * Page permettant d'afficher les articles d'une catégorie
     * @Route("/categorie/{libellecategorie}",
     *     name="index_categorie",
     *     requirements={"libellecategorie" = "\w+"},
     *     methods={"GET"})
     * @param string $libellecategorie
     * @return Response
     */
    public function categorie($libellecategorie = 'tout') {
        $categorie = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->findOneBy([
                'libelle' => $libellecategorie
                ]);
        $articles = $categorie->getArticles();

        return $this->render('index/categorie.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * Page permettant d'afficher un Article
     * @Route("/{libellecategorie}/{slugarticle}_{id}.html",
     *     name="index_article",
     *     requirements={"idarticle" = "\d+"})
     */
    public function article(Article $article) {
        # Recupération avec Doctrine
        // $article = $this->getDoctrine()
        //    ->getRepository(Article::class)
        //    ->find($idarticle);        -----> No need caus' we changed variables in function article()

        # Récupération des suggestions
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticleSuggestions($article->getId(),$article->getCategorie()->getId());

        # Récupération des lastArticles
        $lastArticles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findLastFiveArticle();

        # Si aucun article n'a été trouvé
        if(!$article) :
            # On génère une exception
            // throw $this->createNotFoundException("Nous n'avons pas trouvé d'article ID : $idarticle");

            # Ou on peut aussi rediriger l'utilisateur sur la page index
            return $this->redirectToRoute('index',[],Response::HTTP_MOVED_PERMANENTLY);

        endif;

        return $this->render('index/article.html.twig', [
            'article'           =>      $article,
            'suggestions'       =>      $suggestions,
            '$lastArticles'     =>      $lastArticles
        ]);

        #
    }

}
