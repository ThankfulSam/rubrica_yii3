<?php

use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;

/* @var string $csrf */
/* @var \Yiisoft\Router\UrlGeneratorInterface $url */

?>


<?php if (!empty($error)): ?>
    <div class="notification is-danger">
        <?= Html::encode($error) ?>
    </div>
<?php endif ?>

<h1 class="title">Sign up!</h1>
<?= Form::widget()
    ->action($url->generate('site/signup'))
    ->options([
        'csrf' => $csrf,
    ])
    ->begin() ?>

<?= Field::widget()->config($signup_form, 'username'); ?>
<?= Field::widget()->config($signup_form, 'password')->passwordInput(); ?>
<?= Field::widget()->config($signup_form, 'repeatPassword')->passwordInput(); ?>
<br>
<?= Html::submitButton('Sign up') ?>

<?= Form::end() ?>
