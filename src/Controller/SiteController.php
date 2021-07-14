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
use Spiral\Database\DatabaseManager;
use PhpParser\Node\Expr\Isset_;

class SiteController
{
    
    private ViewRenderer $viewRenderer;
    private CurrentUser $user;
    private DatabaseManager $dbal;
    
    public function __construct(ViewRenderer $viewRenderer, CurrentUser $user, DatabaseManager $dbal)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('site');
        $this->user = $user;
        $this->dbal = $dbal;
    }
    
    public function index(): ResponseInterface
    {
        
        $form = new LoginForm();
        
        $tab_contatti = $this->dbal->database('default')->select()->from('contatticonpreferiti')->fetchAll();
        
        if (!$this->user->isGuest()){
            return $this->viewRenderer->render('index_prova', [
                'tab_contatti' => $tab_contatti
            ]);
        } else {
            return $this->viewRenderer->render('login', [
                'form' => $form
            ]);
        }
    }
    
    public function actionView(ServerRequestInterface $request): ResponseInterface 
    {
        $id = $request->getQueryParams()['id'];
        $contatto = $this->dbal->database('default')->select()->from('contatticonpreferiti')->where('id', $id)->fetchAll();
        
        return $this->viewRenderer->render('view', [
            'contatto' => $contatto
        ]);                
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
        
        if (!$this->user->isGuest()){
            $this->user->logout();
        }
            
        return $this->viewRenderer->render('login', ['form' => $form ]);
        
    }
    
}

