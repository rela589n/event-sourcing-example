<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Query;

use Carbon\Carbon;
use Closure;
use DateTimeInterface;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class QueryBuilder extends Builder
{
    public function wrapValuesToStrict($values): array
    {
        return collect($values)
            ->map([$this, 'wrapValueToStrict'])
            ->toArray();
    }

    public function wrapValueToStrict($value)
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        if (is_array($value)) {
            return json_encode($value, JSON_THROW_ON_ERROR);
        }

        $grammar = $this->getGrammar();

        if (is_string($value)) {
            json_decode($value);

            if (json_last_error() === JSON_ERROR_NONE) {
                return "{$grammar->quoteString($value)}::jsonb";
            }

            try {
                $dateTime = Carbon::createFromFormat($grammar->getDateFormat(), $value);

                return $this->wrapValueToStrict($dateTime);
            } catch (Exception $e) {
            }

            return $grammar->quoteString($value);
        }

        if ($value instanceof DateTimeInterface) {
            return "{$grammar->quoteString($value->format($grammar->getDateFormat()))}::timestamptz";
        }

        return $value;
    }

    public function orderByField(string $field, array $orders): self
    {
        $field = str_contains($field, '.')
            ? "{$this->from}.$field"
            : $field;

        $cases = collect($orders)
            ->map(fn ($value, int $order) => "when {$field} = '{$value}' then $order")
            ->join(' ');

        return $this->orderBy(
            $this->raw("case $cases end")
        );
    }

    /**
     * @param Closure|self|string $query
     * @param string $as
     *
     * @return $this
     */
    public function withSub($query, $as): self
    {
        if (is_null($this->columns)) {
            $this->select([$this->from . '.*']);
        }

        return $this->selectSub($query, $as);
    }

    /**
     * @param Closure|self|string $query
     * @param string|null $as
     * @param string $boolean
     * @param bool $not
     *
     * @return $this
     */
    public function whereSubExists($query, string $as = null, $boolean = 'and', $not = false): self
    {
        $as = $as ?? Str::random(12);

        return $this->whereExists(
            fn (QueryBuilder $q) => $q->select($this->raw(1))
                ->fromSub($query, $as),
            $boolean,
            $not,
        );
    }

    /**
     * @param Closure|self|string $query
     * @param string|null $as
     * @param string $boolean
     *
     * @return $this
     */
    public function whereSubNotExists($query, string $as = null, $boolean = 'and'): self
    {
        return $this->whereSubExists($query, $as, $boolean, true);
    }

    public function dumpSql(): void
    {
        $query = str_replace(['?'], ['\'%s\''], $this->toSql());
        $query = vsprintf($query, $this->getBindings());

        dump($query);
    }
}
