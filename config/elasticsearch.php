<?php

/**
 * Elasticsearch 配置文件
 *
 * @author Flc <i@flc.io>
 */
return [
    // 默认连接名
    'default' => 'default',

    // 所有连接
    'connections' => [
        'default' => [
            'host' => ['172.17.0.4:9200'],
        ],
    ],
];
