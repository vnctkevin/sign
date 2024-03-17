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
require_once($CFG->dirroot . '/local/sign/classes/form/add.php');

$PAGE->set_url(new moodle_url('/local/sign/add.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Add');

$PAGE->navbar->add('Home', new moodle_url('/local/sign/index.php'));
$PAGE->navbar->add('Tambah Animasi', new moodle_url('/local/sign/add.php'));
$PAGE->navbar->add('Lihat Hasil', new moodle_url('/local/sign/manage.php'));

$mform = new add();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/sign/manage.php', 'Operation cancelled');
} else if ($fromform = $mform->get_data()) {
    
    if ($fromform->filesfrom == "Upload") {
        $content = $mform->get_file_content('userfile');
        $newfilename = $mform->get_new_filename('userfile');
        $fromform->filename = $newfilename;

        $shortpath = 'uploads/'. $newfilename;
        $longpath = $CFG->wwwroot . '/local/sign/uploads/' . rawurlencode($newfilename);
        $success = $mform->save_file('userfile', $shortpath, $override);
        $fromform->url = $longpath;
        }

    // Convert the form data to JSON

    $jsonData = json_encode($fromform);
    // Set the endpoint URL
    $endpointUrl = 'http://127.0.0.1:8000/add';

    // Set the request headers
    $headers = array(
        'Content-Type: application/json',
    );

    // Initialize cURL
    $ch = curl_init();
    

    //redirect($CFG->wwwroot . '/local/sign/manage.php', null, null, null);

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $endpointUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($ch);
    //sleep(1000);
    $message = json_decode($response, true)['message'];

    // Check for any cURL errors
    if (curl_errno($ch)) {
        // Handle the error
        $errorMessage = curl_error($ch);
        redirect($CFG->wwwroot . '/local/sign/manage.php', $errorMessage, null, \core\notification::ERROR);
    } else if ($message != "Request successful") {
        redirect($CFG->wwwroot . '/local/sign/manage.php', $message, null, \core\notification::ERROR);
    }

    // Close the cURL session
    curl_close($ch);

    redirect($CFG->wwwroot . '/local/sign/manage.php', $message, null, \core\notification::SUCCESS);
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();