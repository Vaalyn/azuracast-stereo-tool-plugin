<?php

declare(strict_types=1);

namespace Plugin\StereoToolPlugin\Radio\StereoTool;

use App\Entity;
use Plugin\StereoToolPlugin\Entity\StationStereoTool;
use Symfony\Component\Process\Process;

class StereoTool
{
    public function getVersion(): ?string
    {
        $binaryPath = $this->getBinary();
        if (!$binaryPath) {
            return null;
        }

        $process = new Process([$binaryPath, '--help']);
        $process->setWorkingDirectory(dirname($binaryPath));
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        $outputLines = explode(PHP_EOL, $process->getErrorOutput());
        $version = explode(' - ', $outputLines[2])[0];

        return $version;
    }

    /**
     * @inheritDoc
     */
    public function getBinary(): ?string
    {
        $binaryPath = '/var/azuracast/servers/stereo_tool/stereo_tool_cmd_64';

        return file_exists($binaryPath)
            ? $binaryPath
            : null;
    }

    public function getConfigurationPath(Entity\Station $station): string
    {
        return $station->getRadioConfigDir() . '/radio.sts';
    }

    public function writeConfiguration(
        Entity\Station $station,
        StationStereoTool $stationStereoTool
    ): void {
        $configPath = $this->getConfigurationPath($station);
        $config = $stationStereoTool->getStereoToolConfiguration();

        file_put_contents($configPath, $config);
    }
}
