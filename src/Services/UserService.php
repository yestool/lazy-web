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

  public function getAllUsers()
  {
    return $this->user->getAll();
  }

  // 其他CRUD方法类似实现
}