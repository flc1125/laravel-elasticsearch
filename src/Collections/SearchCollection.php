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
     * @var array
     */
    protected $result = [];

    /**
     * 创建一个搜索结果集实例
     *
     * @param array $result
     */
    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    /**
     * 返回总记录数
     *
     * @return int
     */
    public function total(): int
    {
        return $this->result['hits']['total'] ?? 0;
    }

    /**
     * 返回 source 集合
     *
     * @return \Illuminate\Support\Collection
     */
    public function source(): \Illuminate\Support\Collection
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
    public function aggs(): \Illuminate\Support\Collection
    {
        return collect($this->result['aggregations']);
    }

    /**
     * 返回数组
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->result;
    }
}
