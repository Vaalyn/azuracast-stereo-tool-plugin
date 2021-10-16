<?php

declare(strict_types=1);

namespace Plugin\StereoToolPlugin\Form;

use App\Form\EntityForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StationStereoToolForm extends EntityForm
{
    public function __construct(
        EntityManagerInterface $em,
        Serializer $serializer,
        ValidatorInterface $validator
    ) {
        $formConfig = require (__DIR__ . '/../../config/forms/station_stereo_tool.php');

        parent::__construct($em, $serializer, $validator, $formConfig);
    }
}
