<?php

declare(strict_types=1);

namespace Plugin\StereoToolPlugin\Entity\Repository;

use App\Doctrine\ReloadableEntityManagerInterface;
use App\Entity;
use App\Doctrine\Repository;
use App\Environment;
use Plugin\StereoToolPlugin\Entity\StationStereoTool;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Serializer;

class StationStereoToolRepository extends Repository
{
    public function __construct(
        ReloadableEntityManagerInterface $entityManager,
        Serializer $serializer,
        Environment $environment,
        LoggerInterface $logger,
    ) {
        parent::__construct($entityManager, $serializer, $environment, $logger);
    }


    public function fetchStationStereoTool(Entity\Station $station): ?StationStereoTool
    {
        /** @var StationStereoTool|null $stationStereoTool */
        $stationStereoTool = $this->repository->findOneBy(['station' => $station]);

        return $stationStereoTool;
    }
}
