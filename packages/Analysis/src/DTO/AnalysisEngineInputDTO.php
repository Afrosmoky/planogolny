<?php
namespace Planogolny\Analysis\DTO;

use Planogolny\Analysis\ValueObjects\BBox;

final readonly class AnalysisEngineInputDTO
{
    public function __construct(
        public float $latitude,
        public float $longitude,

        public BBox $bbox200m,
        public BBox $bbox100m,
        public BBox $bbox50m,

        public SurroundingsSnapshotDTO $surroundings200m,
        public SurroundingsSnapshotDTO $surroundings100m,
        public SurroundingsSnapshotDTO $surroundings50m,

        public ConstraintsDTO $constraints,
        public InfrastructureDTO $infrastructure,
        public ?DemographyDTO $demography,

        public bool $hasPlanGeneral,
        public bool $hasMpzp,
    ) {}
}
