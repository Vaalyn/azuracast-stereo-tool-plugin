<?php

declare(strict_types=1);

namespace Plugin\StereoToolPlugin\Form;

use App\Entity;
use App\Environment;
use App\Form\AbstractSettingsForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StereoToolSettingsForm extends AbstractSettingsForm
{
    public function __construct(
        EntityManagerInterface $em,
        Serializer $serializer,
        ValidatorInterface $validator,
        Entity\Repository\SettingsRepository $settingsRepo,
        Environment $environment,
    ) {
        $formConfig = require (__DIR__ . '/../../config/forms/install_stereo_tool.php');

        parent::__construct($settingsRepo, $environment, $em, $serializer, $validator, $formConfig);
    }
}
