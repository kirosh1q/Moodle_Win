<?php
defined('MOODLE_INTERNAL') || die();

$THEME->name = 'robotics';

// Наследуем Boost
$THEME->parents = ['boost'];

// SCSS компиляция
$THEME->scss = function($theme) {
    return theme_robotics_get_main_scss_content($theme);
};

// Дополнительные стили
$THEME->sheets = [
    'custom',
    'moodle'
    ];

$THEME->editor_sheets = [];

$THEME->rendererfactory = 'theme_overridden_renderer_factory';

$THEME->enable_dock = false;
$THEME->yuicssmodules = [];

$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;

$THEME->haseditswitch = true;
$THEME->hidefromselector = false;
$THEME->usescourseindex = true;
$THEME->settings = [
    'enableaichat' => true
];

$THEME->layouts = [
    'base' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'standard' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'course' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'coursecategory' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'incourse' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],

    'frontpage' => [
        'file' => 'frontpage.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // My courses page.
    
    'mycourses' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'admin' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'mydashboard' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'mypublic' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'login' => [
        'file' => 'login.php',
        'regions' => [],
    ],
    'popup' => [
        'file' => 'columns1.php',
        'regions' => [],
    ],
    'frametop' => [
        'file' => 'columns1.php',
        'regions' => [],
    ],
    'embedded' => [
        'file' => 'embedded.php',
        'regions' => [],
    ],
    'maintenance' => [
        'file' => 'maintenance.php',
        'regions' => [],
    ],
    'print' => [
        'file' => 'columns1.php',
        'regions' => [],
    ],
    'redirect' => [
        'file' => 'embedded.php',
        'regions' => [],
    ],
    'report' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    'secure' => [
        'file' => 'secure.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
];