<?php

namespace App\Services;

use App\Models\User;

class UserService
{
  private User $user;

  public function __construct(User $user)
  {
    $this->user = $user;
  }
  public function getUserByEmail($email)
  {
    return $this->user->getUserByEmail($email);
  }

  public function getAllUsers()
  {
    return $this->user->all();
  }

  public function getUserById($id)
    {
        return $this->user->find($id);
    }

    public function createUser($data)
    {
        return $this->user->create($data);
    }

    public function updateUser($id, $data)
    {
        return $this->user->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->user->delete($id);
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
      return $this->user->paginate($page, $perPage);
    }
}