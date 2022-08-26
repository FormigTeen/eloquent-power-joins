<?php

namespace Kirschbaum\PowerJoins\Database;

use Kirschbaum\PowerJoins\Database\MySql;
use Illuminate\Support\Arr;

class Expression
{
    public array $registerExpression = [
        'mysql' => MySql::class
    ];

    public function getExpression($key = null)
    {
        return new (Arr::get($this->registerExpression, $key ?? "mysql"))("");
    }

    final public static function getMysql($column): MySql
    {
        return (new self())->getExpression("mysql")->raw($column);
    }

}
