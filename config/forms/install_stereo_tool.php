<?php
return [
    'method' => 'post',
    'enctype' => 'multipart/form-data',

    'groups' => [
        [
            'use_grid' => true,
            'elements' => [
                'details' => [
                    'markup',
                    [
                        'label' => __('Instructions'),
                        'markup' =>
                            '<p>' . __('You can upload the Stereo Tool Command line version in order to provide access to Stereo Tool the software-based audio processor which offers outstanding audio quality and comes with many unique features. This will allow you to add Stereo Tool processing to stations audio streams. To download the Stereo Tool Command line version:') . '</p>' .
                            '<ul>' .
                            '<li>' . __('Visit <a href="%s" target="_blank">the Stereo Tool download site</a>.',
                                'https://www.stereotool.com/download/') . '</li>' .
                            '<li>' . __('Download the "Linux 64 bit Command line version".') . '</li>' .
                            '<li>' . __('Select the "stereo_tool_cmd_64" file via the the button below.') . '</li>' .
                            '<li>' . __('Click on the upload button.') . '</li>' .
                            '<li>' . __('For production use you need to <a href="%s" target="_blank">buy a license for Stereo Tool</a> and configure it on the station you want to use it on.', 'https://www.stereotool.com/products/') . '</li>'
                            . '</ul>',
                        'form_group_class' => 'col-sm-12',
                    ],
                ],

                'current_version' => [
                    'markup',
                    [
                        'label' => __('Current Installed Version'),
                        'markup' => '<p class="text-danger">' . __('Stereo Tool is currently not installed on this installation.') . '</p>',
                        'form_group_class' => 'col-sm-12',
                    ],
                ],

                'binary' => [
                    'file',
                    [
                        'label' => __('Select "stereo_tool_cmd_64" File'),
                        'required' => true,
                        'type' => 'all',
                        'form_group_class' => 'col-md-6',
                        'button_text' => __('Select File'),
                        'button_icon' => 'cloud_upload',
                        'max_size' => 64 * 1024 * 1024,
                    ],
                ],

                'submit' => [
                    'submit',
                    [
                        'type' => 'submit',
                        'label' => __('Upload'),
                        'class' => 'ui-button btn-lg btn-primary',
                        'form_group_class' => 'col-sm-12',
                    ],
                ],
            ],
        ],
    ],
];
