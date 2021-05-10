<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Exceptions;

use Illuminate\Database\Eloquent\Model;
use LogicException;

final class WriteOperationIsNotAllowedForReadModel extends LogicException
{
    public function __construct(Model $model)
    {
        $class = $model::class;

        parent::__construct(
            "Model {$class}#{$model->getKey()} is readonly. For write operation use Doctrine model",
        );
    }
}
