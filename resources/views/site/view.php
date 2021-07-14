<?php




use Yiisoft\Html\Html;

/* @var \Yiisoft\User\CurrentUser $user */
/*  @var \Yiisoft\Router\UrlGeneratorInterface $url */
?>

<h1><b><?= $user->getId()?>, stai visualizzando un singolo contatto</b></h1>
<br>
<?php echo $contatto[0]['nome'] . '<br>';
echo $contatto[0]['cognome'] . '<br>';
echo $contatto[0]['telefono'] . '<br>';
echo $contatto[0]['indirizzo'] . '<br>';
echo 'preferito: '. $contatto[0]['preferito'] . '<br>';?>
<br>
<br>
<?php 
    $str = 'set as preferred';
    if ($contatto[0]['preferito']){
        $str = 'set as not preferred';
    }
    echo Html::a($str, $url->generate('site/setPreferred', ['id' => $contatto[0]['id']]));//, ['method' => 'POST']);
    echo '<br>';
    echo Html::a('update', $url->generate('site/update', ['id' => $contatto[0]['id']]));
    
?>


