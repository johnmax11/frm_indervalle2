<?php
namespace MiProyecto{
    require_once(dirname(dirname(__FILE__)).'/cron.php');
    $obj_cron = new cron();
    $obj_cron->publicar_foto_facebookEvent();
}
