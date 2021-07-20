<?php

namespace App\User;

use Yiisoft\Auth\IdentityInterface;
use Spiral\Database\DatabaseManager;

final class Identity implements IdentityInterface
{
    private string $id;
    
    public function __construct(string $id) {
        $this->id = $id;
    }
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getUsername(): string 
    {
        $dbal = new DatabaseManager();
        $user = $dbal->database('default')
            ->select('username')
            ->from('users')
            ->where(['id', $this->id]);
        return $user;
    }
}