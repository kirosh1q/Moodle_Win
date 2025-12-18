<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Returns the main SCSS content.
 */
function theme_robotics_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_robotics', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    return $scss;
}

/**
 * Get SCSS to prepend.
 */
function theme_robotics_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        'brandcolor' => ['primary'],
    ];

    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Inject additional SCSS.
 */
function theme_robotics_get_extra_scss($theme) {
    $content = '';

    if (!empty($theme->settings->scss)) {
        $content .= $theme->settings->scss;
    }

    return $content;
}

/**
 * Get compiled css.
 */
function theme_robotics_get_precompiled_css() {
    global $CFG;
    return file_get_contents($CFG->dirroot . '/theme/robotics/style/moodle.css');
}

/**
 * Serves any files associated with the theme settings.
 */
function theme_robotics_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('robotics');
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}


function theme_robotics_get_string($identifier, $component = '', $a = null) {

    if ($component === 'block_myoverview' && $identifier === 'pluginname') {
        return 'Мои курсы';
    }

    return get_string($identifier, $component, $a);
}