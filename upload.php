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
$PAGE->set_url(new moodle_url('/local/sign/upload.php'));
$PAGE->set_context(\context_system::instance());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $targetDir = 'uploads/'; // Specify the target directory where the file will be stored
    $targetFile = $targetDir . basename($_FILES['file']['name']);
    
    // Check if the file has been successfully uploaded
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        echo 'File has been uploaded successfully.';
    } else {
        echo 'Failed to upload file.';
    }
} else {
    echo 'Invalid request.';
}
?>