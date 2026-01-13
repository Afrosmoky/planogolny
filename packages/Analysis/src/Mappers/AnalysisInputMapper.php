<?php

// packages/Analysis/src/Mappers/AnalysisInputMapper.php

namespace Planogolny\Analysis\Mappers;
use Planogolny\Analysis\DTO\AnalysisEngineInputDTO;
use Planogolny\Analysis\DTO\AnalysisInputDTO;

final class AnalysisInputMapper
{
    public static function map(
        AnalysisInputDTO $input,
        AnalysisEngineInputDTO $engineInput
    ): AnalysisEngineInputDTO {
        // tu:
        // - liczysz BBox
        // - rozbijasz surroundings na 50/100/200
        // - normalizujesz dane
        // - uzupełniasz braki

        return $engineInput;
    }
}
