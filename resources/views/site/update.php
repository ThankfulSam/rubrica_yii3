<?php 
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;

?>

<h1 class="title">Modifica contatto</h1>
<?= Form::widget()
->action($url->generate('site/update'))
->options([
    'csrf' => $csrf,
])
->begin() ?>

<?= Field::widget()->config($form, 'id')->hiddenInput(); ?>
<?= Field::widget()->config($form, 'nome'); ?>
<?= Field::widget()->config($form, 'cognome'); ?>
<?= Field::widget()->config($form, 'telefono'); ?>
<?= Field::widget()->config($form, 'indirizzo'); ?>
<br>
<?= Html::submitButton('Salva modifiche') ?>

<?= Form::end() ?>
