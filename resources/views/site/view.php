<?php

use Yiisoft\Html\Html;
use App\Entity\Contatto;

/* @var \Yiisoft\User\CurrentUser $user */
/*  @var \Yiisoft\Router\UrlGeneratorInterface $url */
/* @var Contatto $contatto*/
?>

<h1><b><?= $user->getId() ?>, stai visualizzando un singolo contatto</b></h1>
<br>

<?php 
    $cont = current($contatto);
    echo $cont->getNome() . '<br>';
    echo $cont->getCognome() . '<br>';
    echo $cont->getTelefono() . '<br>';
    echo $cont->getIndirizzo() . '<br>';
    echo 'preferito: '. $cont->getPreferito() . '<br>';
?>
<br>
<br>
<?php 
    $str = 'set as preferred';
    if ($cont->getPreferito()){
        $str = 'set as not preferred';
    }
    echo Html::a($str, $url->generate('site/setPreferred', ['id' => $cont->getId()]));//, ['method' => 'POST']);
    echo '<br>';
    echo Html::a('update', $url->generate('site/update', ['id' => $cont->getId()]));
    echo '<br>';
    echo Html::a('delete', $url->generate('site/delete', ['id' => $cont->getId()]), ['onclick' => 'return confirm(\'Sei sicuro di voler eliminare questo contatto?\')']);
    echo '<br>';
    echo Html::a('home', $url->generate('home'), ['class' => 'button']);
?>
