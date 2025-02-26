<?php

namespace App\Services;

use App\Models\Post;

class PostService
{
    private Post $post;

    public function __construct(Post $post)
    {
      $this->post = $post;
    }


    public function getAllPosts()
    {
      return $this->post->all();
    }

    public function getPostById($id)
    {
        return $this->post->find($id);
    }

    public function createPost($data)
    {
        return $this->post->create($data);
    }

    public function updatePost($id, $data)
    {
        return $this->post->update($id, $data);
    }

    public function deletePost($id)
    {
        return $this->post->delete($id);
    }

    /**
     * 分页获取用户列表
     * 
     * @param int $page 当前页码
     * @param int $perPage 每页显示数量
     * @return array 包含用户数据和分页信息
     */
    public function paginate(int $page = 1, int $perPage = 10): array
    {
      return $this->post->paginate($page, $perPage);
    }
}