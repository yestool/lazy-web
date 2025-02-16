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
}