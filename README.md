# Laravel - Elasticsearch

[![Latest Stable Version](https://poser.pugx.org/flc/laravel-elasticsearch/v/stable)](https://packagist.org/packages/flc/laravel-elasticsearch)
[![Total Downloads](https://poser.pugx.org/flc/laravel-elasticsearch/downloads)](https://packagist.org/packages/flc/laravel-elasticsearch)
[![License](https://poser.pugx.org/flc/laravel-elasticsearch/license)](https://packagist.org/packages/flc/laravel-elasticsearch)
[![996.icu](https://img.shields.io/badge/link-996.icu-red.svg)](https://996.icu)
[![LICENSE](https://img.shields.io/badge/license-Anti%20996-blue.svg)](https://github.com/996icu/996.ICU/blob/master/LICENSE)

## 安装

```bash
composer require flc/laravel-elasticsearch
``` 

## 版本矩阵


| Package Version | Elasticsearch Version | Elasticsearch-PHP Branch |
|-----------------|-----------------------|--------------------------|
| v2.x            | >= 7.x                | >= 7.x                   |
| v1.x            | >= 6.x                | >= 7.x                   |



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

## LICENSE

- MIT
- Anti 996
