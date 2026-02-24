<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Planogolny\Analysis\DTO\AnalysisInputDTO;
use Planogolny\GIS\DTO\CoordinatesDTO;
use Planogolny\GIS\Facades\GISFacade;
use Planogolny\Reporting\Actions\BuildReportSampleAction;

class RunSampleAnalysis extends Command
{
    protected $signature = 'analysis:sample
        {lat : Latitude}
        {lon : Longitude}';

    protected $description = 'Run full analysis for given coordinates';

    public function handle(
        GISFacade $gis
    ): int {

        $lat = (float) $this->argument('lat');
        $lon = (float) $this->argument('lon');

        $coords = new CoordinatesDTO(
            lat: $lat,
            lon: $lon
        );

        $gisData = $gis->fetchAllData($coords);

        $this->info('GIS Data:');
        $this->line(json_encode($gisData, JSON_PRETTY_PRINT));

        $this->newLine();

        $data = app(BuildReportSampleAction::class)->execute(
            new AnalysisInputDTO(
                $gisData['surroundings'],
                $gisData['legalConstraints']
            )
        );

        $this->info('Analysis Result:');
        $this->newLine();

        $this->line(json_encode($data, JSON_PRETTY_PRINT));
        return self::SUCCESS;
    }
}
