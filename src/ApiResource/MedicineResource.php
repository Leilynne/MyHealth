<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\ApiResource\Filter\NameFilter;
use App\State\MedicineCollectionProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '',
            paginationEnabled: false,
            normalizationContext: ['groups' => [self::OUTPUT]],
            filters: [
                NameFilter::class,
            ],
            provider: MedicineCollectionProvider::class,
        ),
    ],
    routePrefix: '/medicine',
)]
class MedicineResource
{
    private const string OUTPUT = 'Output';

    #[Groups([self::OUTPUT])]
    public int $medicineId;

    #[Groups([self::OUTPUT])]
    public int $dose;

    #[Groups([self::OUTPUT])]
    public string $name;

    #[Groups([self::OUTPUT])]
    public string $description;
}
