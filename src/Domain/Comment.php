<?php

namespace silex_openC\src\Domain;

class Comment {

    /**
     * Comment id
     *
     * @var integer
     */
    private $id;

    /**
     * Comment author
     *
     * @var \silex_openc\src\Domain\User
     */
    private $author;

    /**
     * Comment content
     *
     * @var integer
     */
    private $content;

    /**
     * Associated article
     *
     * @var silex_openC\Domain\Article
     */
    private $article;

    public function getId() {
        return $this->id;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getContent() {
        return $this->content;
    }

    public function getArticle() {
        return $this->article;
    }

    /**
     * L'association avec un article se
     * traduit dans le code source par la
     * présence d'une propriété $article.
     * Il ne s'agit pas d'un simple
     * identifiant de type entier, mais
     * bien d'un objet de la classe
     * Article.
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setAuthor(User $author) {
        $this->author = $author;
        return $this;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    public function setArticle(Article $article) {
        $this->article = $article;
        return $this;
    }

}
