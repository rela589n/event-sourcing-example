<?php

declare(strict_types=1);


namespace App\Infrastructure\Database;

use Doctrine\DBAL\Types\Type;
use Dunglas\DoctrineJsonOdm\Serializer;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot(): void
    {
        Type::getType('json_document')
            ->setSerializer(
                new Serializer(
                    [new ArrayDenormalizer(), new ObjectNormalizer()],
                    [new JsonEncoder()],
                )
            );
    }
}
