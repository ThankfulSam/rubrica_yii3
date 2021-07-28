<?php

use Yiisoft\Html\Html;
use App\Entity\Contatto;
use Yiisoft\Session\SessionInterface;

/* @var \Yiisoft\User\CurrentUser $user */
/* @var \Yiisoft\Router\UrlGeneratorInterface $url */
/* @var Contatto $contatto */
/* @var SessionInterface $session */
?>

<h1><b><?= $session->get('nome') ?>, stai visualizzando un singolo contatto</b></h1>
<br>

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
    echo Html::a($str, $url->generate('site/setPreferred', ['id' => $cont->getId()]), ['data-method' => 'POST']);
    echo '<br>';
    echo Html::a('modifica', $url->generate('site/update', ['id' => $cont->getId()]));
    echo '<br>';
    echo Html::a('elimina', $url->generate('site/delete', ['id' => $cont->getId()]), ['onclick' => 'return confirm(\'Sei sicuro di voler eliminare questo contatto?\')']);
    echo '<br>';
    echo Html::a('home', $url->generate('home'), ['class' => 'button']);
?>

<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  background-color: #fffaf0
}
th, td {
  padding: 5px;
  text-align: left;
}
</style>
