<?php

namespace App\User;

use App\User\Identity;
use \Yiisoft\Auth\IdentityInterface;
use \Yiisoft\Auth\IdentityRepositoryInterface;
use Spiral\Database\DatabaseManager;
use Yiisoft\Security\PasswordHasher;
use function PHPUnit\Framework\isEmpty;

final class IdentityRepository implements IdentityRepositoryInterface
{
    
    public function findIdentity(string $id) : ?IdentityInterface
    {
        return new Identity($id);
    }
    
    public function accessCheck(string $username, string $password, DatabaseManager $dbal) : ?IdentityInterface
    {
        
        $pass = new PasswordHasher();
        $user = $dbal->database('default')->select()->from('users')->where('username', $username)->fetchAll();
        if(!empty($user)){
            $user = current($user);
            if ($password != '' && $pass->validate($password, $user['password'])){
                return new Identity((string)$user['id']);
            }
        }
        return null;
    }
    
    //private $users;
    
    /*public function __construct(DatabaseManager $dbal){
        $this->users = $dbal->database('default')->select()->from('users')->fetchAll();
    }*/
    
    /*public function __construct(UserRepository $user_repo){
        $this->users = $user_repo->all();
    }*/
    
    /*public function findIdentity(string $id) : ?IdentityInterface
    {
        foreach ($this->users as $user) {
            if ((string)$user['id'] === $id) {
                return new Identity($id);
            }
        }
        
        return null;
    }*/
    
    
    
    /*public function accessCheck(string $username, string $password) : ?IdentityInterface
    {
        
        $pass = new PasswordHasher();
        foreach ($this->users as $user) {
            if ((string)$user['username'] === $username && $pass->validate($password, (string)$user['password'])) {
                return new Identity((string)$user['id']);
            }
        }
        
        return null;
    }*/
    
}