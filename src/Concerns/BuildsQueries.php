<?php

namespace Flc\Laravel\Elasticsearch\Concerns;

use Flc\Laravel\Elasticsearch\Collections\SearchCollection;
use Illuminate\Database\Concerns\BuildsQueries as BaseBuildsQueries;

/**
 * 构建查询
 *
 * @author Flc <2018-11-15 11:27:52>
 */
trait BuildsQueries
{
    use BaseBuildsQueries;

    /**
     * 创建一个搜索集合
     *
     * @param array $result
     *
     * @return \Flc\Laravel\Elasticsearch\Collections\SearchCollection
     */
    protected function searchCollection(array $result = []): SearchCollection
    {
        return new SearchCollection($result);
    }
}
