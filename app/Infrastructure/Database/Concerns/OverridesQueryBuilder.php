<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Concerns;

use App\Infrastructure\Database\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;

/** @mixin Model */
trait OverridesQueryBuilder
{
    protected function newBaseQueryBuilder(): QueryBuilder
    {
        $connection = $this->getConnection();
        $grammar = $connection->getQueryGrammar();
        $processor = $connection->getPostProcessor();

        return new QueryBuilder($connection, $grammar, $processor);
    }
}
