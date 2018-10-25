<?php

namespace Flc\Laravel\Elasticsearch\Query;

/**
 * Elasticsearch 数据格式转换
 *
 * @author Flc <2018-10-25 10:56:12>
 */
class Processor
{
    /**
     * 格式化查询输出
     *
     * @param Builder $query
     * @param array   $results
     *
     * @return array
     */
    public function processSelect(Builder $query, $results)
    {
        return array_map(function ($result) {
            return (object) $result['_source'];
        }, $results['hits']['hits']);
    }

    // ===================

    public function processAggregateFunction(Builder $query, $results)
    {
        return $this->processAggregate($query, $results);
    }

    public function processAggregate(Builder $query, $results)
    {
        return $results;
    }
}
