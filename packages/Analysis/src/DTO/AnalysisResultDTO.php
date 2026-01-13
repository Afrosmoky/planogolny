<?php

namespace Planogolny\Analysis\DTO;

final readonly class AnalysisResultDTO
{
    public function __construct(
        /** @var CategoryScoreDTO[] */
        public array $categories,
        public array $raw = []
    ) {}
}
