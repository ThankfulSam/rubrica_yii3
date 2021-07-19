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
use App\Form\ContactForm;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Data\Reader\ReadableDataInterface;

class SiteController
{
    
    private ViewRenderer $viewRenderer;
    private CurrentUser $user;
    private DatabaseManager $dbal;
    private ReadableDataInterface $dri;
    
    public function __construct(ViewRenderer $viewRenderer, CurrentUser $user, 
        DatabaseManager $dbal, ReadableDataInterface $dri)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('site');
        $this->user = $user;
        $this->dbal = $dbal;
        $this->dri = $dri;
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
        if (isset($request->getQueryParams()['id'])){
            $id = $request->getQueryParams()['id'];
            $contatto = $this->dbal->database('default')->select()->from('contatticonpreferiti')->where('id', $id)->fetchAll();
            
            return $this->viewRenderer->render('view', [
                'contatto' => $contatto
            ]); 
        } else {
            return $this->viewRenderer->render('index');
        }
                    
    }
    
    /*METODO CHE PERMETTE IL SET O RESET COME PREFERITO DI UN CONTATTO*/
    
    public function actionSetPreferred(ServerRequestInterface $request): ResponseInterface 
    {
        
        //if($request->getMethod() === Method::POST && isset($request->getQueryParams()['id'])){
        if(isset($request->getQueryParams()['id'])){
            
            $id = $request->getQueryParams()['id'];
            
            $contatto = $this->dbal->database('default')->select()->from('contatticonpreferiti')->where('id', $id)->fetchAll();
            if ($contatto[0]['preferito']){
                $this->dbal->database('default')->table('contatticonpreferiti')
                ->update(['preferito' => '0'])
                ->where('id', $id)
                ->run();
            } else {
                $this->dbal->database('default')->table('contatticonpreferiti')
                ->update(['preferito' => '1'])
                ->where('id', $id)
                ->run();
            }
            $contatto = $this->dbal->database('default')->select()->from('contatticonpreferiti')->where('id', $id)->fetchAll();
            
            return $this->viewRenderer->render('view', [
                'contatto' => $contatto
            ]);
        } else {
            return $this->viewRenderer->render('index');    
        } 
        
    }
    
    /* METODO CHE PERMETTE LA MODIFICA DI UN CONTATTO*/
    
    public function actionUpdate(ServerRequestInterface $request)
    {
        $form = new ContactForm();
        
        if($request->getMethod() === Method::POST) {
            $form->load($request->getParsedBody());
            $this->dbal->database('default')->table('contatticonpreferiti')
            ->update(['nome' => $form->getNome(),
                'cognome' => $form->getCognome(),
                'telefono' => $form->getTelefono(),
                'indirizzo' => $form->getIndirizzo()
            ])
            ->where('id', $form->getId())
            ->run();
            
            $contatto = $this->dbal->database('default')->select()->from('contatticonpreferiti')->where('id', $form->getId())->fetchAll();
            return $this->viewRenderer->render('view', [
                'contatto' => $contatto
            ]);
        }
        
        $contatto = $this->dbal->database('default')->select()->from('contatticonpreferiti')->where('id', $request->getQueryParams()['id'])->fetchAll();
        $form->loadData(array_values($contatto[0]));
        return $this->viewRenderer->render('update', [
            'form' => $form
        ]);
        
    }
    
    /* METODO CHE PERMETTE L'INSERIMENTO DI UN NUOVO CONTATTO*/
    
    public function actionInsert(ServerRequestInterface $request)
    {
        $form = new ContactForm();
        
        if($request->getMethod() === Method::POST) {
            $form->load($request->getParsedBody());
            $this->dbal->database('default')->table('contatticonpreferiti')
            ->insert()->values([
                'id' => $form->getId(),
                'nome' => $form->getNome(),
                'cognome' => $form->getCognome(),
                'telefono' => $form->getTelefono(),
                'indirizzo' => $form->getIndirizzo(),
                'preferito' => 0,
                'user_id' => 5
            ])
            ->run();
            
            $tab_contatti = $this->dbal->database('default')->select()->from('contatticonpreferiti')->fetchAll();
            return $this->viewRenderer->render('index_prova', 
                [
                    'tab_contatti' => $tab_contatti
                ]);
        }
        
        $id = $this->dbal->database('default')->select()->from('contatticonpreferiti')->max('id');
        $form->setAttribute('id', $id+1);
        $form->setAttribute('nome', '');
        $form->setAttribute('cognome', '');
        $form->setAttribute('telefono', '');
        $form->setAttribute('indirizzo', '');
        return $this->viewRenderer->render('insert', [
            'form' => $form
        ]);
        
    }
    
    /* METODO CHE PERMETTE LA RIMOZIONE DI UN CONTATTO*/
    
    public function actionDelete(ServerRequestInterface $request)
    {
        if (isset($request->getQueryParams()['id'])){
            $id = $request->getQueryParams()['id'];
            $this->dbal->database('default')
                ->table('contatticonpreferiti')
                ->delete()
                ->where('id', $id)
                ->run();
        }
        
        $contatto = $this->dbal->database('default')->select()->from('contatticonpreferiti')->fetchAll();
        return $this->viewRenderer->render('index_prova', [
            'tab_contatti' => $contatto
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
            
        return $this->viewRenderer->render('login', ['form' => $form]);
        
    }
    
}

