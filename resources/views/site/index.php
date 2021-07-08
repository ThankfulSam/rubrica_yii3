<?php

declare(strict_types=1);
use Yiisoft\User\CurrentUser;

/** @var App\ApplicationParameters $applicationParameters
 *  @var CurrentUser $user
 *  @var \Yiisoft\Router\UrlGeneratorInterface $url
*/

$this->params['breadcrumbs'] = '/';

$this->setTitle($applicationParameters->getName());
?>
<p class="subtitle">Utente <strong><?php echo $user->getId(); ?></strong>!</p>
<h1 class="title">Benvenuto nella tua rubrica</h1>

