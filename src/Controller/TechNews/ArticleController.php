<?php

namespace App\Controller\TechNews;

use App\Entity\Article;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * Démonstration, Ajouter un Article avec Doctrine
     * @Route("/article", name="article")
     */
    public function index()
    {
        # Création de la Catégorie
        $categorie = new Categorie();
        $categorie->setLibelle('Business');

        # Création de l'Auteur
        $auteur = new Auteur();
        $auteur->setPrenom('Hugo');
        $auteur->setNom('LIEGEARD');
        $auteur->setEmail('wf3@hl-media.fr');
        $auteur->setPassword('test');

        # Création de notre Article
        $article = new Article();
        $article->setTitre('Ceci est un titre');
        $article->setContenu('Ceci est un contenu');
        $article->setFeaturedimage('3.jpg');
        $article->setSpecial(0);
        $article->setSpotlight(1);

        # On associe une catégorie et un auteur à notre article
        $article->setCategorie($categorie);
        $article->setAuteur($auteur);

        # On sauvegarde le tout en BDD
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->persist($categorie);
        $em->persist($auteur);
        $em->flush();

        # Récupération des suggestions
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticleSuggestions($article->getId(),$article->getCategorie()->getId());

        # Retour à la vue
        return new Response (
            'Nouvel article Ajouté avec ID : ' .
            $article->getId() .
            ' et la nouvelle categorie ' .
            $categorie->getLibelle() .
            ' de ' .
            $auteur->getPrenom()
        );

    }
}