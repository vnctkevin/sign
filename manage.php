<?php
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
 * Defines the version and other meta-info about the plugin
 *
 * @package     local_sign
 * @author      Valentino - Fakhri - Kevin - Sekar
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

$PAGE->set_url(new moodle_url('/local/sign/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Form');
$PAGE->navbar->add('Home', new moodle_url('/local/sign/index.php'));
$PAGE->navbar->add('Tambah Animasi', new moodle_url('/local/sign/add.php'));
$PAGE->navbar->add('Lihat Hasil', new moodle_url('/local/sign/manage.php'));

$curl = curl_init();

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

curl_setopt($curl, CURLOPT_URL, 'http://127.0.0.1:8000/get_subtitle'); 
$response = curl_exec($curl);
$subtitlelist = json_decode($response, true);


curl_setopt($curl, CURLOPT_URL, 'http://127.0.0.1:8000/get_running_gesture'); 
$response = curl_exec($curl);
$running = json_decode($response, true);

curl_setopt($curl, CURLOPT_URL, 'http://127.0.0.1:8000/get_successful_gesture');
$response = curl_exec($curl);
$success = json_decode($response, true);

curl_setopt($curl, CURLOPT_URL, 'http://127.0.0.1:8000/get_queued_gesture');
$response = curl_exec($curl);
$queued = json_decode($response, true);

curl_setopt($curl, CURLOPT_URL, 'http://127.0.0.1:8000/get_failure_gesture');
$response = curl_exec($curl);
$failure = json_decode($response, true);

curl_close($curl);

echo $OUTPUT->header();

$templatecontext = (object)[
    'editurl' => new moodle_url('/local/sign/add.php'),
    'subtitlelist' => $subtitlelist,
    'running' => $running,
    'success' => $success,
    'queued' => $queued,
    'failure' => $failure,
];

echo $OUTPUT->render_from_template('local_sign/manage', $templatecontext);

echo $OUTPUT->footer();