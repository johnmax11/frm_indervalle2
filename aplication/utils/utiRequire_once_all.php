<?php 
namespace MiProyecto{
    if(!isset($_SESSION)){
        session_start();
    }
    /* agregamos las librerias utiles */
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/utiRequest.php');
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/html2pdf_v4.03/templates_pdf.php');
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/mail/mail_plantillas.php');
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/mail/mail_envios.php');
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/error/ExceptionHandler.php');
}
