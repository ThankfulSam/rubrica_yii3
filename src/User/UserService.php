<?php
namespace App\User;

use Yiisoft\Access\AccessCheckerInterface;

class UserService
{
    private AccessCheckerInterface $accessChecker;
    
    public function __construct(AccessCheckerInterface $accessChecker){
        $this->accessChecker = $accessChecker;    
    }
    
    public function can(string $permissionName, array $parameters = []): bool
    {
        //return $this->accessChecker->userHasPermission($this-, $permissionName);
    }
    
}

