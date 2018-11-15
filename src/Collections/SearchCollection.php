<?php

namespace Flc\Laravel\Elasticsearch\Collections;

/**
 * 搜索结果集
 *
 * @author Flc <2018-11-15 10:29:31>
 */
class SearchCollection
{
    /**
     * 创建一个搜索结果集实例
     *
     * @param array $result [description]
     */
    public function __construct($result = [])
    {
        $this->result = $result;
    }

    /**
     * 返回总记录数
     *
     * @return int
     */
    public function total()
    {
        return $this->result['hits']['total'] ?? 0;
    }

    /**
     * 返回 source 集合
     *
     * @return \Illuminate\Support\Collection
     */
    public function source()
    {
        return collect(array_map(function ($result) {
            return (object) $result['_source'];
        }, $this->result['hits']['hits']));
    }

    /**
     * 返回聚合集合
     *
     * @return \Illuminate\Support\Collection
     */
    public function aggs()
    {
        return collect($this->result['aggregations']);
    }

    /**
     * 返回数组
     *
     * @return array
     */
    public function toArray()
    {
        return $this->result;
    }
}
