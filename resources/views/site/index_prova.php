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
use App\Entity\Contatto;
use Yiisoft\Yii\DataView\Columns\ActionColumn;


/** @var App\ApplicationParameters $applicationParameters
 *  @var CurrentUser $user
 *  @var \Yiisoft\Router\UrlGeneratorInterface $url
 *  @var OffsetPaginator $paginator
 *  @var ContactForm $contact_form
 *  @var SearchForm $search_form
 */

$this->params['breadcrumbs'] = '/';

$this->setTitle($applicationParameters->getName());
?>

<h1 class="title">I miei contatti</h1>

<p class="subtitle">Utente <strong><?php echo $user->getId(); ?></strong>!</p>
    
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

<?php /* echo GridView::widget()
        ->tableOptions(['align'=>'center'])
        ->columns([
            [
                'class' => SerialColumn::class, // this line is optional
            ],
            [
                'class' => DataColumn::class,
                'attribute()' => ['nome'],
                'label()' => ['Nome'],
                'value()' => [static function($contact_form) use ($url){
                    return Html::a($contact_form['nome'].' '.$contact_form['cognome'], $url->generate('site/view', ['id' => $contact_form['id']]));
                }],
            ],
            'preferito'
        ])
        ->paginator($paginator);*/
?>

<?php echo GridView::widget()
        ->tableOptions(['align'=>'center'])
        ->columns([
            [
                'class' => SerialColumn::class, // this line is optional
            ],
            [
                'class' => DataColumn::class,
                'attribute()' => ['nome'],
                'label()' => ['Nome e cognome'],
                'value()' => [static function($contact) use ($url){
                    return Html::a($contact->getNome() .' '.$contact->getCognome(), $url->generate('site/view', ['id' => $contact->getId()]));
                }],
            ],
            //'nome', 'cognome'
        ])
        ->paginator($paginator);
?>

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
