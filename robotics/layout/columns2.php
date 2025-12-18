<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

defined('MOODLE_INTERNAL') || die();

global $USER, $PAGE;

require_once($CFG->libdir . '/behat/lib.php');

// проверка на админа
$isadmin = is_siteadmin($USER);

// тест местонахождения
$pagelayout = $PAGE->pagelayout;
$showroboticsnav = !in_array($pagelayout, ['login', 'popup', 'frametop', 'embedded', 'maintenance', 'print', 'redirect']);

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

// поддержка drawers
if (isloggedin()) {
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
    $blockdraweropen = true;
}

$extraclasses = [];
if ($showroboticsnav) {
    $extraclasses[] = 'has-robotics-nav';
}
$extraclasses[] = 'uses-drawers';
if ($blockdraweropen) {
    $extraclasses[] = 'drawer-open-block';
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

//  email юзера в usermenu
if (isloggedin() && !isguestuser()) {
    global $USER;
    $primarymenu['user']['useremail'] = $USER->email;
}

if (isset($primarymenu['moremenu']['nodecollection']['children'])) {
    $primarymenu['moremenu']['nodecollection']['children'] = array_filter(
        $primarymenu['moremenu']['nodecollection']['children'],
        function($item) {
            return !isset($item['key']) || $item['key'] !== 'mycourses';
        }
    );
    $primarymenu['moremenu']['nodecollection']['children'] = array_values($primarymenu['moremenu']['nodecollection']['children']);
}
if (isset($primarymenu['moremenu']['nodearray'])) {
    $primarymenu['moremenu']['nodearray'] = array_filter(
        $primarymenu['moremenu']['nodearray'],
        function($item) {
            return !isset($item['key']) || $item['key'] !== 'mycourses';
        }
    );
    $primarymenu['moremenu']['nodearray'] = array_values($primarymenu['moremenu']['nodearray']);
}

$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

// Подготовка данных для навигации Robotics
$currenturl = $PAGE->url->out(false);
$roboticsnav = [
    'show' => $showroboticsnav,
    'isadmin' => $isadmin,
    'items' => []
];

// активный пункт
function is_active_url($url, $currenturl) {
    $urlpath = parse_url($url, PHP_URL_PATH);
    $currentpath = parse_url($currenturl, PHP_URL_PATH);

    if ($urlpath === $currentpath) {
        return true;
    }
    
    // Специальные проверки для разных страниц
    if (strpos($currentpath, '/my/courses.php') !== false && strpos($urlpath, '/my/courses.php') !== false) {
        return true;
    }
    if (strpos($currentpath, '/calendar/') !== false && strpos($urlpath, '/calendar/') !== false) {
        return true;
    }
    if (strpos($currentpath, '/grade/') !== false && strpos($urlpath, '/grade/') !== false) {
        return true;
    }
    if (strpos($currentpath, '/user/profile.php') !== false && strpos($urlpath, '/user/profile.php') !== false) {
        return true;
    }
    if (strpos($currentpath, '/admin/user.php') !== false && strpos($urlpath, '/admin/user.php') !== false) {
        return true;
    }
    if (strpos($currentpath, '/course/management.php') !== false && strpos($urlpath, '/course/management.php') !== false) {
        return true;
    }
    
    return false;
}

if ($isadmin) {
    $roboticsnav['items'] = [
        [
            'url' => (new moodle_url('/my/courses.php'))->out(false),
            'text' => 'Мои курсы',
            'icon' => 'fa-book',
            'active' => is_active_url((new moodle_url('/my/courses.php'))->out(false), $currenturl)
        ],
        [
            'url' => (new moodle_url('/admin/user.php'))->out(false),
            'text' => 'Управление аккаунтами',
            'icon' => 'fa-users',
            'active' => is_active_url((new moodle_url('/admin/user.php'))->out(false), $currenturl)
        ],
        [
            'url' => (new moodle_url('/course/management.php'))->out(false),
            'text' => 'Управление курсами',
            'icon' => 'fa-graduation-cap',
            'active' => is_active_url((new moodle_url('/course/management.php'))->out(false), $currenturl)
        ],
        [
            'url' => (new moodle_url('/user/profile.php', ['id' => $USER->id]))->out(false),
            'text' => 'Профиль',
            'icon' => 'fa-user',
            'active' => is_active_url((new moodle_url('/user/profile.php', ['id' => $USER->id]))->out(false), $currenturl)
        ]
    ];
} else {
    $roboticsnav['items'] = [
        [
            'url' => (new moodle_url('/my/courses.php'))->out(false),
            'text' => 'Мои курсы',
            'icon' => 'fa-book',
            'active' => is_active_url((new moodle_url('/my/courses.php'))->out(false), $currenturl)
        ],
        [
            'url' => (new moodle_url('/calendar/view.php', ['view' => 'month']))->out(false),
            'text' => 'Расписание',
            'icon' => 'fa-calendar',
            'active' => is_active_url((new moodle_url('/calendar/view.php', ['view' => 'month']))->out(false), $currenturl)
        ],
        [
            'url' => (new moodle_url('/grade/report/overview/index.php'))->out(false),
            'text' => 'Оценки',
            'icon' => 'fa-star',
            'active' => is_active_url((new moodle_url('/grade/report/overview/index.php'))->out(false), $currenturl)
        ],
        [
            'url' => (new moodle_url('/user/profile.php', ['id' => $USER->id]))->out(false),
            'text' => 'Профиль',
            'icon' => 'fa-user',
            'active' => is_active_url((new moodle_url('/user/profile.php', ['id' => $USER->id]))->out(false), $currenturl)
        ]
    ];
}

$showaichat = ($PAGE->pagelayout !== 'login' && $PAGE->pagelayout !== 'popup');

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'roboticsnav' => $roboticsnav,
    'blockdraweropen' => $blockdraweropen,
    'forceblockdraweropen' => $forceblockdraweropen,
    'showaichat' => $showaichat,
];

echo $OUTPUT->render_from_template('theme_robotics/columns2', $templatecontext);