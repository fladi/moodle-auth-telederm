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
 * Strings for component 'auth_telederm', language 'en', branch 'MOODLE_24_STABLE'
 *
 * @package   auth_telederm
 * @copyright 2014 Michael Fladischer <michael.fladischer@medunigraz.at>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_teledermdescription'] = 'This method uses the telederm webservice to determine valid user credentials.';
$string['auth_teledermnotinstalled'] = 'Cannot use telederm authentication. The PHP curl module is not installed.';
$string['auth_teledermlogin'] = 'The login webservice URL to POST authentication data to.';
$string['auth_teledermlogin_key'] = 'Webservice login URL';
$string['auth_teledermmetadata'] = 'The user metadata URL to POST for additional user information.';
$string['auth_teledermmetadata_key'] = 'Webservice user metadata URL';
$string['auth_teledermverify'] = 'If enabled, all calls to the webservice will verify the certificate used in the HTTPS connection.';
$string['auth_teledermverify_key'] = 'Verify HTTPS peer certificate';
$string['auth_teledermauthenticate'] = 'If enabled, all calls to the webservice will use HTTP basic authentication.';
$string['auth_teledermauthenticate_key'] = 'Use HTTP basic authentication';
$string['auth_teledermusername'] = 'The username used for HTTP basic authentication.';
$string['auth_teledermusername_key'] = 'URL Username';
$string['auth_teledermpassword'] = 'The password used for HTTP basic authentication.';
$string['auth_teledermpassword_key'] = 'URL password';
$string['auth_teledermguid'] = 'The client GUID to identify the client or whom users should be authenticated.';
$string['auth_teledermguid_key'] = 'Client GUID';
$string['auth_teledermkey'] = 'The developer key to use in each webservice request.';
$string['auth_teledermkey_key'] = 'Developer key';
$string['auth_teledermnamespace'] = 'The XML namespace in which to look for the &lt;result&gt; element.';
$string['auth_teledermnamespace_key'] = 'Result XML namespace';
$string['pluginname'] = 'TeleDerm authentication';
