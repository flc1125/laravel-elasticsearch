# Laravel - Elasticsearch

[![Latest Stable Version](https://poser.pugx.org/flc/laravel-elasticsearch/v/stable)](https://packagist.org/packages/flc/laravel-elasticsearch)
[![Total Downloads](https://poser.pugx.org/flc/laravel-elasticsearch/downloads)](https://packagist.org/packages/flc/laravel-elasticsearch)
[![License](https://poser.pugx.org/flc/laravel-elasticsearch/license)](https://packagist.org/packages/flc/laravel-elasticsearch)
[![996.icu](https://img.shields.io/badge/link-996.icu-red.svg)](https://996.icu)

## 安装

```bash
composer require flc/laravel-elasticsearch
```

## 配置

[使用文档](https://docs.flc.io/elasticsearch/laravel-elasticsearch/introduction/)

## 示例

```php
<?php

use Elasticsearch;

Elasticsearch::index('users')
    ->select('id', 'username', 'password', 'created_at', 'updated_at', 'status', 'deleted')
    ->whereTerm('status', 1)
    ->orWhereIn('deleted', [1, 2])
    ->whereNotExists('area')
    ->where(['status' => 1, 'closed' => 0])
    ->where(function ($query) {
        $query->where('status', '=', 1)
            ->where('closed', 1)
            ->where('username', 'like', '张三');
            ->where('username', 'match', '李四');
    })
    ->orderBy('id', 'desc')
    ->take(2)
    ->paginate(10);
    // ->get();
    // ->search();
```

## TODO

- [ ] 聚合查询
- [ ] 写入、更新、删除文档
- [ ] 原生支持
- [ ] 辅助方法

## LICENSE

MIT
