<?php

/*
* Archivo de desinstalacion
*/

//Solo se ejecuta la desinstalacion si es WordPress quien lo solicita
defined('ABSPATH') or die( "Hasta la vista Baby" );
if(!defined('WP_UNINSTALL_PLUGIN'))
{
    die;
}
?>
