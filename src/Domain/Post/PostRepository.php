<?php


namespace App\Domain\Post;


interface PostRepository
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Post|null
     */
    public function findPostById(int $id): ?Post;
}