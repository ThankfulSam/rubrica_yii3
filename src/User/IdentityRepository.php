<?php

namespace App\User;

use App\User\Identity;
use \Yiisoft\Auth\IdentityInterface;
use \Yiisoft\Auth\IdentityRepositoryInterface;
use Spiral\Database\DatabaseManager;
use Yiisoft\Security\PasswordHasher;

final class IdentityRepository implements IdentityRepositoryInterface
{
    /*private const USERS = [
        [
            'id' => 'samu',
            'password' => 'samuelemillucci'
        ],
        [
            'id' => 42,
            'password' => '54321'
        ],
    ];*/
    
    private $users;
    
    public function __construct(DatabaseManager $dbal){
        $this->users = $dbal->database('default')->select()->from('users')->fetchAll();
    }
    
    public function findIdentity(string $id) : ?IdentityInterface
    {
        foreach ($this->users as $user) {
            if ((string)$user['id'] === $id) {
                return new Identity($id);
            }
        }
        
        return null;
    }
    
    public function accessCheck(string $username, string $password, DatabaseManager $dbal) : ?IdentityInterface
    {
        
        $pass = new PasswordHasher();
        foreach ($this->users as $user) {
            if ((string)$user['username'] === $username && $pass->validate($password, (string)$user['password'])) {
                return new Identity((string)$user['id']);
            }
        }
        
        return null;
    }    
    
}