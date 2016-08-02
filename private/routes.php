<?php
    return [
        \Old\Milantex\Core\Route::get('page/[a-z][a-z0-9]*', '\\Application\\Modules\\MainModule::getPage'),

        \Old\Milantex\Core\Route::post('.*', '\\Application\\Modules\\MainModule::getPage'), # Ruta koja ce na kraju sigurno proci, ako ni jedna pre ne prodje
    ];
