<?php

use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use \Yiisoft\User\CurrentUser;

/* @var \App\Form\LoginForm $form */
/* @var string $csrf */
/* @var \Yiisoft\Router\UrlGeneratorInterface $url */
/* @var \Yiisoft\User\CurrentUser $user */
?>


<?php if (!empty($form->getId())): ?>
    <div class="notification is-danger">
        <?= Html::encode('id errato') ?>
    </div>
<?php endif ?>

<?= Form::widget()
    ->action($url->generate('site/login'))
    ->options([
        'csrf' => $csrf,
    ])
    ->begin() ?>

<?= Field::widget()->config($form, 'id')->passwordInput(); ?>

<?= Html::submitButton('Login') ?>

<?= Form::end() ?>

<?php  
    //if (!$user->isGuest()): ?>
    
<?php //endif; ?>

