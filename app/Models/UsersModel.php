<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['email', 'password'];

    public function getUserAll()
    {
        return $this->table($this->table)->findAll();
    }
    public function getUserById($id)
    {
        return $this->table($this->table)->where('id_user', $id)->find();
    }
    public function getUserByEmail($email)
    {
        return $this->table($this->table)->where('email', $email)->find();
    }
}
