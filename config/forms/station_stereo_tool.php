<?php
return [
    'groups' => [
        [
            'use_grid' => true,
            'elements' => [
                'enable_stereo_tool' => [
                    'toggle',
                    [
                        'label' => __('Enable Stereo Tool'),
                        'description' => __('Enable Stereo Tool audio processing for this station.'),
                        'selected_text' => __('Yes'),
                        'deselected_text' => __('No'),
                        'default' => false,
                        'form_group_class' => 'col-sm-12',
                    ],
                ],

                'license_key' => [
                    'text',
                    [
                        'label' => __('License Key'),
                        'form_group_class' => 'col-sm-12',
                        'maxLength' => 255,
                    ],
                ],

                'stereo_tool_configuration' => [
                    'textarea',
                    [
                        'label' => __('Stereo Tool Configuration'),
                        'description' => __('Put the content of your .sts file from Stereo Tool here.'),
                        'form_group_class' => 'col-sm-12',
                    ],
                ],

                'submit' => [
                    'submit',
                    [
                        'type' => 'submit',
                        'label' => __('Save Changes'),
                        'class' => 'ui-button btn-lg btn-primary',
                        'form_group_class' => 'col-sm-12',
                    ],
                ],
            ],
        ],
    ],
];
