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
use Doctrine\Common\Annotations\AnnotationRegistry;
use Cycle\Schema\Compiler;
use Cycle\Schema\Registry;
use App\Entity\User;
use Cycle\Annotated\Embeddings;
use Cycle\Annotated\Entities;
use App\Entity\Contatto;

class SiteController
{
    
    private ViewRenderer $viewRenderer;
    private CurrentUser $user;
    private DatabaseManager $dbal;
    
    public function __construct(ViewRenderer $viewRenderer, CurrentUser $user, 
        DatabaseManager $dbal)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('site');
        $this->user = $user;
        $this->dbal = $dbal;
    }
    
    public function index(): ResponseInterface
    {
        
        $form = new LoginForm();
        $contact_form = new ContactForm();
        $search_form = new SearchForm();
        $paginator = new OffsetPaginator(new MyDataReader($this->dbal, $this->user));
        
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
            $contatto = $this->dbal->database('default')->select()->from('contatticonpreferitiyii3')->where('id', $id)->fetchAll();
            
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
            
            $contatto = $this->dbal->database('default')->select()->from('contatticonpreferitiyii3')->where('id', $id)->fetchAll();
            if ($contatto[0]['preferito']){
                $this->dbal->database('default')->table('contatticonpreferitiyii3')
                ->update(['preferito' => '0'])
                ->where('id', $id)
                ->run();
            } else {
                $this->dbal->database('default')->table('contatticonpreferitiyii3')
                ->update(['preferito' => '1'])
                ->where('id', $id)
                ->run();
            }
            $contatto = $this->dbal->database('default')->select()->from('contatticonpreferitiyii3')->where('id', $id)->fetchAll();
            
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
            $this->dbal->database('default')->table('contatticonpreferitiyii3')
            ->update(['nome' => $form->getNome(),
                'cognome' => $form->getCognome(),
                'telefono' => $form->getTelefono(),
                'indirizzo' => $form->getIndirizzo()
            ])
            ->where('id', $form->getId())
            ->run();
            
            $contatto = $this->dbal->database('default')->select()->from('contatticonpreferitiyii3')->where('id', $form->getId())->fetchAll();
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
        
        $paginator = new OffsetPaginator(new MyDataReader($this->dbal, $this->user));
        
        if($request->getMethod() === Method::POST) {
            $form->load($request->getParsedBody());
            $this->dbal->database('default')->table('contatticonpreferitiyii3')
            ->insert()->values([
                'id' => $form->getId(),
                'nome' => $form->getNome(),
                'cognome' => $form->getCognome(),
                'telefono' => $form->getTelefono(),
                'indirizzo' => $form->getIndirizzo(),
                'preferito' => 0,
                'user_id' => (int)$this->user->getId(),
            ])
            ->run();
            
            $tab_contatti = $this->dbal->database('default')->select()->from('contatticonpreferitiyii3')->fetchAll();
            return $this->viewRenderer->render('index_prova', 
                [
                    'paginator' => $paginator,
                    'contact_form' => $contact_form,
                    'search_form' => $search_form
                ]);
        }
        
        $id = $this->dbal->database('default')->select()->from('contatticonpreferitiyii3')->max('id');
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
        $paginator = new OffsetPaginator(new MyDataReader($this->dbal, $this->user));
        
        if (isset($request->getQueryParams()['id'])){
            $id = $request->getQueryParams()['id'];
            $this->dbal->database('default')
                ->table('contatticonpreferitiyii3')
                ->delete()
                ->where('id', $id)
                ->run();
        }
        
        $contatto = $this->dbal->database('default')->select()->from('contatticonpreferitiyii3')->fetchAll();
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
        print_r($contatto);
        if(empty($contatto)){
            return false;
        }
        
        return true;
    }
    
    /*METODO CHE PERMETTE LA RICERCA DI UN CONTATTO A PARTIRE DA NOME O COGNOME*/
    public function actionSearch(ServerRequestInterface $request): ResponseInterface 
    {
        /*$search_form = new SearchForm();
        $dataReader = new MyDataReader($this->dbal, $this->user);
        
        if($request->getMethod() === Method::POST){
            $search_form->load($request->getParsedBody());
            
            // NOME E COGNOME ENTRAMBI SETTATI
            if(!empty($search_form->getNome()) && !empty($search_form->getCognome())){
                $contatto = $this->dbal->database('default')
                    ->select()
                    ->from('contatticonpreferitiyii3')
                    ->where('nome', $search_form->getNome())
                    ->andWhere('cognome', $search_form->getCognome())
                    ->limit(1)
                    ->fetchAll();
                return $this->viewRenderer->render('view', [
                    'contatto' => $contatto    
                ]);
            }
            elseif(!empty($search_form->getNome()) && empty($search_form->getCognome())){
                $nome = $search_form->getNome();
                $result = $dataReader->readAndFilterWhere('nome', $search_form->getNome());
                $a = new OffsetPaginator($dataReader);
            }
            
            
        };*/
    }
    
    public function actionProva() {
        
        $cl = (new Tokenizer\Tokenizer(new Tokenizer\Config\TokenizerConfig([
            'directories' => ['src/Entity'],
        ])))->classLocator();
        //AnnotationRegistry::registerLoader('class_exists');
        
        $schema = (new Compiler())->compile(new Registry($this->dbal), [
            //new Embeddings($cl),  // register embeddable entities
            new Entities($cl),    // register annotated entities
        ]);
        //$orm = $orm->withSchema(new ORM\Schema($schema));
        $orm = new ORM\ORM(new ORM\Factory($this->dbal), new ORM\Schema($schema));
        
        /*$u = new User();
        $u->setUsername("Hello World");
        
        $t = new ORM\Transaction($orm);
        $t->persist($u);
        $t->run();*/
        
        $source = $orm->getSource(User::class);
        $db = $source->getDatabase();
        
        $result = $db->query('SELECT * FROM users');
        //print_r($result->fetchAll());
        
        $select = $orm->getRepository(User::class)->select();
        //print_r($select->where('username', 'samu')->fetchAll());
        
        $select2 = $orm->getRepository(Contatto::class)->select();
        print_r($select2->fetchAll());
        
    }
}
