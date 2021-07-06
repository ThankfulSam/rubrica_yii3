<?php

use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;

/* @var \App\Form\LoginForm $form */
/* @var string $csrf */
/* @var \Yiisoft\Router\UrlGeneratorInterface $url */
?>


<?php if (!empty($form->getUsername())): ?>
    <div class="notification is-success">
        Username is: <?= Html::encode($form->getUsername() . $form->getPassword()) ?>
    </div>
<?php endif ?>

<?= Form::widget()
    ->action($url->generate('site/login'))
    ->options([
        'csrf' => $csrf,
    ])
    ->begin() ?>

<?= Field::widget()->config($form, 'username') ?>
<?= Field::widget()->config($form, 'password')->passwordInput() ?>

<?= Html::submitButton('Login') ?>

<?= Form::end() ?>
