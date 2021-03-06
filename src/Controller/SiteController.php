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
use App\Form\SignupForm;
use function PHPUnit\Framework\equalTo;
use Yiisoft\Security\PasswordHasher;
use App\Form\SearchForm;
use Cycle\ORM;
use Spiral\Tokenizer;
use Cycle\Schema\Compiler;
use Cycle\Schema\Registry;
use Cycle\Annotated\Entities;
use App\Entity\Contatto;
use App\Repository\ContattoRepository;
use Yiisoft\Data\Reader\Filter\Equals;
use Cycle\ORM\Transaction;
use function PHPUnit\Framework\isEmpty;
use Yiisoft\Session\SessionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Router\UrlGeneratorInterface;

class SiteController
{
    
    private ViewRenderer $viewRenderer;
    private CurrentUser $user;
    private DatabaseManager $dbal;
    private ContattoRepository $contact_repo;
    private ResponseFactoryInterface $responseFactory;
    private UrlGeneratorInterface $urlGenerator;
    
    public function __construct(ViewRenderer $viewRenderer, CurrentUser $user, 
        DatabaseManager $dbal, ResponseFactoryInterface $responseFactory,
        UrlGeneratorInterface $urlGenerator)
    {
        $this->viewRenderer = $viewRenderer->withControllerName('site');
        $this->user = $user;
        $this->dbal = $dbal;
        $this->contact_repo = new ContattoRepository($this->returnORM()->getRepository(Contatto::class)->select(), $this->user);
        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
    }
    
    /* METODO PER PAGINA PRINCIPALE DELL'APPLICAZIONE CON LA VISUALIZZAZIONE DEI CONTATTI */
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        
        $contact_form = new ContactForm();
        $search_form = new SearchForm();
        
        //$paginator = new OffsetPaginator(new MyDataReader($this->dbal, $this->user));
        $per = (!empty($request->getQueryParams()['per'])) ? $request->getQueryParams()['per'] : null;
        $pref = (!empty($request->getQueryParams()['pref'])) ? $request->getQueryParams()['pref'] : null;
        $page = (!empty($request->getQueryParams()['page'])) ? intval($request->getQueryParams()['page']) : 1;

        $paginator = (new OffsetPaginator($this->contact_repo->allWithFilter($per, $pref)));
        
        return $this->viewRenderer->render('index_prova', [
            'paginator' => $paginator,
            'contact_form' => $contact_form,
            'search_form' => $search_form,
            'current_page' => $page
        ]);
        
    }
    
    /* METODO PER LA VIEW DI UN SINGOLO CONTATTO */
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
            return $this->backHome();
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
                        
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader(
                    'Location', 
                    $this->urlGenerator->generate('site/view', [
                        'id' => $id
                    ])
                 );
        } else {
            return $this->backHome();    
        }
        
    }
    
    /* METODO CHE PERMETTE LA MODIFICA DI UN CONTATTO*/
    public function actionUpdate(ServerRequestInterface $request)
    {

        $form = new ContactForm();
        $orm = $this->returnORM();
        
        if($request->getMethod() === Method::POST) {
            $form->load($request->getParsedBody());
            
            $contact_to_change = $orm->getRepository(Contatto::class)->findByPK($form->getId());
            $contact_to_change->updateAll($form->getNome(), $form->getCognome(), $form->getTelefono(), $form->getIndirizzo());
            
            (new Transaction($orm))->persist($contact_to_change)->run();
            
            $contatto = $this->contact_repo->all()->withFilter(new Equals('id', $form->getId()))->read();
            
            return $this->responseFactory
            ->createResponse(302)
            ->withHeader(
                'Location',
                $this->urlGenerator->generate('site/view', [
                    'id' => $form->getId()
                ])
            );
            
        }
        
        $contatto = $orm->getRepository(Contatto::class)->findByPK($request->getQueryParams()['id']);
        $form->loadData($contatto);
        return $this->viewRenderer->render('update', [
            'form' => $form
        ]);
            
    }
    
    /* METODO CHE PERMETTE L'INSERIMENTO DI UN NUOVO CONTATTO*/
    public function actionInsert(ServerRequestInterface $request)
    {
        
            $form = new ContactForm();
            $error = '';
            
            if($request->getMethod() === Method::POST) {
                
                $form->load($request->getParsedBody());
                
                if($form->getNome() != '' && $form->getCognome() != '' &&
                    $form->getTelefono() != ''){
                        
                        $contact = new Contatto();
                        $contact->setId($form->getId());
                        $contact->updateAll($form->getNome(), $form->getCognome(), $form->getTelefono(), $form->getIndirizzo());
                        $contact->setPreferito(0);
                        $contact->setUserId($this->user->getId());
                        
                        (new Transaction($this->returnORM()))->persist($contact)->run();
                        
                        return $this->responseFactory
                        ->createResponse(302)
                        ->withHeader(
                            'Location',
                            $this->urlGenerator->generate('home')
                            );
                } else {
                    $error = 'Nome, cognome e telefono sono campi obbligatori';
                }
            }
            
            $id = $this->contact_repo->select()->max('id');
            
            $form->setAttribute('id', $id+1);
            $form->setAttribute('nome', '');
            $form->setAttribute('cognome', '');
            $form->setAttribute('telefono', '');
            $form->setAttribute('indirizzo', '');
            return $this->viewRenderer->render('insert', [
                'form' => $form,
                'error' => $error
            ]);
            
    }
    
    /* METODO CHE PERMETTE LA RIMOZIONE DI UN CONTATTO*/
    public function actionDelete(ServerRequestInterface $request)
    {        
            
        if (isset($request->getQueryParams()['id'])){
            $id = $request->getQueryParams()['id'];
            
            $orm = $this->returnORM();
            $contact_to_delete = $orm->getRepository(Contatto::class)->findByPK($id);
            (new Transaction($orm))->delete($contact_to_delete)->run();
            
        }
        
        return $this->responseFactory
        ->createResponse(302)
        ->withHeader(
            'Location',
            $this->urlGenerator->generate('home')
            );
            
    }
    
    /* METODO CHE PERMETTE DI EFFETTUARE IL LOGIN */
    public function actionLogin(ServerRequestInterface $request, Validator $validator,
        IdentityRepository $identityRepository, SessionInterface $session): ResponseInterface
        {
            $form = new LoginForm();
            
            if($request->getMethod() === Method::POST) {
                $form->load($request->getParsedBody());
                $validator->validate($form);
                
                $identity = $identityRepository->accessCheck($form->getUsername(), $form->getPassword(), $this->dbal);

                if ($identity != null){
                    $this->user->login($identity);
                    $this->nome_utente = $form->getUsername();
                    $session->open();
                    $session->set('nome', $form->getUsername());
                }
                
            }
            
            if($this->user->isGuest()){
                return $this->viewRenderer->render('login', [
                    'form' => $form
                ]);
            } else {
                return $this->responseFactory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $this->urlGenerator->generate('home')
                    );
            }
            
    }
    
    /* METODO CHE PERMETTE DI EFFETTUARE IL LOGOUT */
    public function actionLogout(SessionInterface $session) 
    {
        $this->user->logout();
        $session->destroy();
        $session->close();
        
        return $this->responseFactory
        ->createResponse(302)
        ->withHeader(
            'Location',
            $this->urlGenerator->generate('site/login')
            );
        
    }
    
    /* METODO CHE PERMETTE LA REGISTRAZIONE DI UN NUOVO UTENTE*/
    public function actionSignup(ServerRequestInterface $request, Validator $validator) 
    {
        $signup_form = new SignupForm();
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
                
                return $this->responseFactory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $this->urlGenerator->generate('site/login')
                );
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
    
    /* METODO CHE RITORNA L'ORM COSTRUITO A PARTIRE DALLE ENTITA' SPECIFICATE */
    public function returnORM()
    {
        $cl = (new Tokenizer\Tokenizer(new Tokenizer\Config\TokenizerConfig([
            'directories' => ['src/Entity'],
        ])))->classLocator();
        
        $schema = (new Compiler())->compile(new Registry($this->dbal), [
            new Entities($cl),    // register annotated entities
        ]);
        
        $orm = new ORM\ORM(new ORM\Factory($this->dbal), new ORM\Schema($schema));
        
        return $orm;
    }
    
    /* METODO CHE FA IL REDIRECT A INDEX */
    public function backHome()
    {
        return $this->responseFactory
        ->createResponse(302)
        ->withHeader(
            'Location',
            $this->urlGenerator->generate('home')
            );
    }
    
}
