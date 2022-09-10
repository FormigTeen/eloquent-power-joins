<?php

namespace Kirschbaum\PowerJoins\Database;

use Illuminate\Database\Query\Expression;

class MySql extends Expression
{
    public function raw(string $expression): self
    {
        $this->value = $expression;
        return $this;
    }

    function div($divisor): self
    {
        $column = $this->getValue();
        return $this->raw("($column DIV $divisor)");
    }

    public function mod($divisor): self
    {
        $column = $this->getValue();
        return $this->raw("($column MOD $divisor)");
    }

    public function date(): self
    {
        $column = $this->getValue();
        return $this->raw("DATE($column)");
    }

    public function dateFormat($format): self
    {
        $column = $this->getValue();
        return $this->raw("DATE_FORMAT($column, '$format')");
    }

    public function yearWeek(): self
    {
        $column = $this->getValue();
        return $this->raw("YEARWEEK($column)");
    }

    public function year(): self
    {
        $column = $this->getValue();
        return $this->raw("YEAR($column)");
    }

    public function getColumn($alias = null)
    {
        $column = $this->getValue();
        return $this->raw("$column" . ($alias ? " AS $alias" : ""));
    }

    function count(): self
    {
        $column = $this->getValue();
        return $this->raw("COUNT($column)");
    }

    public function min($column): Expression
    {
        $column = $this->getValue();
        return $this->raw("MIN($column)");
    }

    public function max(): Expression
    {
        $column = $this->getValue();
        return $this->raw("MAX($column)");
    }

    public function cast($type): Expression
    {
        $column = $this->getValue();
        return $this->raw("CAST($column AS $type)");
    }

    public function sum(): Expression
    {
        $column = $this->getValue();
        return $this->raw("SUM($column)")->cast("DOUBLE");
    }

    public function avg(): Expression
    {
        $column = $this->getValue();
        return $this->raw("AVG($column)");
    }

    public function length(): Expression
    {
        $column = $this->getValue();
        return $this->raw("LENGTH($column)");
    }

    public function string($string): self
    {
        return $this->raw("'$string'");
    }

    public function concat(...$columns): Expression
    {
        return $this->raw("CONCAT( " . collect([$this->getValue()])->merge($columns)->implode(", ") . " )");
    }

    public function upper(): Expression
    {
        $column = $this->getValue();
        return $this->raw("UPPER($column)");
    }

    public function substr(int $start, int $end): Expression
    {
        $column = $this->getValue();
        return $this->raw("SUBSTR($column, $start, $end)");
    }

    public function coalesce($default): Expression
    {
        $column = $this->getValue();
        return $this->raw("COALESCE($column, $default)");
    }

    public function jsonExtract($key): Expression
    {
        $column = $this->getValue();
        return $this->raw("JSON_EXTRACT($column, '$.$key')");
    }

    public function jsonArrayExtract(int $key): Expression
    {
        $column = $this->getValue();
        return $this->raw("JSON_EXTRACT($column, '$[$key]')");
    }

    public function round(int $range): Expression
    {
        $column = $this->getValue();
        return $this->raw("ROUND($column, $range)");
    }

    public function plus(...$columns): Expression
    {
        return $this->raw(" (" . collect([$this->getValue()])->merge($columns)->implode(" + ") . " ) ");
    }

    public function jsonUnquote(): Expression
    {
        $column = $this->getValue();
        return $this->raw("JSON_UNQUOTE($column)");
    }

}

