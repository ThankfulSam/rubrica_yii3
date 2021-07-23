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
use App\Form\ContactForm;
use Yiisoft\Data\Paginator\OffsetPaginator;
use App\Reader\MyDataReader;
use App\Form\SignupForm;
use function PHPUnit\Framework\equalTo;
use Yiisoft\Security\PasswordHasher;
use App\Form\SearchForm;
use Cycle\ORM;
use Spiral\Tokenizer;
use Cycle\Schema\Compiler;
use Cycle\Schema\Registry;
use App\Entity\User;
use Cycle\Annotated\Entities;
use App\Entity\Contatto;
use App\Repository\ContattoRepository;
use Yiisoft\Data\Reader\Filter\Equals;
use Cycle\ORM\Transaction;
use Yiisoft\Data\Reader\Filter\Like;
use Yiisoft\Data\Reader\Sort;

class SiteController
{
    
    private ViewRenderer $viewRenderer;
    private CurrentUser $user;
    private DatabaseManager $dbal;
    private ContattoRepository $contact_repo;
    
    public function __construct(ViewRenderer $viewRenderer, CurrentUser $user, 
        DatabaseManager $dbal)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('site');
        $this->user = $user;
        $this->dbal = $dbal;
        $this->contact_repo = new ContattoRepository($this->returnORM()->getRepository(Contatto::class)->select(), $this->user);
    }
    
    public function index(): ResponseInterface
    {
        
        $form = new LoginForm();
        $contact_form = new ContactForm();
        $search_form = new SearchForm();
        
        //$paginator = new OffsetPaginator(new MyDataReader($this->dbal, $this->user));
        $paginator = new OffsetPaginator(
            $this->contact_repo->all()
        );
        
        if (!$this->user->isGuest()){
            return $this->viewRenderer->render('index_prova', [
                'paginator' => $paginator,
                'contact_form' => $contact_form,
                'search_form' => $search_form
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
            $contatto = $this->contact_repo->all()
                ->withFilter(new Equals('id', $id))
                ->read();
            
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
        
        if(isset($request->getQueryParams()['id'])){
            
            $id = $request->getQueryParams()['id'];
            
            $orm = $this->returnORM();
            $contact_to_change = $orm
                ->getRepository(Contatto::class)
                ->findByPK($id);
            
            $contact_to_change->setPreferito(null);
            
            (new Transaction($orm))->persist($contact_to_change)->run();
            
            $contatto = $this->contact_repo->all()->withFilter(new Equals('id', $id))->read();
            
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
            
            $orm = $this->returnORM();
            $contact_to_change = $orm->getRepository(Contatto::class)->findByPK($form->getId());
            $contact_to_change->updateAll($form->getNome(), $form->getCognome(), $form->getTelefono(), $form->getIndirizzo());
            
            (new Transaction($orm))->persist($contact_to_change)->run();
            
            $contatto = $this->contact_repo->all()->withFilter(new Equals('id', $form->getId()))->read();
            
            return $this->viewRenderer->render('view', [
                'contatto' => $contatto
            ]);
        }
        
        $contatto = $this->dbal->database('default')->select()->from('contatticonpreferitiyii3')->where('id', $request->getQueryParams()['id'])->fetchAll();
        $form->loadData(array_values($contatto[0]));
        return $this->viewRenderer->render('update', [
            'form' => $form
        ]);
        
    }
    
    /* METODO CHE PERMETTE L'INSERIMENTO DI UN NUOVO CONTATTO*/
    
    public function actionInsert(ServerRequestInterface $request)
    {
        $form = new ContactForm();
        $contact_form = new ContactForm();
        $search_form = new SearchForm();
        
        $paginator = new OffsetPaginator(
            $this->contact_repo->all()
        );
        
        if($request->getMethod() === Method::POST) {
            $form->load($request->getParsedBody());
            
            $contact = new Contatto();
            $contact->setId($form->getId());
            $contact->updateAll($form->getNome(), $form->getCognome(), $form->getTelefono(), $form->getIndirizzo());
            $contact->setPreferito(0);
            $contact->setUserId($this->user->getId());
            
            (new Transaction($this->returnORM()))->persist($contact)->run();
            
            return $this->viewRenderer->render('index_prova', 
                [
                    'paginator' => $paginator,
                    'contact_form' => $contact_form,
                    'search_form' => $search_form
                ]);
        }
        
        $id = $this->contact_repo->select()->max('id');

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
        $contact_form = new ContactForm();
        $search_form = new SearchForm();
        $paginator = new OffsetPaginator(
            $this->contact_repo->all()
        );
        
        if (isset($request->getQueryParams()['id'])){
            $id = $request->getQueryParams()['id'];
            
            $orm = $this->returnORM();
            $contact_to_delete = $orm->getRepository(Contatto::class)->findByPK($id);
            (new Transaction($orm))->delete($contact_to_delete)->run();
            
        }
        
        return $this->viewRenderer->render('index_prova', [
            'paginator' => $paginator,
            'contact_form' => $contact_form,
            'search_form' => $search_form
        ]);
    }
    
    public function actionPreferred(): ResponseInterface
    {
        $contact_form = new ContactForm();
        $search_form = new SearchForm();
        $paginator = new OffsetPaginator(
            $this->contact_repo->findPreferiti()
        );
        
        return $this->viewRenderer->render('index_prova', [
            'paginator' => $paginator,
            'contact_form' => $contact_form,
            'search_form' => $search_form
        ]);
        
    }
    
    public function actionOrdinaPer(ServerRequestInterface $request) : ResponseInterface
    {
        $contact_form = new ContactForm();
        $search_form = new SearchForm();
        $paginator = new OffsetPaginator(
            $this->contact_repo->all()
                ->withSort(Sort::any()->withOrder([$request->getQueryParams()['per'] => 'asc']))
            );
        
        return $this->viewRenderer->render('index_prova', [
            'paginator' => $paginator,
            'contact_form' => $contact_form,
            'search_form' => $search_form
        ]);
        
    }
    
    public function actionLogin(ServerRequestInterface $request, Validator $validator,
        IdentityRepository $identityRepository): ResponseInterface
        {
            $form = new LoginForm();
            
            if($request->getMethod() === Method::POST) {
                $form->load($request->getParsedBody());
                $validator->validate($form);
                
                $identity = $identityRepository->accessCheck($form->getUsername(), $form->getPassword(), $this->dbal);
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
    
    
    /* METODO CHE PERMETTE LA REGISTRAZIONE DI UN NUOVO UTENTE*/
    public function actionSignup(ServerRequestInterface $request, Validator $validator) {
        $signup_form = new SignupForm();
        $form = new LoginForm();
        $pass = new PasswordHasher();
        
        if($request->getMethod() === Method::POST){
            $signup_form->load($request->getParsedBody());
            $validator->validate($signup_form);
            
            // PASSWORD NON COINCIDENTI
            if($signup_form->getPassword() != $signup_form->getRepeatPassword()){
                return $this->viewRenderer->render('signup', [
                    'error' => 'Le password inserite devono coincidere',
                    'signup_form' => $signup_form
                ]);
            } 
            // USERNAME GIA' ESISTENTE
            elseif ($this->userAlreadyExist($signup_form->getUsername())) {
                return $this->viewRenderer->render('signup', [
                    'error' => 'Username gia\' esistente',
                    'signup_form' => $signup_form
                ]);
            } 
            // REGISTRAZIONE DEL NUOVO UTENTE
            else {
                $this->dbal->database('default')->insert('users')
                ->values([
                    'username' => $signup_form->getUsername(),
                    'password' => $pass->hash($signup_form->getPassword()),
                ])
                ->run();
                
                return $this->viewRenderer->render('login', [
                    'error' => 'Registrazione avvenuta con successo!',
                    'form' => $form
                ]);
            }
        }
        
        return $this->viewRenderer->render('signup', [
            'signup_form' => $signup_form
        ]);
    }
    
    /* METODO CHE VERIFICA LA NON ESISTENZA DI UN UTENTE CON IL NOME SPECIFICATO NEL SIGNUP*/
    private function userAlreadyExist(string $username): bool 
    {
        $contatto = $this->dbal->database('default')->select()->from('users')->where('username', $username)->fetchAll();
        
        if(empty($contatto)){
            return false;
        }
        
        return true;
    }
    
    /*METODO CHE PERMETTE LA RICERCA DI UN CONTATTO A PARTIRE DA NOME O COGNOME*/
    public function actionSearch(ServerRequestInterface $request): ResponseInterface 
    {
        $search_form = new SearchForm();
        $contact_form = new ContactForm();
                
        if($request->getMethod() === Method::POST){
            $search_form->load($request->getParsedBody());
            
            $paginator = new OffsetPaginator(
                $this->contact_repo->search($search_form->getNome(), $search_form->getCognome())
            );
            
            return $this->viewRenderer->render('index_prova', [
                'paginator' => $paginator,
                'contact_form' => $contact_form,
                'search_form' => $search_form
            ]);
        };
        
    }
    
    public function returnORM(){
        $cl = (new Tokenizer\Tokenizer(new Tokenizer\Config\TokenizerConfig([
            'directories' => ['src/Entity'],
        ])))->classLocator();
        
        $schema = (new Compiler())->compile(new Registry($this->dbal), [
            new Entities($cl),    // register annotated entities
        ]);
        
        $orm = new ORM\ORM(new ORM\Factory($this->dbal), new ORM\Schema($schema));
        
        return $orm;
    }
    
}
