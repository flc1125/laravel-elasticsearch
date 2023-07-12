<?php

use Elasticsearch\Client;

it('Test base elasticsearch client', function () {
    $version = Client::VERSION;

    expect($version)->toBeString()
        ->and(version_compare($version, '6.0.0', '>='))->toBeTrue()
        ->and(version_compare($version, '8.0.0', '<'))->toBeTrue();
});