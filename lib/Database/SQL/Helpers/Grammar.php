<?php

namespace AWC\Database\SQL\Helpers;

use AWC\Database\SQL\Helpers\Query;
use AWC\Helpers\Arr;

class Grammar
{

    /**
     * The grammar specific operators.
     *
     * @var array
     */
    protected $operators = [];

    /**
     * Compile Select Statement
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @return string
     */


    protected $selectComponents = [
        'aggregate',
        'columns',
        'from',
        'joins',
        'where',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
        'unions',
        'lock',
    ];


    /**
     * Compile a SELECT Query into an SQL
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @return string
     */
    public function compileSelect(Query $query)
    {
        if (is_null($query->columns)) {
            $query->columns = ['*'];
        }

        $sql = trim($this->concatenate(
            $this->compileComponents($query))
        );

        return $sql;
    }

    /**
     * compile components
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @return array
     */
    protected function compileComponents(Query $query)
    {
        $sql = [];

        foreach ($this->selectComponents as $component) {
            if (!is_null($query->$component)) {
                $method = 'compile' . ucfirst($component);
                if ($component == 'joins') {
//                    var_dump($query->joins);
                }

                $sql[$component] = $this->$method($query, $query->$component);
            }
        }

        return $sql;
    }

    protected function compileAggregate(Query $query, $aggregate)
    {
        $column = $this->columnize($aggregate['columns']);

        // If the query has a "distinct" constraint and we're not asking for all columns
        // we need to prepend "distinct" onto the column name so that the query takes
        // it into account when it performs the aggregating operations on the data.
        if ($query->distinct && $column !== '*') {
            $column = 'DISTINCT ' . $column;
        }

        return 'SELECT ' . $aggregate['function'] . '(' . $column . ') AS aggregate';
    }

    protected function compileFrom(Query $query, $table)
    {
        return "FROM {$table}";
    }

    /**
     * compileJoins
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $joins
     * @return string
     */
    protected function compileJoins(Query $query, $joins)
    {
        return (new Arr($joins))->map(function ($join) use ($query) {
            $table = $join->table;

            $type = strtoupper($join->type);

            $nestedJoins = is_null($join->joins) ? '' : ' ' . $this->compileJoins($query, $join->joins);

            return trim("{$type} JOIN {$table}{$nestedJoins} {$this->compileWhere( $join )}");
        })->implode(" ");

    }

    /**
     *
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @return string
     */
    public function compileWhere(Query $query)
    {
        // Each type of where clauses has its own compiler function which is responsible
        // for actually creating the where clauses SQL. This helps keep the code nice
        // and maintainable since each clause has a very small method that it uses.
        if (is_null($query->where)) {
            return '';
        }

        // If we actually have some where clauses, we will strip off the first boolean
        // operator, which is added by the query builders for convenience so we can
        // avoid checking for the first clauses in each of the compilers methods.
        if (count($sql = $this->compileWheresToArray($query)) > 0) {
            return $this->concatenateWhereClauses($query, $sql);
        }

        return '';
    }


    /**
     * Format the where clause statements into one string.
     *
     * @param $query
     * @param $sql
     * @return string
     */
    protected function concatenateWhereClauses($query, $sql)
    {
        $conjunction = $query instanceof JoinClause ? "ON" : 'WHERE';

        return $conjunction . ' ' . $this->removeLeadingBoolean(implode(' ', $sql));
    }

    /**
     * Parse Array and return statement
     *
     * @param $query
     * @return array
     */
    protected function compileWheresToArray($query)
    {
        return (new Arr($query->where))->map(function ($where) use ($query) {
            return $where['link'] . ' ' . $this->{"where{$where['type']}"}($query, $where);
        })->all();
    }

    /**
     * Create a raw Where clause
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $where
     * @return mixed
     */
    protected function whereRaw(Query $query, $where)
    {
        return $where['sql'];
    }

    /**
     * Return a Basic where statement
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $where
     * @return string
     */
    protected function whereBasic(Query $query, $where)
    {
        return $where['column'] . " " . $where['operator'] . " '" . $where['value'] . "'";
    }

    /**
     * Return a WHERE IN Clause
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $where
     * @return string
     */
    protected function whereIn(Query $query, $where)
    {
        return $where['column'] . " IN ({$this->columnize($where['values'])})";
    }

    /**
     * Return a WHERE NOT IN Clause
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $where
     * @return string
     */
    protected function whereNotIn(Query $query, $where)
    {
        return $where['column'] . " NOT IN ({$this->columnize($where['values'])})";
    }

    /**
     * Return a WHERE <column> NULL Clause
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $where
     * @return string
     */
    protected function whereNull(Query $query, $where)
    {
        return "{$where['column']} IS NULL";
    }

    /**
     * Return a WHERE <column> NOT NULL Clause
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $where
     * @return string
     */
    protected function whereNotNull(Query $query, $where)
    {
        return "{$where['column']} IS NOT NULL";
    }

    /**
     * Return a WHERE <column> BETWEEN Clause
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $where
     * @return string
     */
    protected function whereBetween(Query $query, $where)
    {
        return $where['column'] . " BETWEEN '" . $where['values'][0] . "' AND '" . $where['values'][1] . "'";
    }

    /**
     * Return a Where Column clause
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $where
     * @return string
     */
    protected function whereColumn(Query $query, $where)
    {
        return $where['first'] . ' ' . $where['operator'] . ' ' . $where['second'];
    }

    /**
     * Compile the Group statement of the query
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $groups
     * @return string
     */
    protected function compileGroups(Query $query, $groups)
    {
        return 'GROUP BY ' . $this->columnize($groups);
    }

    /**
     * Compile the Havings statement of the Query
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $havings
     * @return string
     */
    protected function compileHavings(Query $query, $havings)
    {
        $sql = implode(' ', array_map([$this, 'compileHaving'], $havings));

        return '';
    }


    /**
     * Compile a sing having clause
     *
     * @param array $having
     * @return string
     */
    protected function compileHaving(array $having)
    {
        if( $having['type'] === 'Raw'){
            return $having['link'].' '.$having['sql'];
        }

        return $this->compileBasicHaving($having);
    }

    /**
     * Compile a basic having clause
     *
     * @param $having
     * @return string
     */
    protected function compileBasicHaving( $having )
    {
        $column = $having['column'];

        $parameter = $this->parameterize($having['value']);

        return $having['link'].' '.$column.' '.$having['operator'].' '.$parameter;
    }

    /**
     * Compile the Order by portion of the query
     *
     * @param \AWC\atabase\SQL\Helpers\Query $query
     * @param $orders
     * @return string
     */
    protected function compileOrders (Query $query, $orders )
    {
        if( ! empty($orders)){
            return 'ORDER BY '.implode( ", ", $this->compileOrdersToArray( $query, $orders ) );
        }

        return '';
    }

    /**
     * compile the query orders to an array
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $orders
     * @return array
     */
    protected function compileOrdersToArray( Query $query, $orders)
    {
        return array_map( function($order){
            return ! isset($order['sql'])
                        ? $order['column'].' '.$order['direction']
                        : $order['sql'];
        }, $orders);
    }

    /**
     * Compile Random Statement
     *
     * @param $seed
     * @return string
     */
    protected function compileRandom( $seed )
    {
        return 'RANDOM()';
    }

    /**
     * Compile the Limit portions of the query
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $limit
     * @return string
     */
    protected function compileLimit(Query $query, $limit)
    {
        return 'LIMIT '.(int) $limit;
    }

    /**
     * Compile the Offset portions of the query
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $offset
     * @return string
     */
    protected function compileOffset(Query $query, $offset)
    {
        return 'OFFSET '.(int) $offset;
    }


    /**
     * Compile the UNION queries to the query
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $unions
     * @return string
     */
    protected function compileUnions( Query $query, $unions )
    {
        $sql = '';

        foreach( $query->unions as $union ){
            $sql .= $this->compileUnion($union);
        }

        if( !empty($query->unionorders)) {
            $sql .= ' '.$this->compileOrders( $query, $query->unionOrders );
        }

        if( !isset($query->unionLimit)) {
            $sql .= ' '.$this->compileLimit( $query, $query->unionLimit );
        }

        if( !isset($query->unionOffset)) {
            $sql .= ' '.$this->compileOffset( $query, $query->unionOffset );
        }


        return ltrim($sql);
    }

    /**
     * Compile a single Union Clause
     *
     * @param array $union
     * @return string
     */
    protected function compileUnion( array $union )
    {
        $conjunction = $union['all'] ? ' union all ' : ' union ' ;

        return $conjunction.$union['query']->toSql();
    }

    /**
     * Compile a lock statement
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $value
     * @return string
     */
    protected function compileLock(Query $query, $value)
    {
        return is_string($value) ? $value : '';
    }

    /**
     * Compile an insert statement
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param array $values
     * @return string
     */
    public function compileInsert(Query $query, array $values)
    {
        $table = $query->table;

        $columns = $this->columnize(array_keys(reset($values)));

        $parameters = $this->parameterize(array_values(reset($values)));

        return "INSERT INTO {$table}({$columns}) VALUES {$parameters}";
    }

    /**
     * Create query parameter place-holders for an array.
     *
     * @param  array $values
     * @return string
     */
    public function parameterize(array $values)
    {
        return implode(', ', $values);
    }


    /**
     * @param \AWC\Database\SQL\Helpers\Query $query
     *
     * @return string
     */
    public function compileDelete(Query $query)
    {

        return "";
    }

    /**
     * Compiles the update sql
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param array $values
     * @return string
     */
    public function compileUpdate(Query $query, array $values)
    {
        $table = $query->table;

        $columns = (new Arr($values))->map(function ($value, $key) {
            return "{$key}='{$value}'";
        })->implode(', ');

        $wheres = $this->compileWhere($query);

        return trim("UPDATE {$table} SET {$columns} {$wheres}");
    }

    /**
     * Compile Columns
     *
     * @param \AWC\Database\SQL\Helpers\Query $query
     * @param $columns
     * @return string
     */
    protected function compileColumns(Query $query, $columns)
    {
        $select = $query->distinct ? 'SELECT DISTINCT ' : 'SELECT ';

        return $select . $this->columnize($columns);
    }

    /**
     * Process the columns to be in an SQL column format
     *
     * @param array $columns
     * @return string
     */
    public function columnize(array $columns)
    {
        return implode(', ', $columns);
    }


    /**
     * Concatenate an array of segments, removing empties.
     *
     * @param  array $segments
     * @return string
     */
    protected function concatenate($segments)
    {
        return implode(' ', array_filter($segments, function ($value) {
            return (string)$value !== '';
        }));
    }

    protected function removeLeadingBoolean($value)
    {
        return preg_replace('/and |or /i', '', $value, 1);
    }

    public function getOperators()
    {
        return $this->operators;
    }


}
