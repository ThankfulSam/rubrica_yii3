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
    
}