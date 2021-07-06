<?php

declare(strict_types=1);

use HttpSoft\Message\RequestFactory;
use HttpSoft\Message\ResponseFactory;
use HttpSoft\Message\ServerRequestFactory;
use HttpSoft\Message\StreamFactory;
use HttpSoft\Message\UploadedFileFactory;
use HttpSoft\Message\UriFactory;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Yiisoft\Session\Session;
use Yiisoft\Session\SessionInterface;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Psr\Container\ContainerInterface;
use Cycle\ORM\ORMInterface;
use App\User\IdentityRepository;

return [
    RequestFactoryInterface::class => RequestFactory::class,
    ServerRequestFactoryInterface::class => ServerRequestFactory::class,
    ResponseFactoryInterface::class => ResponseFactory::class,
    StreamFactoryInterface::class => StreamFactory::class,
    UriFactoryInterface::class => UriFactory::class,
    UploadedFileFactoryInterface::class => UploadedFileFactory::class,
    
    /*SessionInterface::class =>[
        'class' => Session::class, 
        '__construct' => [
            $params['session']['options'] ?? [],
            $params['session']['handler'] ?? null,
        ],
    ],*/
    
    //User
    IdentityRepositoryInterface::class => IdentityRepository::class,
];
