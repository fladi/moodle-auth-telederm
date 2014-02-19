<?php

/**
 * @author Michael Fladischer <michael.fladischer@medunigraz.at>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package moodle multiauth
 *
 * Authentication Plugin: ANV Authentication
 *
 * Authenticates against an Austrian ANV webpage.
 *
 * 2013-02-07  File created.
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/authlib.php');

/**
 * ANV authentication plugin.
 */
class auth_plugin_telederm extends auth_plugin_base {

    /**
     * Constructor.
     */
    function auth_plugin_telederm() {
        $this->authtype = 'telederm';
        $this->config = get_config('auth/telederm');
        $this->config->field_updatelocal_email = false;
        $this->config->field_updatelocal_lastname = false;
        $this->config->field_updatelocal_firstname = false;
        $this->config->field_updatelocal_city = false;
        $this->config->field_updatelocal_country = false;
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username (with system magic quotes)
     * @param string $password The password (with system magic quotes)
     * @return bool Authentication success or failure.
     */
    function user_login ($username, $password) {
        if (! function_exists('curl_init')) {
            print_error('auth_teledermnotinstalled','mnet');
            return false;
        }

        $this->passwordhash = sha1($password);

        $xml = new SimpleXMLElement('<checkbenutzerviewws_checkuserisactiveonclient/>');
        $xml->addChild('clientguid', $this->config->guid);
        $xml->addChild('developerkey', $this->config->key);
        $xml->addChild('username', $username);
        $xml->addChild('passwordhash', $this->passwordhash);

        $repsonse = $this->_post($this->config->login, $xml->asXML());
        if (!$response) {
            return false;
        }

        $returnobject = simplexml_load_string($response);
        $returnobject->registerXPathNamespace('a', $this->config->namespace);

        return (string)$returnobject->xpath('//a:result')[0] == "true";
    }

    function prevent_local_passwords() {
        return true;
    }

    /**
     * Read user information from external database and returns it as array.
     *
     * @param string $username The username (with system magic quotes)
     * @return array
     */
    function get_userinfo($username) {
        $xml = new SimpleXMLElement('<checkbenutzerviewws_getuserdata/>');
        $xml->addChild('clientguid', $this->config->guid);
        $xml->addChild('developerkey', $this->config->key);
        $xml->addChild('username', $username);
        $xml->addChild('passwordhash', $this->passwordhash);

        $repsonse = $this->_post($this->config->metadata, $xml->asXML());
        //
        // Check if we were able to fetch user metadata.
        if (!$response) {
            // Return dummy data.
            return array(
                'email' => "{$username}@telederm.medunigraz.at",
                'lastname' => 'Telederm',
                'firstname' => $username,
                'city' => 'Graz',
                'country' => 'AT'
            );
        }

        $returnobject = simplexml_load_string($response);
        $returnobject->registerXPathNamespace('a', $this->config->namespace);

        return array(
            'email' => (string)$returnobject->xpath('//a:value/a:email')[0],
            'lastname' => (string)$returnobject->xpath('//a:value/a:lastname')[0],
            'firstname' => (string)$returnobject->xpath('//a:value/a:firstname')[0],
            'city' => 'Graz',
            'country' => 'AT'
        );

    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return false;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $page An object containing all the data for this page.
     */
    function config_form($config, $err, $user_fields) {
        global $OUTPUT;

        include "config.html";
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     */
    function process_config($config) {
        // set to defaults if undefined
        if (!isset ($config->login)) {
            $config->login = 'https://webservice.telederm.org/cderm/rest/cdermService.svc/checkbenutzerviewws_checkuserisactiveonclient';
        }
        if (!isset ($config->metadata)) {
            $config->metadata = 'https://webservice.telederm.org/cderm/rest/cdermService.svc/checkbenutzerviewws_getuserdata';
        }
        if (!isset ($config->verify)) {
            $config->verify = false;
        }
        if (!isset ($config->authenticate)) {
            $config->authenticate = false;
        }
        if (!isset ($config->username)) {
            $config->username = '';
        }
        if (!isset ($config->password)) {
            $config->password = '';
        }
        if (!isset ($config->guid)) {
            $config->guid = '';
        }
        if (!isset ($config->key)) {
            $config->key = '';
        }
        if (!isset ($config->namespace)) {
            $config->namespace = 'http://schemas.datacontract.org/2004/07/tootbox_net.commons.ViewWS';
        }

        // save settings
        set_config('login', $config->login, 'auth/telederm');
        set_config('metadata', $config->metadata, 'auth/telederm');
        set_config('verify', $config->verify, 'auth/telederm');
        set_config('authenticate', $config->authenticate, 'auth/telederm');
        set_config('username', $config->username, 'auth/telederm');
        set_config('password', $config->password, 'auth/telederm');
        set_config('guid', $config->guid, 'auth/telederm');
        set_config('key', $config->key, 'auth/telederm');
        set_config('namespace', $config->namespace, 'auth/telederm');

        return true;
    }

    /**
     * Handle webservice POST calls.
     *
     * @param string $url The URL to call.
     * @param string $data POST payload.
     * @return string The body of the webservice repsonse.
     */
    function _post($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->config->verify && substr($url, 0, 6) == 'https:');
        if ($this->config->authenticate) {
            curl_setopt($ch, CURLOPT_USERPWD, "{$this->config->username}:{$this->config->password}");
        }
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: text/xml', 'Content-length: '. strlen($data)
        ));
        $response = curl_exec($ch);
        if (curl_errno($ch) > 0) {
            return false;
        }
        return $response;
    }
}

