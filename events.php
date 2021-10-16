<?php

declare(strict_types=1);

use App\Acl;
use App\Event;
use App\Middleware;
use Azura\SlimCallableEventDispatcher\SlimCallableEventDispatcher;
use Plugin\StereoToolPlugin\Constants\AclConstants;
use Plugin\StereoToolPlugin\Controller;
use Plugin\StereoToolPlugin\EventListener;
use Slim\Routing\RouteCollectorProxy;

return function (SlimCallableEventDispatcher $dispatcher)
{
    // Add Migrations to the Doctrine migration_paths
    $dispatcher->addListener(Event\BuildMigrationConfigurationArray::class, function (Event\BuildMigrationConfigurationArray $event) {
        $migrationConfigurations = $event->getMigrationConfigurations();
        $baseDir = $event->getBaseDir();

        $migrationConfigurations['migrations_paths']['Plugin\StereoToolPlugin\Entity\Migration'] = $baseDir . '/plugins/StereoToolPlugin/src/Entity/Migration';

        $event->setMigrationConfigurations($migrationConfigurations);
    });

    // Add Entities to the Doctrine mapping paths
    $dispatcher->addListener(Event\BuildDoctrineMappingPaths::class, function (Event\BuildDoctrineMappingPaths $event) {
        $mappingClassesPaths = $event->getMappingClassesPaths();
        $baseDir = $event->getBaseDir();

        $mappingClassesPaths[] = $baseDir . '/plugins/StereoToolPlugin/src/Entity';

        $event->setMappingClassesPaths($mappingClassesPaths);
    });

    // Tell the view handler to look for templates in this directory too
    $dispatcher->addListener(Event\BuildView::class, function(Event\BuildView $event) {
        $event->getView()->addFolder('stereo-tool-plugin', __DIR__.'/templates');
    });

    $dispatcher->addListener(Event\BuildPermissions::class, function(Event\BuildPermissions $event) {
        $permissions = $event->getPermissions();

        $permissions['station'][AclConstants::STATION_STEREO_TOOL] = __('Manage Station Stereo Tool Settings');

        $event->setPermissions($permissions);
    });

    // Add a new route handled exclusively by the plugin.
    $dispatcher->addListener(Event\BuildRoutes::class, function(Event\BuildRoutes $event) {
        $app = $event->getApp();

        $app->group(
            '/admin',
            function (RouteCollectorProxy $group) {
                $group->group(
                    '/install/stereo_tool',
                    function (RouteCollectorProxy $group) {
                        $group->map(['GET', 'POST'], '', Controller\Admin\StereoToolController::class)
                            ->setName('stereo-tool-plugin:admin:install_stereo_tool:index');
                    }
                )->add(new Middleware\Permissions(Acl::GLOBAL_ALL));
            }
        )
            ->add(Middleware\Module\Admin::class)
            ->add(Middleware\EnableView::class)
            ->add(new Middleware\Permissions(Acl::GLOBAL_VIEW))
            ->add(Middleware\RequireLogin::class)
            ->add(Middleware\Auth\StandardAuth::class);

        $app->group(
            '/station/{station_id}',
            function (RouteCollectorProxy $group) {
                $group->map(['GET', 'POST'], '/stereo_tool', Controller\Station\StereoToolController::class)
                    ->setName('stereo-tool-plugin:station:stereo_tool:index');
            }
        )
            ->add(Middleware\Module\Stations::class)
            ->add(new Middleware\Permissions(AclConstants::STATION_STEREO_TOOL, true))
            ->add(new Middleware\Permissions(Acl::STATION_VIEW, true))
            ->add(Middleware\RequireStation::class)
            ->add(Middleware\GetStation::class)
            ->add(Middleware\EnableView::class)
            ->add(Middleware\RequireLogin::class)
            ->add(Middleware\Auth\StandardAuth::class);
    });

    $dispatcher->addListener(Event\BuildAdminMenu::class, function(Event\BuildAdminMenu $event) {
        $router = $event->getRequest()->getRouter();

        $event->merge(
            [
                'stations' => [
                    'items' => [
                        'stereo_tool' => [
                            'label' => __('Install Stereo Tool'),
                            'url' => (string)$router->named('stereo-tool-plugin:admin:install_stereo_tool:index'),
                            'permission' => Acl::GLOBAL_ALL,
                        ]
                    ]
                ],
            ]
        );
    });

    $dispatcher->addListener(Event\BuildStationMenu::class, function(Event\BuildStationMenu $event) {
        $router = $event->getRequest()->getRouter();
        $station = $event->getStation();

        $event->merge(
            [
                'stereo_tool' => [
                    'label' => __('Stereo Tool'),
                    'icon' => 'speaker',
                    'url' => (string)$router->named(
                        'stereo-tool-plugin:station:stereo_tool:index',
                        ['station_id' => $station->getIdRequired()]
                    ),
                    'permission' => AclConstants::STATION_STEREO_TOOL,
                ],
            ]
        );
    });

    $dispatcher->addServiceSubscriber(EventListener\StereoToolLiquidsoapConfig::class);
};
