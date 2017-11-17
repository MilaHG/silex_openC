<?php

namespace silex_openC\src\DAO;

use silex_openC\src\Domain\Comment;

class CommentDAO extends DAO {

    /**
     * @var \silex_openC\src\DAO\ArticleDAO
     */
    private $articleDAO;

    /**
     * @var \silex_openC\src\DAO\UserDAO
     */
    private $userDAO;

    public function setArticleDAO(ArticleDAO $articleDAO) {
        $this->articleDAO = $articleDAO;
    }

    public function setUserDAO(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
    }

    /**
     * Returns a list of all comments, sorted by date (most recent first) - for the Admin
     *
     * @param array A list of all comments
     */
    public function findAll() {
        $sql = "SELECT * FROM t_comment ORDER BY com_id DESC";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result in an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['com_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }

    /**
     * Return a list of all comments for an article,
     * sorted by date (most recent last)
     *
     * @param integer $articleId The id of the
     * article
     *
     * @return array A list of all comments for the
     * concerned article
     */
    public function findAllByArticle($articleId) {
        //the associated article is retrieved ONLY once => find() and NOT findAll()
        $article = $this->articleDAO->find($articleId);

        // art_id is not selected by the SQL query
        // the article will not be retrieved during domain object construction
        $sql = "SELECT com_id, com_content, usr_id FROM t_comment WHERE art_id=? ORDER BY com_id";
        $result = $this->getDb()->fetchAll($sql, [$articleId]);

        // convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            //the associated article is defined for the constructed comment each time
            $comment->setArticle($article);
            $comments[$comId] = $comment;
        }
        return $comments;
    }

    /**
     * Creates a Comment object based on a DB row
     *
     * @param array $row The DB row containing the
     * relevant Comment data
     *
     * @return \silex_openC\src\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);

        if (array_key_exists('art_id', $row)) {
            // find and set the associated article
            $articleId = $row['art_id'];
            $article = $this->articleDAO->find($articleId);
            $comment->setArticle($article);
            /**
             * on ne construit l'article associé au
             * commentaire que si le champ art_id
             * est présent dans la ligne de
             * résultat SQL.
             * Cela permet  de ne construire
             * l'objet Article qu'une seule fois
             * dans la méthode findAllByArticles,
             * ce qui limite le nombre de requêtes
             * SQL et améliore donc les
             * performances de l'application.
             */
        }
        if (array_key_exists('usr_id', $row)) {
            // find and set the associated user
            $userId = $row['usr_id'];
            $user = $this->userDAO->find($userId);
            $comment->setAuthor($user);
        }
        return $comment;
    }

    /**
     * Afin de pouvoir construire complètement une
     * instance de la classe Comment, la classe
     * CommentDAO doit pouvoir récupérer un article
     * à partir de son identifiant et construire
     * une instance de la classe Article.
     * Plutôt que d'ajouter cela dans le code
     * source de CommentDAO, on ajoute dans la
     * classe ArticleDAO une nouvelle méthode find
     * définissant le service requis.
     * La classe CommentDAO a besoin de ce service
     * pour fonctionner : on dit qu'il existe une
     * dépendance entre la classe CommentDAO et la
     * classe ArticleDAO.
     * Cette dépendance se traduit dans le code
     * source de CommentDAO par la présence d'une
     * propriété privée $articleDAO et d'un
     * accesseur en écriture (mutateur)
     * setArticleDAO.
     */
    // Cette méthode rassemble les valeurs BD dans le tableau$commentData, puis vérifie s'il faut insérer ou mettre à jour le commentaire en se basant sur l'existence d'une valeur pour l'identifiant du commentaire. Ensuite, elle utilise la méthode DBAL appropriée pour effectuer l'opération dans la tablet_comment.

    /**
     * Saves a comment into the database
     *
     * @param \silex_openC\src\Domain\Comment $comment The comment to save
     */
    public function save(Comment $comment) {
        $commentData = [
            'art_id' => $comment->getArticle()->getId(),
            'usr_id' => $comment->getAuthor()->getId(),
            'com_content' => $comment->getContent()
        ];

        if ($comment->getId()) {
            // The comment has already been saved => update it
            $this->getDb()->update('t_comment', $commentData, ['com_id' => $comment->getId()]);
        } else {
            // The comment has never been saved => insert it
            $this->getDb()->insert('t_comment', $commentData);
            // Get the id of the newly created comme,y and set it on the entity
            $id = $this->getDb()->lastInsertId();
            $comment->setId($id);
        }
    }

}
