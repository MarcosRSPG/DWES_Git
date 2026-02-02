<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    //Cargamos librerias
    require_once __DIR__.'/../app/config/config.php';

    //require_once __DIR__.'/../app/librerias/Db.php';
    //require_once __DIR__.'/../app/librerias/Controlador.php';
    //require_once __DIR__.'/../app/librerias/Core.php';