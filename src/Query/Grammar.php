<?php

namespace Flc\Laravel\Elasticsearch\Query;

/**
 * Elasticsearch 语法转换
 *
 * @author Flc <2018-10-22 17:01:52>
 */
class Grammar
{
    /**
     * 返回搜索的参数
     *
     * @param Builder $query
     *
     * @return array
     */
    public function compileSearch(Builder $query): array
    {
        $params = $this->compileBase($query);

        if (! is_null($query->_source)) {
            $params['_source'] = $query->_source;
        }

        if (! is_null($query->from)) {
            $params['from'] = $query->from;
        }

        if (! is_null($query->size)) {
            $params['size'] = $query->size;
        }

        if ($body = $query->grammar->compileBody($query)) {
            $params['body'] = $body;
        }

        return $params;
    }

    /**
     * 返回查询的 body 参数
     *
     * @param Builder $query
     *
     * @return array
     */
    public function compileBody(Builder $query): array
    {
        $body = [];

        if (count($query->sort) > 0) {
            $body['sort'] = $query->sort;
        }

        if ($bool = $query->grammar->compileWheres($query)) {
            $body['query']['bool'] = $bool;
        }

        return $body;
    }

    /**
     * 返回基础公共参数
     *
     * @param Builder $query
     *
     * @return array
     */
    public function compileBase(Builder $query): array
    {
        $params = [];

        $params['index'] = $query->index;

        if (! is_null($query->type)) {
            $params['type'] = $query->type;
        }

        return $params;
    }

    /**
     * 转换 where 条件
     *
     * @param Builder $query
     *
     * @return array
     */
    public function compileWheres(Builder $query): array
    {
        $wheres = [];

        foreach ($query->wheres as $type => $where) {
            if ($where) {
                $wheres[$type] = $where;
            }
        }

        return $wheres;
    }
}
