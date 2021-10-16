<?php

declare(strict_types=1);

namespace Plugin\StereoToolPlugin\Controller\Station;

use App\Doctrine\ReloadableEntityManagerInterface;
use App\Http\Response;
use App\Http\ServerRequest;
use App\Session\Flash;
use DI\FactoryInterface;
use Plugin\StereoToolPlugin\Entity\Repository\StationStereoToolRepository;
use Plugin\StereoToolPlugin\Entity\StationStereoTool;
use Plugin\StereoToolPlugin\Form\StationStereoToolForm;
use Plugin\StereoToolPlugin\Radio\StereoTool\StereoTool;
use Psr\Http\Message\ResponseInterface;

class StereoToolController
{
    protected string $csrf_namespace = 'stations_stereo_tool';

    public function __invoke(
        ServerRequest $request,
        Response $response,
        StereoTool $stereoTool,
        FactoryInterface $factory,
        StationStereoToolRepository $stationStereoToolRepository,
        ReloadableEntityManagerInterface $entityManager
    ): ResponseInterface {
        if ($stereoTool->getVersion() === null) {
            $station = $request->getStation();

            return $response->withRedirect(
                (string)$request->getRouter()->named(
                    'stereo-tool-plugin:admin:install_stereo_tool:index',
                    ['station_id' => $station->getId()]
                )
            );
        }

        $form = $factory->make(StationStereoToolForm::class);

        $this->updateStationStereoToolSettings(
            $request,
            $form,
            $stationStereoToolRepository,
            $entityManager,
            $stereoTool
        );

        return $request->getView()->renderToResponse(
            $response,
            'stereo-tool-plugin::station/stereo_tool/index',
            [
                'form' => $form,
                'csrf' => $request->getCsrf()->generate($this->csrf_namespace),
            ]
        );
    }

    protected function updateStationStereoToolSettings(
        ServerRequest $request,
        StationStereoToolForm $form,
        StationStereoToolRepository $stationStereoToolRepository,
        ReloadableEntityManagerInterface $entityManager,
        StereoTool $stereoTool
    ): void {
        if (!$form->isValid($request)) {
            return;
        }

        $station = $request->getStation();
        $data = $form->getValues();

        $stationStereoTool = $stationStereoToolRepository->fetchStationStereoTool($station);
        if ($stationStereoTool === null) {
            $stationStereoTool = new StationStereoTool($station);
        }

        $stationStereoTool = $stationStereoToolRepository->fromArray($stationStereoTool, $data);

        $station->setNeedsRestart(true);

        $entityManager->persist($stationStereoTool);
        $entityManager->persist($station);
        $entityManager->flush();

        $stereoTool->writeConfiguration($station, $stationStereoTool);

        $request->getFlash()->addMessage(__('Changes saved.'), Flash::SUCCESS);
    }
}
