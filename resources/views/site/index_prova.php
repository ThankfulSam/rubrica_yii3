<?php

declare(strict_types=1);
use Yiisoft\User\CurrentUser;

/** @var App\ApplicationParameters $applicationParameters
 *  @var CurrentUser $user
 */

$this->params['breadcrumbs'] = '/';

$this->setTitle($applicationParameters->getName());
?>

<h1 class="title">I miei contatti</h1>

<p class="subtitle">Utente <strong><?php echo $user->getId(); ?></strong>!</p>


    <?php /*<p class="subtitle is-italic"><a href="https://github.com/yiisoft/docs/tree/master/guide/en" target="_blank" rel="noopener">
        Don't forget to check the guide.
    </a>*/?>
    
    <?php 
    echo '<table class="center">';
    foreach ($tab_contatti as $tab){
        $tab = array_values($tab);
        echo '<tr>';
        for($i=1;$i<=4;$i++){
            echo '<td>' . $tab[$i] . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    ?>
    
</p>
