<?php

namespace App\User;

use Yiisoft\Auth\IdentityInterface;

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
}