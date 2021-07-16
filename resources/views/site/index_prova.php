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
 */

$this->params['breadcrumbs'] = '/';

$this->setTitle($applicationParameters->getName());
?>

<h1 class="title">I miei contatti</h1>

<p class="subtitle">Utente <strong><?php echo $user->getId(); ?></strong>!</p>
    
    <?php 
        echo '<table class="center">';
        foreach ($tab_contatti as $tab){
            echo '<tr>';
            echo '<td>' . $tab['nome'] . '</td>';
            echo '<td>' . $tab['cognome'] . '</td>';
            echo '<td>' . Html::a('Visualizza!', $url->generate('site/view', ['id' => $tab['id']])) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    ?>
<?php /*ListView::widget([
    'paginator' => new OffsetPaginator($dataReader),
]);
      ListView::end();*/
?>