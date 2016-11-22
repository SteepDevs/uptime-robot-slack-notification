<?php

function initComposer()
{
    echo '  Инициализируем composer...';
    echo shell_exec("
cd " . __DIR__ . "/protected/;
if [ ! -f \"composer.phar\" ]; then
    wget https://getcomposer.org/download/1.0.0-beta2/composer.phar;
fi;
php composer.phar -v install;
php composer.phar -v update;
php composer.phar dump-autoload --optimize
    ");

    echo "\n";
}

initComposer();