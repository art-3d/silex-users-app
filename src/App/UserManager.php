<?php
namespace App;

use Silex\Application as SilexApplication;
use App\Models\User;

class UserManager
{
    protected $app;
    protected $userTableName = 'user';

    public function __construct(SilexApplication $app)
    {
        $this->app = $app;
        $this->conn = $app['db'];
    }

    public function createUser($data)
    {
        $user = new User();

        $password = '';
        if (trim($data['password']) !== '') {
            $password = $this->app['security.default_encoder']
                ->encodePassword($data['password'], $user->getSalt());
        }

        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setNickname($data['nickname']);
        $user->setAge($data['age']);
        $user->setPassword($password);

        return $user;
    }

    public function insert(User $user)
    {
        $sql = 'INSERT INTO ' . $this->userTableName .
            ' (firstname, lastname, nickname, age, password, salt, roles, created_at)' .
            ' VALUES (:firstname, :lastname, :nickname, :age, :password, :salt, :roles, :created_at)';

        $params = array(
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'nickname' => $user->getNickname(),
            'age' => $user->getAge(),
            'password' => $user->getPassword(),
            'salt' => $user->getSalt(),
            'roles' => implode(',', $user->getRoles()),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s')
        );

        $this->conn->executeUpdate($sql, $params);
    }

    public function findUsers($condition = array())
    {
        $users = $this->getUsers($condition);

        $users = array_map(function ($user) {
            return array(
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'nickname' => $user['nickname'],
                'age' => $user['age'],
                'created_at' => $user['created_at']
            );
        }, $users);

        return $users;
    }

    public function getSecureUsers()
    {
        $users = $this->getUsers();

        $result = array();
        foreach ($users as $user) {
            $result[$user['nickname']] = array(
                $user['roles'],
                $user['password'],
                $user['salt']
            );
        }

        return $result;
    }

    private function getUsers($where = array())
    {
        $sql = 'SELECT * FROM ' . $this->userTableName;
        $values = array();
        $keys = array();
        if (!empty($where)) {
            foreach ($where as $key => $val) {
                $keys[] = "$key LIKE ? ";
                $values[] = "%$val%";
            }
            $sql .= ' WHERE ' . implode('AND ', $keys);
        }

        try {
            $users = $this->conn->fetchAll($sql, $values);
        } catch (\Exception $e) {
            return [];
        }

        return $users;
    }
}
