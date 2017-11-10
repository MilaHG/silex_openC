<?php

namespace silex_openC\src\DAO;

use silex_openC\src\Domain\Article;

class ArticleDAO extends DAO {

//La classe ArticleDAO hérite (mot-clé extends) de la classe abstraite DAO
    /**
     * Return a list of all articles, sorted by date (most recent first)
     *
     * @return array A list of all articles
     */
    public function findAll() {
        $sql = "select * from t_article order by art_id desc";
        $result = $this->getDb()->fetchAll($sql);

        //Convert query result to an array of domain objects
        $articles = array();
        foreach ($result as $row) {
            $articleId = $row['art_id'];
            $articles[$articleId] = $this->buildDomainObject($row);
        }
        return $articles;
    }

    /**
     * Return an Article matching the supplied ID
     *
     * @param integer $id
     *
     * @return \silex_openC\src\Domain\Article |throws an exception if no matching article is found
     */
    public function find($id) {
        $sql = "SELECT * FROM t_article WHERE art_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row) {
            return $this->buildDomainObject($row);
        } else {
            throw new \Exception("Pas d'article correspondant à l'id " . $id);
        }
    }

    /**
     * Creates an Article object based on a DB row
     *
     * @param array $row La ligne de la BDD contenant les données de l'Article
     * @return Article
     */
    protected function buildDomainObject(array $row) {
        $article = new Article();
        $article->setId($row['art_id']);
        $article->setTitle($row['art_title']);
        $article->setContent($row['art_content']);
        return $article;
    }

//changé par rapport au code OpenC protected et non private
}
