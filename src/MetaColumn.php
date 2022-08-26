<?php

namespace Kirschbaum\PowerJoins;

use Illuminate\Database\Eloquent\Model;

class MetaColumn
{

    protected ?string $alias = null;

    protected function __construct(
        protected Model $model
    ){}

    public static function for($classOrObject): self
    {
        if ( is_string($classOrObject) )
            return new self(call_user_func([$classOrObject, 'make']));
        else
            return new self($classOrObject);
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setAlias($alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    public function hasAlias(): bool
    {
        return (bool) $this->alias;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getTable(): string
    {
        return $this->getModel()->getTable() . ($this->hasAlias() ? " AS " . $this->getAlias() : '');
    }

    public function getColumn($column): \Illuminate\Database\Query\Expression
    {
        if ( is_array($column) )
            return collect($column)->map(fn($key) => $this->getColumn($key))->toArray();

        return \DB::raw(($this->hasAlias() ? $this->getAlias() : $this->getTable()) . '.' . $column);
    }

    public function getJoinColumn($column): array|\Illuminate\Database\Query\Expression
    {
        if ( is_array($column) )
            return collect($column)->map(fn($key) => $this->getJoinColumn($key))->toArray();

        return \DB::raw(
            $this->getColumn($column) . " AS " .
            implode("___", [
                "joinWith", $this->hasAlias() ? $this->getAlias() : $this->getTable(), $column
            ])
        );
    }
}
