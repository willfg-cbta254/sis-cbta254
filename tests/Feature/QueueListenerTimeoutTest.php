<?php

it('disables the child process timeout in the dev queue listener', function () {
    $composer = json_decode(file_get_contents(base_path('composer.json')), true, 512, JSON_THROW_ON_ERROR);

    $devScripts = implode(' ', $composer['scripts']['dev']);

    expect($devScripts)
        ->toContain('queue:listen')
        ->toContain('--timeout=0');
});
