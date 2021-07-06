<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ServerRequestInterface;
use \Yiisoft\User;
use App\Form\LoginForm;
use Yiisoft\Http\Method;
use Yiisoft\Validator\Validator;

class SiteController
{
    private ViewRenderer $viewRenderer;

    public function __construct(ViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('site');
    }

    public function index(): ResponseInterface
    {
        return $this->viewRenderer->render('index');
    }
    
    public function actionIndex(ServerRequestInterface $request, User $user) 
    {
        if ($user->isGuest())
            todo;
        else todo;
    }
    
    public function actionLogin(ServerRequestInterface $request, Validator $validator): ResponseInterface 
    {
        $form = new LoginForm();
        
        if($request->getMethod() === Method::POST) {
            $form->load($request->getParsedBody());
            $validator->validate($form);
        }
        return $this->viewRenderer->render('login', [
            'form' => $form
        ]);
    
    }
    
}
