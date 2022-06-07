<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4-L',
    'orientation' => 'P',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
'default_font'=>'bangla',
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_header' => 9,
    'margin_footer' => 9,

	'tempDir'               => public_path('temp/'),
    'font_path' => storage_path('fonts/'),
    'font_data' => [
        'bangla' => [
            'R'  => 'Siyamrupali.ttf',    // regular font
            'B'  => 'Siyamrupali.ttf',       // optional: bold font
            'I'  => 'Siyamrupali.ttf',     // optional: italic font
            'BI' => 'SolaimanLipi.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ]
        // ...add as many as you want.
    ]
];
