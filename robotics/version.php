<?php
defined('MOODLE_INTERNAL') || die();

$plugin->component = 'theme_robotics';
$plugin->version   = 2025121100;

// Moodle 5.0+ (подойдёт и для 5.0.4)
$plugin->requires  = 2024100700;

$plugin->dependencies = [
    'theme_boost' => ANY_VERSION,
];