<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ServerRequestInterface;
use \Yiisoft\User\CurrentUser;
use App\Form\LoginForm;
use Yiisoft\Http\Method;
use Yiisoft\Validator\Validator;
use App\User\IdentityRepository;

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
    
    public function actionIndex(ServerRequestInterface $request, CurrentUser $user) 
    {
        /*if ($user->isGuest())
            todo;
        else todo;*/
    }
    
    public function actionLogin(ServerRequestInterface $request, Validator $validator, 
        CurrentUser $user, IdentityRepository $identityRepository): ResponseInterface 
    {
        $form = new LoginForm();
        
        if($request->getMethod() === Method::POST) {
            $form->load($request->getParsedBody());
            $validator->validate($form);
            
            $identity = $identityRepository->findIdentity($form->getId());
            if ($identity != null){
                $user->login($identity);
            }
            
        }

        if($user->isGuest()){
            return $this->viewRenderer->render('login', [
                'form' => $form
            ]);
        } else {
            return $this->viewRenderer->render('index');
        }
            
    }
    
    public function actionLogout(CurrentUser $user) {
        $form = new LoginForm();
        
        if($user->logout()){
            return $this->viewRenderer->render('login', [
                'form' => $form
            ]);
        } 
    }
    
}
