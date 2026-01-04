<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\State\CategoryCollectionProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/category',
            paginationEnabled: false,
            normalizationContext: ['groups' => [self::OUTPUT]],
            provider: CategoryCollectionProvider::class,
        ),
    ],
)]
class CategoryResource
{
    private const string OUTPUT = 'Output';

    #[Groups([self::OUTPUT])]
    #[ApiProperty(identifier: true)]
    public int $id;

    #[Groups([self::OUTPUT])]
    public string $name;
}
