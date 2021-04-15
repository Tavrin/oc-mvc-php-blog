<?php


namespace App\Manager;


use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;

class AdminManager
{
    public function hydrateDashboard(): array
    {
        $content = [];
        $postRepository = new PostRepository();
        $userRepository = new UserRepository();
        $commentRepository = new CommentRepository();

        $content['posts']['items'] = $postRepository->findAll();
        $content['posts']['count'] = count($content['posts']['items']);

        $content['users']['items'] = $userRepository->findAll();
        $content['users']['count'] = count($content['users']['items']);

        $content['comments']['items'] = $commentRepository->findAll();
        $content['comments']['count'] = count($content['comments']['items']);

        return $content;
    }
}