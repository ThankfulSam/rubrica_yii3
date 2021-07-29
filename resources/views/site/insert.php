<?php
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use App\Form\ContactForm;
/* @var \Yiisoft\Router\UrlGeneratorInterface $url */
/* @var ContactForm @form */
?>

<h1 class="title">Inserimento nuovo contatto</h1>

<?php if (!empty($error)): ?>
    <div class="notification is-danger">
        <?= Html::encode($error) ?>
    </div>
<?php endif ?>

<?= Form::widget()
->action($url->generate('site/insert'))
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
<?= Html::submitButton('Inserisci') ?>

<?= Form::end() ?>
<br>
<button>
	<?php echo Html::a('home', $url->generate('home'))?>
</button>
