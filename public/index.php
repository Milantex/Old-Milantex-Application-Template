<?php
    require_once '../private/vendor/autoload.php';
    require_once '../private/Configuration.php';

    $routes = require_once '../private/routes.php';

    $app = new \Old\Milantex\Core\Application(new ApplicationConfiguration(), $routes);
    $app->run();
