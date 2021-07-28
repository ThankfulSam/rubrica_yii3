<?php

declare(strict_types=1);
use Yiisoft\User\CurrentUser;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Data\Paginator\OffsetPaginator;
use Yiisoft\Yii\DataView\GridView;
use Yiisoft\Yii\DataView\Columns\DataColumn;
use Yiisoft\Yii\DataView\Columns\SerialColumn;
use App\Form\ContactForm;
use App\Form\SearchForm;
use Yiisoft\Session\SessionInterface;


/** @var App\ApplicationParameters $applicationParameters
 *  @var CurrentUser $user
 *  @var \Yiisoft\Router\UrlGeneratorInterface $url
 *  @var SessionInterface $session
 *  @var OffsetPaginator $paginator
 *  @var ContactForm $contact_form
 *  @var SearchForm $search_form
 *  
 */

$this->params['breadcrumbs'] = '/';

$this->setTitle($applicationParameters->getName());
?>

<h1 class="title">I miei contatti</h1>

<p class="subtitle">Utente <strong><?php echo $session->get('nome'); ?></strong>!</p>
    
<?php /* echo ListView::widget()
        ->cssFramework(ListView::BULMA)
        ->itemOptions(['tag' => 'table'])
        ->itemView(//'//_list_view_contact.php')
            static fn ($contact_form) => 
                '<tr >' .
                //'<button>' . 
                Html::a($contact_form['nome'].' '.$contact_form['cognome'], $url->generate('site/view', ['id' => $contact_form['id']])) .
                //'</button>' .
                '</tr>'
        )
        ->paginator($paginator);*/
?>


<?php echo GridView::widget()
        ->tableOptions(['align'=>'center'])
        ->pageSize(3)
        ->currentPage((isset($current_page)) ? $current_page : 1)
        ->columns([
            [
                'class' => SerialColumn::class, // this line is optional
            ],
            [
                //'class' => DataColumn::class,
                'label()' => ['Nome e cognome'],
                'value()' => [static function($contact) use ($url){
                    return Html::a($contact->getNome() .' '.$contact->getCognome(), $url->generate('site/view', ['id' => $contact->getId()]));
                }],
            ],
            [
                'label()' => ['Preferito'],
                'value()' => [static function($contact) {
                    return ($contact->getPreferito()) ? '*' : '';
                }]
            ],
        ])
        ->paginator($paginator);
?>

<br>

<?php 
    $pref = null;
    if(isset($_GET['pref'])){
        $pref = $_GET['pref'];
    }; 
    $ordinaPer = null;
    if(isset($_GET['per'])){
        $ordinaPer = $_GET['per'];
    };
?>
<button>
	<?php echo Html::a('Mostra solo preferiti', $url->generate('home', ['pref' => 1, 'per' => $ordinaPer])); ?>
</button>
<button>
	<?php echo Html::a('Mostra tutti', $url->generate('home', ['pref' => 0, 'per' => $ordinaPer])); ?>
</button>
<br>
<button>
	<?php echo Html::a('Ordina per nome', $url->generate('home', ['per' => 'nome', 'pref' => $pref])); ?>
</button>
<button>
	<?php echo Html::a('Ordina per cognome', $url->generate('home', ['per' => 'cognome', 'pref' => $pref])); ?>
</button>
<br>
<button>
	<?php echo Html::a('Inserisci nuovo contatto', $url->generate('site/insert')); ?>
</button>
<br>
<br>

<h2>Ricerca contatto!</h2>
<?= Form::widget()
    ->action($url->generate('site/search'))
    ->options([
        'csrf' => $csrf,
    ])
    ->begin() ?>

<?= Field::widget()->config($search_form, 'nome'); ?>
<?= Field::widget()->config($search_form, 'cognome'); ?>

<?= Html::submitButton('Cerca') ?>

<?= Form::end() ?>
