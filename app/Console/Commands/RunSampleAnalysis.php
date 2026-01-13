<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Planogolny\Analysis\Actions\PrepareAnalysisInputAction;
use Planogolny\Analysis\Actions\RunAnalysisAction;
use Planogolny\Analysis\Samples\SampleAnalysisInputFactory;
use Planogolny\GIS\DTO\CoordinatesDTO;
use Planogolny\GIS\Services\GISFacade;

class RunSampleAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analysis:sample
        {case=suburban : suburban|urban|rural}
        {--gis : Dump GIS aggregation only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run analysis using sample input';

    public function handle(
        PrepareAnalysisInputAction $prepare,
        RunAnalysisAction $run
    ): int {
        // punkt testowy – Piaseczno (domy jednorodzinne)
        $lat = 52.097;
        $lon = 21.026;

        $input = $prepare->execute($lat, $lon);
        $result = $run->execute($input);

        $this->info('Analysis result:');
        $this->line(json_encode($result, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }

    /**
     * Execute the console command.
     */
    public function __handle(RunAnalysisAction $action, GISFacade $gis): int
    {
        if ($this->option('gis')) {
            $coords = new CoordinatesDTO(
                lat: 52.097,
                lon: 21.026
            );

            $data = $gis->fetchAllData($coords);

            $this->info('GIS aggregation result:');
            $this->line(json_encode(
                $data['surroundings'],
                JSON_PRETTY_PRINT
            ));

            return self::SUCCESS;
        }

        $case = $this->argument('case');

        $input = match ($case) {
            'urban'    => SampleAnalysisInputFactory::urbanParcel(),
            'rural'    => SampleAnalysisInputFactory::ruralParcel(),
            default    => SampleAnalysisInputFactory::suburbanParcel(),
        };

        $result = $action->execute($input);

        $this->info("Analysis completed for case: {$case}");
        $this->line(json_encode($result, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
