<?php

declare(strict_types=1);

use App\Support\Path;

test("deve retornar os arquivos da pasta corretamente", function () {
    $dirname = realpath(dirname(__DIR__, 2)) . "/app/Console";

    $files = Path::getMultipleFiles($dirname);

    expect($files)->toHaveCount(1);
});
