<?php

namespace Flc\Laravel\Elasticsearch\Query;

use Closure;
use RuntimeException;
use InvalidArgumentException;
use Illuminate\Pagination\Paginator;
use Elasticsearch\Client as ElasticsearchClient;
use Flc\Laravel\Elasticsearch\Concerns\BuildsQueries;

/**
 * Elasticsearch 查询构建类
 *
 * @author Flc <i@flc.io>
 */
class Builder
{
    use BuildsQueries;

    /**
     * @var \Elasticsearch\Client
     */
    public $client;

    /**
     * @var \Flc\Laravel\Elasticsearch\Query\Grammar
     */
    public $grammar;

    /**
     * 索引名
     *
     * @var string
     */
    public $index;

    /**
     * 索引Type
     *
     * @var string
     */
    public $type;

    /**
     * 搜寻条件
     *
     * @var array
     */
    public $wheres = [
        'filter'   => [],
        'should'   => [],
        'must'     => [],
        'must_not' => [],
    ];

    /**
     * 排序
     *
     * @var array
     */
    public $sort = [];

    /**
     * 从X条开始查询
     *
     * @var int
     */
    public $from;

    /**
     * 获取数量
     *
     * @var int
     */
    public $size;

    /**
     * 需要查询的字段
     *
     * @var array
     */
    public $_source;

    /**
     * 聚合查询条件
     *
     * @var array
     */
    public $aggs;

    /**
     * 所有的区间查询配置
     *
     * @var array
     */
    protected $range_operators = [
        '>' => 'gt', '<' => 'lt', '>=' => 'gte', '<=' => 'lte',
    ];

    /**
     * 实例化一个构建链接
     *
     * @param ElasticsearchClient $client
     * @param Grammar             $grammar
     */
    public function __construct(ElasticsearchClient $client, Grammar $grammar)
    {
        $this->client = $client;
        $this->grammar = $grammar;
    }

    /**
     * 指定索引名
     *
     * @param string $value
     *
     * @return $this
     */
    public function index($value)
    {
        $this->index = $value;

        return $this;
    }

    /**
     * 指定type
     *
     * @param string $value
     *
     * @return $this
     */
    public function type($value)
    {
        $this->type = $value;

        return $this;
    }

    /**
     * 指定需要查询获取的字段
     *
     * @param array|mixed $columns
     *
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $this->_source = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    /**
     * 追加排序规则
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function addOrder($value)
    {
        $this->sort[] = $value;

        return $this;
    }

    /**
     * 按自定字段排序
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        return $this->addOrder([
            $column => strtolower($direction) == 'asc' ? 'asc' : 'desc',
        ]);
    }

    /**
     * offset 方法别名
     *
     * @param int $value
     *
     * @return $this
     */
    public function skip($value)
    {
        return $this->offset($value);
    }

    /**
     * 跳过X条数据
     *
     * @param int $value
     *
     * @return $this
     */
    public function offset($value)
    {
        if ($value >= 0) {
            $this->from = $value;
        }

        return $this;
    }

    /**
     * limit 方法别名
     *
     * @param int $value
     *
     * @return $this
     */
    public function take($value)
    {
        return $this->limit($value);
    }

    /**
     * 设置获取的数据量
     *
     * @param int $value
     *
     * @return $this
     */
    public function limit($value)
    {
        if ($value >= 0) {
            $this->size = $value;
        }

        return $this;
    }

    /**
     * 以分页形式获取指定数量数据
     *
     * @param int $page
     * @param int $perPage
     *
     * @return $this
     */
    public function forPage($page, $perPage = 15)
    {
        return $this->skip(($page - 1) * $perPage)->take($perPage);
    }

    /**
     * 返回新的构建类
     *
     * @return \Flc\Laravel\Elasticsearch\Query\Builder
     */
    public function newQuery()
    {
        return new static($this->client, $this->grammar);
    }

    /**
     * 增加一个条件到查询中
     *
     * @param mixed  $value 条件语法
     * @param string $type  条件类型，filter/must/must_not/should
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function addWhere($value, $type = 'filter')
    {
        if (! array_key_exists($type, $this->wheres)) {
            throw new InvalidArgumentException("Invalid where type: {$type}.");
        }

        $this->wheres[$type][] = $value;

        return $this;
    }

    /**
     * term 查询
     *
     * @param string $column 字段
     * @param mixed  $value  值
     * @param string $type   条件类型
     *
     * @return $this
     */
    public function whereTerm($column, $value, $type = 'filter')
    {
        return $this->addWhere(
            ['term' => [$column => $value]], $type
        );
    }

    /**
     * terms 查询
     *
     * @param string $column 字段
     * @param array  $value  值
     * @param string $type   条件类型
     *
     * @return $this
     */
    public function whereTerms($column, array $value, $type = 'filter')
    {
        return $this->addWhere(
            ['terms' => [$column => $value]], $type
        );
    }

    /**
     * match 查询
     *
     * @param string $column 字段
     * @param mixed  $value  值
     * @param string $type   条件类型
     *
     * @return $this
     */
    public function whereMatch($column, $value, $type = 'filter')
    {
        return $this->addWhere(
            ['match' => [$column => $value]], $type
        );
    }

    /**
     * match_phrase 查询
     *
     * @param string $column 字段
     * @param mixed  $value  值
     * @param string $type   条件类型
     *
     * @return $this
     */
    public function whereMatchPhrase($column, $value, $type = 'filter')
    {
        return $this->addWhere(
            ['match_phrase' => [$column => $value]], $type
        );
    }

    /**
     * range 查询
     *
     * @param string $column   字段
     * @param string $operator 查询符号
     * @param mixed  $value    值
     * @param string $type     条件类型
     *
     * @return $this
     */
    public function whereRange($column, $operator, $value, $type = 'filter')
    {
        if (! array_key_exists($operator, $this->range_operators)) {
            throw new InvalidArgumentException("Invalid operator: {$operator}.");
        }

        return $this->addWhere([
            'range' => [
                $column => [$this->range_operators[$operator] => $value],
            ],
        ], $type);
    }

    /**
     * 区间查询(含等于)
     *
     * @param string $column 字段
     * @param array  $value  区间值
     * @param string $type   条件类型
     *
     * @return $this
     */
    public function whereBetween($column, array $value = [], $type = 'filter')
    {
        return $this->addWhere([
            'range' => [
                $column => [
                    'gte' => $value[0],
                    'lte' => $value[1],
                ],
            ],
        ], $type);
    }

    /**
     * 字段非 null 查询
     *
     * @param string $column
     * @param string $type
     *
     * @return $this
     */
    public function whereExists($column, $type = 'filter')
    {
        return $this->addWhere([
            'exists' => ['field' => $column],
        ], $type);
    }

    /**
     * 查询字段为 null
     *
     * @param string $column
     *
     * @return $this
     */
    public function whereNotExists($column)
    {
        return $this->whereExists($column, 'must_not');
    }

    /**
     * whereNotExists 别名
     *
     * @param string $column
     *
     * @return $this
     */
    public function whereNull($column)
    {
        return $this->whereNotExists($column);
    }

    /**
     * where 条件查询
     *
     * @param string|Colsure|array $column
     * @param mixed                $operator
     * @param mixed                $value
     * @param string               $type
     *
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $type = 'filter')
    {
        // 如果是数组
        if (is_array($column)) {
            return $this->addArrayOfWheres($column, $type);
        }

        // 如果 column 是匿名函数
        if ($column instanceof Closure) {
            return $this->whereNested(
                $column, $type
            );
        }

        // 如果只有两个参数
        if (func_num_args() === 2) {
            list($value, $operator) = [$operator, '='];
        }

        // 符号查询
        $this->performWhere($column, $value, $operator, $type);

        return $this;
    }

    /**
     * or where 查询(whereShould 别名)
     *
     * @param string|Colsure|array $column
     * @param mixed                $operator
     * @param mixed                $value
     *
     * @return $this
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        if (func_num_args() === 2) {
            list($value, $operator) = [$operator, '='];
        }

        return $this->where($column, $operator, $value, 'should');
    }

    /**
     * 多条件 and 查询；whereTerms 别名
     *
     * @param string $column 字段
     * @param array  $value  值
     * @param string $type   条件类型
     *
     * @return $this
     */
    public function whereIn($column, array $value, $type = 'filter')
    {
        return $this->whereTerms($column, $value, $type);
    }

    /**
     * 多条件 OR 查询
     *
     * @param string $column
     * @param array  $value
     *
     * @return $this
     */
    public function orWhereIn($column, array $value)
    {
        return $this->whereIn($column, $value, 'should');
    }

    /**
     * 多条件反查询；反whereIn
     *
     * @param string $column 字段
     * @param array  $value  值
     *
     * @return $this
     */
    public function whereNotIn($column, array $value)
    {
        return $this->whereIn($column, $value, 'must_not');
    }

    /**
     * 添加一个数组条件的查询
     *
     * @param array  $column
     * @param string $type
     * @param string $method
     *
     * @return $this
     */
    protected function addArrayOfWheres($column, $type, $method = 'where')
    {
        return $this->whereNested(function ($query) use ($column, $method, $type) {
            foreach ($column as $key => $value) {
                if (is_array($value)) {
                    $query->$method(...$value);
                } else {
                    $query->$method($key, '=', $value, $type);
                }
            }
        });
    }

    /**
     * 嵌套查询
     *
     * @param Closure $callback 回调函数
     * @param string  $type     条件类型
     *
     * @return $this
     */
    public function whereNested(Closure $callback, $type = 'filter')
    {
        call_user_func($callback, $query = $this->forNestedWhere());

        return $this->addNestedWhereQuery($query, $type);
    }

    /**
     * 创建一个用户嵌套查询的构建实例
     *
     * @return Builder
     */
    public function forNestedWhere()
    {
        return $this->newQuery();
    }

    /**
     * 将嵌套的查询构建条件加入到查询中
     *
     * @param Builder $query
     * @param string  $type
     */
    public function addNestedWhereQuery(self $query, $type = 'filter')
    {
        if ($bool = $query->grammar->compileWheres($query)) {
            $this->addWhere(
                ['bool' => $bool], $type
            );
        }

        return $this;
    }

    /**
     * 处理符号搜索
     *
     * @param string $column   字段
     * @param mixed  $value    值
     * @param string $operator 符号
     * @param string $type     条件类型
     *
     * @return array
     */
    protected function performWhere($column, $value, $operator, $type = 'filter')
    {
        switch ($operator) {
            case '=':
                return $this->whereTerm($column, $value, $type);
                break;

            case '>':
            case '<':
            case '>=':
            case '<=':
                return $this->whereRange($column, $operator, $value, $type);
                break;

            case '!=':
            case '<>':
                return $this->whereTerm($column, $value, 'must_not');
                break;

            case 'match':
                return $this->whereMatch($column, $value, $type);
                break;

            case 'not match':
                return $this->whereMatch($column, $value, 'must_not');
                break;

            case 'like':
                return $this->whereMatchPhrase($column, $value, $type);
                break;

            case 'not like':
                return $this->whereMatchPhrase($column, $value, 'must_not');
                break;
        }
    }

    /**
     * 返回搜索数据集
     *
     * @param array $columns
     *
     * @return \Flc\Laravel\Elasticsearch\Collections\SearchCollection
     */
    public function search($columns = ['*'])
    {
        return $this->onceWithColumn($columns, function () {
            return $this->searchCollection(
                $this->runSearch()
            );
        });
    }

    /**
     * 返回数据结果
     *
     * @param array $columns
     *
     * @return \Illuminate\Support\Collection
     */
    public function get($columns = ['*'])
    {
        return $this->search($columns)->source();
    }

    /**
     * 执行搜索
     *
     * @return array
     */
    public function runSearch()
    {
        return $this->client->search(
            $this->toSearch()
        );
    }

    /**
     * 获取转换为搜索请求参数
     *
     * @return array
     */
    public function toSearch()
    {
        return $this->grammar->compileSearch($this);
    }

    /**
     * 获取请求的 body 参数
     *
     * @return array
     */
    public function toBody()
    {
        return $this->grammar->compileBody($this);
    }

    /**
     * 执行一个获取指定字段的回调函数（拷贝 Laravel 官方）
     *
     * 执行完回调后，当前类的字段属性，会重置为原有配置值
     *
     * @param array    $columns
     * @param callable $callback
     *
     * @return mixed
     */
    protected function onceWithColumn($columns, $callback)
    {
        $original = $this->_source;

        if (is_null($original)) {
            $this->_source = $columns;
        }

        $result = $callback();

        $this->_source = $original;

        return $result;
    }

    /**
     * 分页查询
     *
     * @param int      $perPage
     * @param array    $columns
     * @param string   $pageName
     * @param int|null $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $searchCollection = $this->forPage($page, $perPage)->search($columns);

        $total = $searchCollection->total();
        $results = $total ? $searchCollection->source() : collect();

        return $this->paginator($results, $total, $perPage, $page, [
            'path'     => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * Throw an exception if the query doesn't have an orderBy clause.
     *
     *
     * @throws \RuntimeException
     */
    protected function enforceOrderBy()
    {
        if (empty($this->sort)) {
            throw new RuntimeException('You must specify an orderBy clause when using this function.');
        }
    }

    // 以下为不确定数据
}
