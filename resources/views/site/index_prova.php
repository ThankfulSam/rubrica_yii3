<?php

declare(strict_types=1);
use Yiisoft\User\CurrentUser;
use Yiisoft\Html\Html;
use Yiisoft\Html\Tag\Button;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Yii\DataView\ListView;
use Yiisoft\Data\Paginator\OffsetPaginator;

/** @var App\ApplicationParameters $applicationParameters
 *  @var CurrentUser $user
 *  @var \Yiisoft\Router\UrlGeneratorInterface $url
 *  @var OffsetPaginator $paginator
 */

$this->params['breadcrumbs'] = '/';

$this->setTitle($applicationParameters->getName());
?>

<h1 class="title">I miei contatti</h1>

<p class="subtitle">Utente <strong><?php echo $user->getId(); ?></strong>!</p>
    
<?= ListView::widget()
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
        ->paginator($paginator);
?>

<br>
<button><?php echo Html::a('Inserisci nuovo contatto', $url->generate('site/insert')); ?></button>


