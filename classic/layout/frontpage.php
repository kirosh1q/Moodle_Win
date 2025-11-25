<?php
//
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The frontpage layout for the classic theme.
 * Forces login redirect for non-authenticated users.
 *
 * @package   theme_classic
 * @copyright 2024
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Force login - redirect to login page if not logged in (no guest access)
// This check happens early in the layout rendering
// Note: This runs after require_course_login, so we need to check for guest users too
global $USER;
if (!isloggedin() || isguestuser($USER)) {
    // Use output buffering to prevent any output before redirect
    if (!headers_sent()) {
        $loginurl = new moodle_url('/login/index.php');
        redirect($loginurl);
    } else {
        // Fallback: JavaScript redirect if headers already sent
        $loginurl = new moodle_url('/login/index.php');
        echo '<script>window.location.href = "' . $loginurl->out(false) . '";</script>';
        die();
    }
}

// If we get here, user is logged in, use standard columns layout
$bodyattributes = $OUTPUT->body_attributes();
$blockspre = $OUTPUT->blocks('side-pre');
$blockspost = $OUTPUT->blocks('side-post');

$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
$PAGE->set_secondary_navigation(false);
$renderer = $PAGE->get_renderer('core');
$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockspre,
    'sidepostblocks' => $blockspost,
    'haspreblocks' => $hassidepre,
    'haspostblocks' => $hassidepost,
    'bodyattributes' => $bodyattributes,
    'headercontent' => $headercontent,
];

echo $OUTPUT->render_from_template('theme_classic/columns', $templatecontext);

