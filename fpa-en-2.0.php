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
if (version_compare(PHP_VERSION, '5.2.11', '<')) { // because RecursiveDirectoryIterator::FOLLOW_SYMLINKS
die( 'You are using PHP Version: ' . PHP_VERSION . '. You have to deploy at least PHP 5.2.7 to be able to use this script!');
}


define('SCRIPT', 'Forum Post Assistant (FPA) 2 for Joomla!');
define('VERSION', '2.0.0-alpha');
define('SUPPORT_URL', 'http://github.com/FPA/');
define('NL', '<br />');

/**
 * JInspector class contains code for reconnaissance of current Joomla installation
 *
 */
class JInspector {


}


/**
 * PhpServInspector class contains PHP and Server environment reconnaissance code
 */
class PhpServInspector {

}

/**
 * FPA class contains general methods for report generation and GUI
 */
class FPA {

}