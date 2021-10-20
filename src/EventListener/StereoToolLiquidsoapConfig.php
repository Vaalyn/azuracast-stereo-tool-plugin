<?php

declare(strict_types=1);

namespace Plugin\StereoToolPlugin\EventListener;

use App\Event;
use Plugin\StereoToolPlugin\Entity\Repository\StationStereoToolRepository;
use Plugin\StereoToolPlugin\Radio\StereoTool\StereoTool;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StereoToolLiquidsoapConfig implements EventSubscriberInterface
{
    public function __construct(
        protected StationStereoToolRepository $stationStereoToolRepository,
        protected StereoTool $stereoTool
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            Event\Radio\WriteLiquidsoapConfiguration::class => [
                ['writeStereToolConfiguration', 6]
            ],
        ];
    }

    public function writeStereToolConfiguration(
        Event\Radio\WriteLiquidsoapConfiguration $event
    ): Event\Radio\WriteLiquidsoapConfiguration {
        $station = $event->getStation();

        $stationStereoTool = $this->stationStereoToolRepository->fetchStationStereoTool($station);

        if ($stationStereoTool === null || !$stationStereoTool->getEnableStereoTool()) {
            return $event;
        }

        $stereoToolPath = $this->stereoTool->getBinary();
        $stereoToolConfigurationPath = $this->stereoTool->getConfigurationPath($station);

        $stereoToolLicenseString = '';
        if (!empty($stationStereoTool->getLicenseKey())) {
            $stereoToolLicenseString = sprintf(' -k "%s"', $stationStereoTool->getLicenseKey());
        }

        $stereoToolLiquidsoapProcessing = sprintf(
            'radio = mksafe(pipe(replay_delay=1.0, process=\'%s - - -s %s -q%s\', radio))',
            $stereoToolPath,
            $stereoToolConfigurationPath,
            $stereoToolLicenseString
        );

        $event->appendLines(
            [
                '# Stereo Tool Integration',
                $stereoToolLiquidsoapProcessing,
            ]
        );

        $this->stereoTool->writeConfiguration($station, $stationStereoTool);

        return $event;
    }
}
