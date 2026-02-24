<?php

declare(strict_types=1);

namespace Planogolny\Analysis\DTO;

final class LegalConstraintsDTO
{
    public function __construct(
        public ?float $waterDistance = null,
        public ?float $embankmentDistance = null,
        public ?float $railDistance = null,
        public ?float $motorwayDistance = null,
        public ?float $powerLineDistance = null,
        public ?float $cemeteryDistance = null,
        public bool $waterRestriction = false,
        public bool $embankmentRestriction = false,
        public bool $railRestriction = false,
        public bool $motorwayRestriction = false,
        public bool $powerLineRestriction = false,
        public bool $cemeteryRestriction = false
    ) {}

    private function safeFloat(?float $value): ?float
    {
        if ($value === null) {
            return null;
        }

        if (is_nan($value) || is_infinite($value)) {
            return null;
        }

        return $value;
    }

    public function toArray(): array
    {
        return [
            'waterDistance'       => $this->safeFloat($this->waterDistance),
            'embankmentDistance' => $this->safeFloat($this->embankmentDistance),
            'railDistance'        => $this->safeFloat($this->railDistance),
            'motorwayDistance'    => $this->safeFloat($this->motorwayDistance),
            'powerLineDistance'   => $this->safeFloat($this->powerLineDistance),
            'cemeteryDistance'    => $this->safeFloat($this->cemeteryDistance),

            'waterRestriction'       => $this->waterRestriction,
            'embankmentRestriction' => $this->embankmentRestriction,
            'railRestriction'        => $this->railRestriction,
            'motorwayRestriction'   => $this->motorwayRestriction,
            'powerLineRestriction'  => $this->powerLineRestriction,
            'cemeteryRestriction'   => $this->cemeteryRestriction,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            waterDistance: $data['waterDistance'] ?? null,
            embankmentDistance: $data['embankmentDistance'] ?? null,
            railDistance: $data['railDistance'] ?? null,
            motorwayDistance: $data['motorwayDistance'] ?? null,
            powerLineDistance: $data['powerLineDistance'] ?? null,
            cemeteryDistance: $data['cemeteryDistance'] ?? null,

            waterRestriction: (bool) ($data['waterRestriction'] ?? false),
            embankmentRestriction: (bool) ($data['embankmentRestriction'] ?? false),
            railRestriction: (bool) ($data['railRestriction'] ?? false),
            motorwayRestriction: (bool) ($data['motorwayRestriction'] ?? false),
            powerLineRestriction: (bool) ($data['powerLineRestriction'] ?? false),
            cemeteryRestriction: (bool) ($data['cemeteryRestriction'] ?? false),
        );
    }
}
