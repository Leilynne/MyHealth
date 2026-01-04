<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Enum\TimeOfDayEnum;
use App\State\MedicationSchedule\MedicationScheduleAddProcessor;
use App\State\MedicationSchedule\MedicationScheduleCollectionProvider;
use App\State\MedicationSchedule\MedicationScheduleDeleteProcessor;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '',
            paginationEnabled: false,
            normalizationContext: ['groups' => [self::OUTPUT]],
            provider: MedicationScheduleCollectionProvider::class,
        ),
        new Post(
            uriTemplate: '',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            processor: MedicationScheduleAddProcessor::class,
        ),
        new Delete(
            uriTemplate: '/{id}',
            read: false,
            processor: MedicationScheduleDeleteProcessor::class,
        ),
    ],
    routePrefix: '/medication-schedule',
)]
class MedicationScheduleResource
{
    private const string OUTPUT = 'Output';
    private const string INPUT = 'Input';

    #[Groups([self::OUTPUT])]
    public int $medicationScheduleId;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Choice(callback: TimeOfDayEnum::class . '::stringCases')]
    public string $timeOfDay;

    #[Groups([self::INPUT, self::OUTPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $medicineId;

    #[Groups([self::OUTPUT])]
    public string $medicineName;
}
