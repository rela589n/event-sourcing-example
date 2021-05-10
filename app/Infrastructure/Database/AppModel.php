<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use App\Infrastructure\Database\Concerns\OverridesEloquentBuilder;
use App\Infrastructure\Database\Concerns\OverridesEloquentCollection;
use App\Infrastructure\Database\Concerns\OverridesQueryBuilder;
use App\Infrastructure\Database\Eloquent\EloquentBuilder;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static EloquentBuilder|AppModel newModelQuery()
 * @method static EloquentBuilder|AppModel newQuery()
 * @method static EloquentBuilder|AppModel query()
 *
 * @mixin Eloquent
 */
class AppModel extends Model
{
    use OverridesQueryBuilder;
    use OverridesEloquentBuilder;
    use OverridesEloquentCollection;

    public function touchWithoutSaving(): void
    {
        if (!$this->usesTimestamps()) {
            return;
        }

        $this->updateTimestamps();
    }
}
