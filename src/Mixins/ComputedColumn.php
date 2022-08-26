<?php

namespace Kirschbaum\PowerJoins\Mixins;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Kirschbaum\PowerJoins\Database\Expression;
use Kirschbaum\PowerJoins\MetaColumn;
use Kirschbaum\PowerJoins\PowerJoinClause;
use Illuminate\Support\Str;

class ComputedColumn
{
    public function hasNamedColumn()
    {
        return function ($scope) {
            return method_exists($this->model, 'get'.ucfirst($scope).'Column');
        };
    }

    public function callNamedColumn()
    {
        return function ($column, array $parameters = []) {
            return $this->callScope(function ($scope) use ($column, $parameters) {
                return $this->addSelectWithBase(
                    $this->getColumnExpression($column)->getColumn(Str::of($column)->snake())
                );
            });
        };
    }

    public function getColumnExpression()
    {
        return function (string $column)
        {
            return Expression::getMysql(
                call_user_func_array([$this->model, 'get'.ucfirst($column).'Column'], [$column, [$this]])
            );
        };
    }

    public function addBaseSelect()
    {
        return function () {
            if (!$this->getSelect()) {
                $this->addSelect(
                    MetaColumn::for($this->getModel())
                        ->getColumn('*')
                );
            }
            return $this;
        };
    }


    public function addSelectWithBase()
    {
        return function ($column) {
            return $this->addBaseSelect()
                ->addSelect($column);
        };
    }

    public function addComputedColumn()
    {
        return function ($column) {
            if (is_array($column))
                return collect($column)->reduce(fn($object, $column) => $object->addComputedColumn($column), $this);

            if ($this->hasNamedColumn(Str::of($column)->camel())) {
                return $this->callNamedColumn(Str::of($column)->camel());
            }
            
            return $this;
        };
    }

}
