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

<h1>I miei contatti</h1>

<p class="subtitle">Utente <strong><?php echo $session->get('nome'); ?></strong>!</p>
    
<?php echo GridView::widget()
        ->tableOptions(['align'=>'center'])
        ->pageSize(10)
        ->currentPage((isset($current_page)) ? $current_page : 1)
        ->columns([
            [
                'class' => SerialColumn::class, // this line is optional
            ],
            [
                'label()' => ['Nome e cognome'],
                'value()' => [static function($contact) use ($url){
                    return Html::a($contact->getNome() .' '.$contact->getCognome(), $url->generate('site/view', ['id' => $contact->getId()]));
                }],
            ],
            'telefono',
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

<?php echo Html::a('Mostra solo preferiti', $url->generate('home', ['pref' => 1, 'per' => $ordinaPer]), ['class' => 'button', 'id' => 'blue_button']); ?>
<?php echo Html::a('Mostra tutti', $url->generate('home', ['pref' => 0, 'per' => $ordinaPer]), ['class' => 'button', 'id' => 'blue_button']); ?>
<br>
<?php echo Html::a('Ordina per nome', $url->generate('home', ['per' => 'nome', 'pref' => $pref]), ['class' => 'button', 'id' => 'skyBlue_button']); ?>
<?php echo Html::a('Ordina per cognome', $url->generate('home', ['per' => 'cognome', 'pref' => $pref]), ['class' => 'button', 'id' => 'skyBlue_button']); ?>
<br>
<?php echo Html::a('Inserisci nuovo contatto', $url->generate('site/insert'), ['class' => 'button', 'id' => 'green_button']); ?>
<br>
<br>

<h2>Ricerca contatto!</h2>
<?= Form::widget()
    ->action($url->generate('site/search'))
    ->options([
        'csrf' => $csrf,
    ])
    ->begin() 
?>

<?= Field::widget()->label(false)->config($search_form, 'nome'); ?>
<?= Field::widget()->label(false)->config($search_form, 'cognome'); ?>

<?= Html::submitButton('Cerca', ['class' => 'button']) ?>

<?= Form::end() ?>

