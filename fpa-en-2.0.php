<?php
/**
 * @package FPA - Forum Post Assistant v.2
 * @subpackage FPA for Joomla!
 * @version 2.0.0-alpha
 * @author PhilD
 * @author Mandville
 * @author Bernard Toplak, Joomla VEL Team <bernard.toplak@vel.joomla.org>
 *
 * @copyright (c) 2014, Joomla VEL Team
 *
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License, version 3 (GPL-3.0)
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 3 of the License, or (at your option) any later
 * version.
 * * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 */

# PHP Version Test
if (version_compare(PHP_VERSION, '5.2.11', '<')) // because RecursiveDirectoryIterator::FOLLOW_SYMLINKS
    die( 'You are using PHP Version: '. PHP_VERSION
        .'. You have to deploy at least PHP 5.2.11 to be able to use this script!');

# General constants
define('SCRIPT', 'Forum Post Assistant (FPA) 2 for Joomla!');
define('VERSION', '2.0.0-alpha');
define('SUPPORT_URL', 'http://github.com/FPA/');
define('NL', '<br />');

# What path to scan
$pathToScan = '.';


/**
 * JInspector class contains code for reconnaissance of current Joomla installation
 *
 * @author Bernard Toplak, Joomla VEL Team <bernard.toplak@vel.joomla.org>
 *
 *
 */
class JInspector {

    var $pathToScan;


    function __construct($pathToScan) {
        $this->pathToScan = $pathToScan;
    }



    /**
     * Check if configuration file is writable
     */
    function checkConfigWrittable()
    {
        $writable = (
                is_writable( 'configuration.php' )
		|| (!file_exists( 'configuration.php' )
                && is_writable($this->pathToScan))
                );
    }




}


/**
 * PhpServInspector class contains PHP and Server environment reconnaissance code
 *
 * @author Bernard Toplak, Joomla VEL Team <bernard.toplak@vel.joomla.org>
 *
 *
 */
class PhpServInspector {

    /**
     * @var array PHP functions to check if they exist
     */
    private $functions = array( 'curl_init', 'zip_open', 'zip_read' );

    /**
     * @var array PHP settings to check values
     */
    private $settings_check = array
        (
        'safe_mode' => FALSE,
        'allow_url_fopen' => TRUE,
        'allow_url_include' => FALSE,
        'magic_quotes_gpc' => FALSE,
        'register_globals' => FALSE,
        'mbstring.language' => 'neutral',
        'mbstring.func_overload' => FALSE,
        'display_errors' =>FALSE,
        'file_uploads' => TRUE,
        'magic_quotes_runtime' => FALSE,
        'output_buffering' => FALSE,
        'session.auto_start' => FALSE,
        );

    /**
     * @var array PHP settings to get values for
     */
    private $settings_getvals = array
        ( 'session.save_path', 'file_uploads', 'upload_max_filesize', 'post_max_size',
          'max_input_time', 'max_execution_time', 'memory_limit', 'disable_functions'
        );

    /**
     * @var array PHP extensions to check if they exist
     */
    private $extensions = array( 'zlib', 'xml', 'mbstring', 'json' );


    /**
     * Checks if each PHP function on list exists
     *
     * @return array Returns array of functions as keys and boolean values
     */
    function checkFunctionsExist()
    {
        foreach ($this->functions as $function_name)
        {
            $function_list = array();
            $function_list[$function_name] = function_exists($function_name);
        }
        return $function_list;
    }


    /**
     * Check PHP settings and compare them to expected ones
     *
     * @return array Returns array of PHP settings with values and info if they passed
     */
    function checkSettings()
    {
        foreach ($this->settings_check As $setting_name => $expected)
        {
            $settings_checklist = array();
            $settings_checklist[$setting_name]['val'] = ini_get($setting_name);
             # check if value is as expected
            $settings_checklist[$setting_name]['passed'] =
                ($settings_checklist[$setting_name]['val'] == $expected)
                    ? TRUE : FALSE;
        }
        return $settings_checklist;
    }


    /**
     * Get PHP settings values
     *
     * @return array Returns array of PHP settings with values
     */
    function getSettings()
    {
        foreach ($this->settings_getvals As $setting_name)
        {
            $settings_values = array();
            $settings_values[$setting_name] = ini_get($setting_name);
        }
        return $settings_values;
    }


    /**
     *
     * @return array Returns list of extensions and boolean value if they are loaded
     */
    function checkExtensions()
    {
        foreach ($this->extensions As $extension)
        {
            $extension_list = array();
            $extension_list[$extension] = extension_loaded($extension);
        }
        return $extension_list;
    }


    /**
     *
     */
    function checkPhpVersion()
    {

    }


    /**
     * Checks the availability of the parse_ini_file and parse_ini_string functions.
     *
     * @return      boolean  True if the method exists
     * @author      Joomla! 3.1 Installer
     * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
     * @license     GNU General Public License version 2 or later;
     */
    function getIniParserAvailability()
    {
        $disabled_functions = ini_get('disable_functions');

        if (!empty($disabled_functions))
        {
                // Attempt to detect them in the disable_functions black list
                $disabled_functions = explode(',', trim($disabled_functions));
                $number_of_disabled_functions = count($disabled_functions);

                for ($i = 0; $i < $number_of_disabled_functions; $i++)
                {
                        $disabled_functions[$i] = trim($disabled_functions[$i]);
                }

                $result = !in_array('parse_ini_string', $disabled_functions);
        }
        else
        {
                // Attempt to detect their existence; even pure PHP implementation of them will trigger a positive response, though.
                $result = function_exists('parse_ini_string');
        }

        return $result;
    }


    /**
     * @todo
     *
     * @param type $session_save_path
     * @return type Description
     */
    function checkSessionPathWrittable($session_save_path) {


    }



}


/**
 * FPA class contains general methods for report generation and GUI
 *
 * @author Bernard Toplak, Joomla VEL Team <bernard.toplak@vel.joomla.org>
 *
 * 
 */
class FPA {

}