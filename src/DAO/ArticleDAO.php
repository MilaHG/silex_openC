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
    protected function buildDomainObject(array $row) { //changé par rapport au code OpenC protected et non private
        $article = new Article();
        $article->setId($row['art_id']);
        $article->setTitle($row['art_title']);
        $article->setContent($row['art_content']);
        return $article;
    }

    /**
     * Saves an article into the database
     *
     * @param \silex_openC\Domain\Article $article The article to save
     */
    public function save(Article $article) {
        $articleData = [
            'art_title' => $article->getTitle(),
            'art_content' => $article->getContent()
        ];

        if ($article->getId()) {
            // L'article existe => on le met à jour
            $this->getDb()->update('t_article', $articleData, array('art_id' => $article->getId()));
        } else {
            // L'article n'existe pas => on l'insère
            $this->getDb()->insert('t_article', $articleData);
            // Récupère l'ID du nouvel article pour l'attribuer à cet article
            $id = $this->getDb()->lastInsertId();
            $article->setId($id);
        }
    }

    /**
     * Removes an article from the database
     *
     * @param integer $id The article corresponding ID
     */
    public function delete($id) {
        // Suppression de l'article
        $this->getDb()->delete('t_article', ['art_id' => $id]);
    }

// la suppression d'un article entraîne celle des commentaires associés => cf. ComentDAO.php
}
