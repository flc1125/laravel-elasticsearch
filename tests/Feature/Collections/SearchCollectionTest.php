<?php

use Flc\Laravel\Elasticsearch\Collections\SearchCollection;
use Illuminate\Support\Collection;

it('total', function (SearchCollection $collection, $want) {
    expect($collection->total())->toBe($want);
})->with([
    [
        new SearchCollection([
            'hits' => [
                'total' => 100,
            ],
        ]),
        100,
    ],
    [
        new SearchCollection([
            'hits' => [
                'total' => 200,
            ],
        ]),
        200,
    ],
    [
        new SearchCollection([
            'hits' => [
            ],
        ]),
        0,
    ],

    [
        new SearchCollection([]),
        0,
    ],
]);

it('source', function (SearchCollection $collection, $want) {
    $source = $collection->source();

    expect($source)->toBeInstanceOf(Collection::class)
        ->and($source->toArray())->toEqual($want);
})->with([
    [
        new SearchCollection([
            'hits' => [
                'hits' => [
                    [
                        '_source' => [
                            'id' => 1,
                            'name' => 'flc',
                        ],
                    ],
                    [
                        '_source' => [
                            'id' => 2,
                            'name' => 'flc2',
                        ],
                    ],
                ],
            ],
            ]),
        [
            (object) [
                'id' => 1,
                'name' => 'flc',
            ],
            (object) [
                'id' => 2,
                'name' => 'flc2',
            ],
        ], ]
]);

it('to array', function (SearchCollection $collection, $want) {
    expect($collection->toArray())->toBe($want);
})->with([
    [
        new SearchCollection(['a' => 1]),
        ['a'=> 1],
    ]
]);