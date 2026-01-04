<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\ApiResource\Filter\NameFilter;
use App\State\Medicine\MedicineCollectionProvider;
use App\State\Medicine\MedicineProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/medicine',
            paginationEnabled: false,
            normalizationContext: ['groups' => [self::OUTPUT]],
            filters: [
                NameFilter::class,
            ],
            provider: MedicineCollectionProvider::class,
        ),
        new Get(
            uriTemplate: '/medicine/{id}',
            normalizationContext: ['groups' => [self::OUTPUT]],
            provider: MedicineProvider::class,
        ),
    ],
)]
class MedicineResource
{
    private const string OUTPUT = 'Output';

    #[Groups([self::OUTPUT])]
    #[ApiProperty(identifier: true)]
    public int $id;

    #[Groups([self::OUTPUT])]
    public int $dose;

    #[Groups([self::OUTPUT])]
    public string $name;

    #[Groups([self::OUTPUT])]
    public ?string $description = null;
}
