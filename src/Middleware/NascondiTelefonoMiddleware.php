<?php
namespace App\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;


class NascondiTelefonoMiddleware implements MiddlewareInterface
{

    private ResponseFactoryInterface $responseFactory;
    
    public function __construct(ResponseFactoryInterface $responseFactory) {
        $this->responseFactory = $responseFactory;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        $response = $next->handle($request);
        
        $new_response =  $this->responseFactory
            ->createResponse();
        
        $code = $response->getBody()->__toString();
        
        $pattern = '/([0-9]{3})[0-9]{7}/';
        $replacement = '$1*******';
        $result = preg_replace($pattern, $replacement, $code);
        
        $new_response->getBody()->write($result);
        
        return $new_response;
    }
    
}

