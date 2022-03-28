<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends ResourceController
{
    protected $user;
    public function __construct()
    {
        $this->user = new UsersModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'konfirpass' => 'matches[password]'
        ];
        if (!$this->validate($rules)) {
            $msg = [
                'status' => 'fail',
                'message' => $this->validator->getErrors()
            ];
            return $this->respond($msg, 400);
        }

        $data = [
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT)
        ];

        if ($this->user->save($data)) {
            $msg = [
                'status' => 'success',
                'message' => 'User berhasil ditambahkan'
            ];
            return $this->respond($msg, 201);
        }
    }

    // create regis
    public function login()
    {

        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            $msg = [
                'status' => 'fail',
                'message' => $this->validator->getErrors()
            ];
            return $this->respond($msg, 400);
        }

        // cek login 
        $cek = $this->user->getUserByEmail($this->request->getVar('email'));
        if ($cek) {
            $user = $cek[0];
            if (password_verify($this->request->getVar('password'), $user['password'])) {
                $key = getenv('TOKEN_KEY');
                $payload = array(
                    "iat" => 1356999524,
                    "nbf" => 1357000000,
                    'uid' => $user['id_user'],
                    'email' => $user['email']
                );
                $jwt = JWT::encode($payload, $key, 'HS256');
                $msg = [
                    'status' => 'success',
                    'message' => 'Berhasil Login',
                    'token' => $jwt
                ];
                return $this->respond($msg, 200);
            } else {
                $msg = [
                    'status' => 'fail',
                    'message' => 'Password yang dimasukan salah'
                ];
                return $this->respond($msg, 400);
            }
        } else {
            $msg = [
                'status' => 'fail',
                'message' => 'Email tidak terdaftar'
            ];
            return $this->respond($msg, 400);
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
