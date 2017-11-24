<?php

namespace App\Http\Controllers\Api;

use App\Repositories\DiscussionRepository;
use App\Repositories\UserRepository;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;

class HomeController extends ApiController
{
    protected $user;
    protected $discussion;
    protected $article;
    protected $comment;

    public function __construct(UserRepository $user, ArticleRepository $article, CommentRepository $comment, DiscussionRepository $discussion){
        parent::__construct();

        $this->user = $user;
        $this->article = $article;
        $this->comment = $comment;
        $this->discussion = $discussion;
    }

    public function statistics()
    {
        $users = $this->user->getNumber();
        $discussions = $this->discussion->getNumber();
        $articles = $this->article->getNumber();
        $comments = $this->comment->getNumber();

        $data = compact('users', 'discussions', 'articles', 'comments');

        return $this->response->json($data);
    }

}
