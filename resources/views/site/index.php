<?php

declare(strict_types=1);
use Yiisoft\User\CurrentUser;
use Yiisoft\Session\SessionInterface;

/** @var App\ApplicationParameters $applicationParameters
 *  @var CurrentUser $user
 *  @var \Yiisoft\Router\UrlGeneratorInterface $url
 *  @var SessionInterface $session
*/

$this->params['breadcrumbs'] = '/';

$this->setTitle($applicationParameters->getName());
?>
<p class="subtitle">Utente <strong><?php echo $session->get('nome'); ?></strong>!</p>
<h1 class="title">Benvenuto nella tua rubrica</h1>

