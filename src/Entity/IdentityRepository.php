<?php

namespace App\User;

use App\User\Identity;
use \Yiisoft\Auth\IdentityInterface;
use \Yiisoft\Auth\IdentityRepositoryInterface;

final class IdentityRepository implements IdentityRepositoryInterface
{
    private const USERS = [
        [
            'id' => 1,
            'token' => '12345'
        ],
        [
            'id' => 42,
            'token' => '54321'
        ],
    ];
    
    public function findIdentity(string $id) : ?IdentityInterface
    {
        foreach (self::USERS as $user) {
            if ((string)$user['id'] === $id) {
                return new Identity($id);
            }
        }
        
        return null;
    }
    
    public function findIdentityByToken(string $token, string $type) : ?IdentityInterface
    {
        foreach (self::USERS as $user) {
            if ($user['token'] === $token) {
                return new Identity((string)$user['id']);
            }
        }
        
        return null;
    }
}