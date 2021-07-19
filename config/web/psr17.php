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
use Yiisoft\Auth\IdentityRepositoryInterface;
use App\User\IdentityRepository;
use Yiisoft\Data\Reader\DataReaderInterface;
use App\Reader\MyDataReader;
use Yiisoft\Data\Reader\Filter\FilterInterface;
use src\Reader\MyFiltro;
use Yiisoft\Data\Reader\Filter\FilterProcessorInterface;
use src\Reader\MyFiltroProcessor;

return [
    RequestFactoryInterface::class => RequestFactory::class,
    ServerRequestFactoryInterface::class => ServerRequestFactory::class,
    ResponseFactoryInterface::class => ResponseFactory::class,
    StreamFactoryInterface::class => StreamFactory::class,
    UriFactoryInterface::class => UriFactory::class,
    UploadedFileFactoryInterface::class => UploadedFileFactory::class,
    
    //User
    IdentityRepositoryInterface::class => IdentityRepository::class,
    
    //DataReader
    DataReaderInterface::class => MyDataReader::class,
    FilterInterface::class => MyFiltro::class,
    FilterProcessorInterface::class => MyFiltroProcessor::class,
];
