<?php

use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;

/* @var \App\Form\LoginForm $form */
/* @var string $csrf */
/* @var \Yiisoft\Router\UrlGeneratorInterface $url */
/* @var \Yiisoft\User\CurrentUser $user */
?>

<?php if (!empty($form->getUsername())): ?>
    <div class="notification is-danger">
        <?= Html::encode('username e/o password errati') ?>
    </div>
<?php endif ?>

<?php if (!empty($error)): ?>
    <div class="notification is-success">
        <?= Html::encode($error) ?>
    </div>
<?php endif ?>

<h1 class="title">Login!</h1>
<?= Form::widget()
    ->action($url->generate('site/login'))
    ->options([
        'csrf' => $csrf,
    ])
    ->begin() ?>

<?= Field::widget()->config($form, 'username'); ?>
<?= Field::widget()->config($form, 'password')->passwordInput(); ?>

<?= Html::submitButton('Login') ?>

<?= Form::end() ?>
<br>
<?= 'Non sei ancora iscritto? Clicca ' . '<button>' . Html::a('qui', $url->generate('site/signup')) . '</button>'; ?>