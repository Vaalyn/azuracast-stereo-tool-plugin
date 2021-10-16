<?php

declare(strict_types=1);

namespace Plugin\StereoToolPlugin\Controller\Admin;

use App\Environment;
use App\Http\Response;
use App\Http\ServerRequest;
use App\Session\Flash;
use DI\FactoryInterface;
use Exception;
use Plugin\StereoToolPlugin\Form\StereoToolSettingsForm;
use Plugin\StereoToolPlugin\Radio\StereoTool\StereoTool;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UploadedFileInterface;

class StereoToolController
{
    protected string $csrf_namespace = 'admin_install_stereo_tool';

    public function __invoke(
        ServerRequest $request,
        Response $response,
        FactoryInterface $factory,
        Environment $environment,
        StereoTool $stereoTool
    ): ResponseInterface {
        $form = $factory->make(StereoToolSettingsForm::class);

        $installationResponse = $this->installStereoTool($request, $response, $environment, $form);
        if ($installationResponse instanceof Response) {
            return $installationResponse;
        }

        $version = $stereoTool->getVersion();

        if (null !== $version) {
            $formOptions = $form->getOptions();
            $formOptions['groups'][0]['elements']['current_version'][1]['markup'] = '<p class="text-success">' . __(
                'Stereo Tool version "%s" is currently installed.',
                $version
            ) . '</p>';
            $form->configure($formOptions);
        }

        return $request->getView()->renderToResponse(
            $response,
            'stereo-tool-plugin::admin/install_stereo_tool/index',
            [
                'form' => $form,
                'title' => __('Install Stereo Tool'),
                'csrf' => $request->getCsrf()->generate($this->csrf_namespace),
            ]
        );
    }

    protected function installStereoTool(
        ServerRequest $request,
        Response $response,
        Environment $environment,
        StereoToolSettingsForm $form
    ): ?Response {
        if (!$form->isValid($request)) {
            return null;
        }

        try {
            $stereoToolBaseDir = $environment->getParentDirectory() . '/servers/stereo_tool';

            $values = $form->getValues();

            $importFile = $values['binary'] ?? null;
            if ($importFile instanceof UploadedFileInterface) {
                $stereoToolPath = $stereoToolBaseDir . '/stereo_tool_cmd_64';
                if (is_file($stereoToolPath)) {
                    unlink($stereoToolPath);
                }

                if (!mkdir($stereoToolBaseDir) && !is_dir($stereoToolBaseDir)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $stereoToolBaseDir));
                }

                $importFile->moveTo($stereoToolPath);
                chmod($stereoToolPath, 0755);
                chown($stereoToolPath, 'azuracast:azuracast');

                $request->getFlash()->addMessage(__('Stereo Tool installed.'), Flash::SUCCESS);
            }

            return $response->withRedirect($request->getUri()->getPath());
        } catch (Exception $exception) {
            $form
                ->getField('binary')
                ->addError(get_class($exception) . ': ' . $exception->getMessage());
        }

        return null;
    }
}
