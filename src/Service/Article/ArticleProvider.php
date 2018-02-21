<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20/02/2018
 * Time: 12:00
 */

namespace App\Service\Article;


use Symfony\Component\Yaml\Yaml;

class ArticleProvider
{
    /*
     *  Recupère, parse et retourne les articles depuis articles.yaml
     */
    public function getArticle()  {
        $articles = Yaml::parseFile(__DIR__ . '/articles.yaml');
        return $articles['data'];
    }
}