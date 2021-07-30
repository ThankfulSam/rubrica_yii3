<?php

use Yiisoft\Html\Html;
use App\Entity\Contatto;
use Yiisoft\Session\SessionInterface;

/* @var \Yiisoft\User\CurrentUser $user */
/* @var \Yiisoft\Router\UrlGeneratorInterface $url */
/* @var Contatto $contatto */
/* @var SessionInterface $session */
?>


<?php $cont = current($contatto); ?>

<table align="center">
  <tr>
    <th>Nome:</th>
    <td><?php echo $cont->getNome() ?></td>
  </tr>
  <tr>
    <th>Cognome:</th>
    <td><?php echo $cont->getCognome() ?></td>
  </tr>
  <tr>
    <th>Telefono:</th>
    <td><?php echo $cont->getTelefono() ?></td>
  </tr>
  <tr>
    <th>Indirizzo:</th>
    <td><?php echo $cont->getIndirizzo() ?></td>
  </tr>
  <tr>
    <th>Preferito:</th>
    <td><?php echo ($cont->getPreferito()) ? 'SI' : 'NO' ?></td>
  </tr>
</table>

<br>
<br>
<?php 
    $str = 'imposta come preferito';
    if ($cont->getPreferito()){
        $str = 'rimuovi dai preferiti';
    }
    echo Html::a($str, $url->generate('site/setPreferred', ['id' => $cont->getId()]), ['class' => 'button']);
    echo '<br>';
    echo Html::a('modifica', $url->generate('site/update', ['id' => $cont->getId()]), ['class' => 'button']);
    echo Html::a('elimina', 
        $url->generate('site/delete', ['id' => $cont->getId()]), 
        [
            'onclick' => 'return confirm(\'Sei sicuro di voler eliminare questo contatto?\')', 
            'class'=>'button',
            'id'=>'elimina_button'
        ]);
    echo '<br>';
    echo Html::a('home', $url->generate('home'), ['class' => 'button', 'id'=>'green_button']);
?>

<style>

</style>