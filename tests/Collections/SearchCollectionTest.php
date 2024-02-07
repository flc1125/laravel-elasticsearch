<?php

namespace Flc\Laravel\Elasticsearch\Tests\Collections;

use Flc\Laravel\Elasticsearch\Collections\SearchCollection;
use Flc\Laravel\Elasticsearch\Tests\TestCase;
use Illuminate\Support\Collection;

class SearchCollectionTest extends TestCase
{
    public function testTotal(): void
    {
        $tests = [
            [
                'args' => [
                    'hits' => [
                        'total' => [
                            'value'    => 100,
                            'relation' => 'eq',
                        ],
                    ],
                ],
                'want' => 100,
            ],
            [
                'args' => [
                    'hits' => [
                        'total' => [
                            'value'    => 200,
                            'relation' => 'eq',
                        ],
                    ],
                ],
                'want' => 200,
            ],
            [
                'args' => [
                    'hits' => [
                    ],
                ],
                'want' => 0,
            ],
            [
                'args' => [
                ],
                'want' => 0,
            ],
        ];

        foreach ($tests as $test) {
            $collection = new SearchCollection($test['args']);
            $this->assertSame($test['want'], $collection->total());
        }
    }

    public function testSource(): void
    {
        $tests = [
            [
                'args' => [
                    'hits' => [
                        'hits' => [
                            [
                                '_source' => [
                                    'id'   => 1,
                                    'name' => 'flc',
                                ],
                            ],
                            [
                                '_source' => [
                                    'id'   => 2,
                                    'name' => 'flc2',
                                ],
                            ],
                        ],
                    ],
                ],
                'want' => [
                    (object) [
                        'id'   => 1,
                        'name' => 'flc',
                    ],
                    (object) [
                        'id'   => 2,
                        'name' => 'flc2',
                    ],
                ],
            ],
        ];

        foreach ($tests as $test) {
            $collection = new SearchCollection($test['args']);
            $source     = $collection->source();

            $this->assertInstanceOf(Collection::class, $source);
            $this->assertEquals($test['want'], $source->toArray());
        }
    }

    public function testToArray(): void
    {
        $tests = [
            [
                'args' => [
                    'hits' => [
                        'hits' => [
                            [
                                '_source' => [
                                    'id'   => 1,
                                    'name' => 'flc',
                                ],
                            ],
                            [
                                '_source' => [
                                    'id'   => 2,
                                    'name' => 'flc2',
                                ],
                            ],
                        ],
                    ],
                ],
                'want' => [
                    'hits' => [
                        'hits' => [
                            [
                                '_source' => [
                                    'id'   => 1,
                                    'name' => 'flc',
                                ],
                            ],
                            [
                                '_source' => [
                                    'id'   => 2,
                                    'name' => 'flc2',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($tests as $test) {
            $collection = new SearchCollection($test['args']);
            $this->assertEquals($test['want'], $collection->toArray());
        }
    }
}
