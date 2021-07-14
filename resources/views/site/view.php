<?php




/* @var \Yiisoft\User\CurrentUser $user */
?>

<h1><b><?= $user->getId()?>, stai visualizzando un singolo contatto</b></h1>
<br>
<?php echo $contatto[0]['nome'] . '<br>';
echo $contatto[0]['cognome'] . '<br>';
echo $contatto[0]['telefono'] . '<br>';
echo $contatto[0]['indirizzo'] . '<br>';
echo 'preferito: '. $contatto[0]['preferito'] . '<br>';?>


