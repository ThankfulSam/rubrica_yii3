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
use Infection\Mutator\ReturnValue\This;
use Yiisoft\User\Event\AfterLogin;


class SiteController
{
    private ViewRenderer $viewRenderer;
    private CurrentUser $user;
    
    public function __construct(ViewRenderer $viewRenderer, CurrentUser $user)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('site');
        $this->user = $user;
    }
    
    public function index(): ResponseInterface
    {
        $form = new LoginForm();
        if (!$this->user->isGuest()){
            return $this->viewRenderer->render('index');
        } else {
            return $this->viewRenderer->render('login', [
                'form' => $form
            ]);
        }
    }
    
    public function actionLogin(ServerRequestInterface $request, Validator $validator,
        IdentityRepository $identityRepository): ResponseInterface
        {
            $form = new LoginForm();
            
            if($request->getMethod() === Method::POST) {
                $form->load($request->getParsedBody());
                $validator->validate($form);
                
                $identity = $identityRepository->findIdentity($form->getId());
                if ($identity != null){
                    $this->user->login($identity);
                }
                
            }
            
            if($this->user->isGuest()){
                return $this->viewRenderer->render('login', [
                    'form' => $form
                ]);
            } else {
                return $this->viewRenderer->render('index');
            }
            
    }
    
    public function actionLogout() {
        $form = new LoginForm();
        
        if($this->user->logout()){
            return $this->viewRenderer->render('login', [
                'form' => $form
            ]);
        }
    }
    
}

