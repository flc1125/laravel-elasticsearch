<?php

namespace Flc\Laravel\Elasticsearch\Grammars;

use Flc\Laravel\Elasticsearch\Query\Builder;

/**
 * Elasticsearch 语法转换
 *
 * @author Flc <2018-10-22 17:01:52>
 */
class Grammar
{

    /**
     * 转换 where 条件
     *
     * @param  Builder $query
     * @return array
     */
    public function compileWheres(Builder $query)
    {
        $bool = [];

        foreach (['must', 'filter', 'should', 'must_not'] as $operation) {
            if ($where = $query->wheres[$operation]) {
                $bool[$operation] = $where;
            }
        }

        return $bool;
    }
}