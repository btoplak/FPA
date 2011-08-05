<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" >


<?php
/**
 **  @package Forum Post Assistant / Bug Report Assistant
 **  @version 1.2.0
 **  @release playGround
 **  @date 24/06/2011
 **  @author RussW
 **/


    /** SET THE FPA DEFAULTS *****************************************************************/
    //define ( '_FPA_BRA', TRUE );  // bug-report-mode
    define ( '_FPA_DEV', TRUE );   // developer-mode
    //define ( '_FPA_DIAG', TRUE );  // diagnostic-mode


    // Define some basic assistant information
    if ( defined ( '_FPA_BRA' ) ) {
        define ( '_RES', 'Bug Report Assistant' );

    } else {
        define ( '_RES', 'Forum Post Assistant' );

    }

    define ( '_RES_VERSION', '1.2.0' );
    define ( '_RES_RELEASE', 'Alpha' ); // can be Alpha, Beta, RC, Final
    define ( '_RES_BRANCH', 'Development' ); // can be playGround, currentDevelopment, masterPublic
    define ( '_RES_FPALINK', 'https://github.com/ForumPostAssistant/FPA/archives/masterPublic' ); // where to get the latest 'Final release'
    define ( '_RES_FPALATEST', 'Get the latest release of the ' );



    // Display a "Processing" Message while the routines run
    echo '<div id="slowScreenSplash" style="padding:20px;border: 2px solid #4D8000;background-color:#FFFAF0;border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px;margin: 0 auto; margin-top:50px;margin-bottom:20px;width:700px;position:relative;z-index:9999;top:10%;" align="center">';
    echo '<h1>'. _RES .'</h1>';

    if ( @$_POST['doIT'] == 1 ) {
        echo '<h3 style="color:#4D8000;">Generating The Post Output For You</h3>';
    } else {
        echo '<h3 style="color:#4D8000;">Hang On In There While We Run Some Test Routines</h3>';

    }

    echo '<br />v'. _RES_VERSION .'-'. _RES_RELEASE .' ('. _RES_BRANCH .')';
    echo '</div>';



    // setup the default runtime parameters and collect the POST data changes, if any
    if ( @$_POST['showProtected'] ) {
        $showProtected  = @$_POST['showProtected'];

    } else {
        $showProtected = 2; // default (limited masking)

    }

    if ( @$_POST['showElevated'] == 1 ) {
        $showElevated  = 1;

    } else {
        $showElevated = 0; // default (hide)

    }

    if ( @$_POST['showTables'] == 1 ) {
        $showTables  = 1;

    } else {
        $showTables = 0; // default (hide)

    }

    if ( @$_POST['showComponents'] == 1 ) {
        $showComponents  = 1;

    } else {
        $showComponents = 0; // default (hide)

    }

    if ( @$_POST['showModules'] == 1 ) {
        $showModules  = 1;

    } else {
        $showModules = 0; // default (hide)

    }

    if ( @$_POST['showPlugins'] == 1 ) {
        $showPlugins  = 1;

    } else {
        $showPlugins = 0; // default (hide)

    }

    if ( @$_POST['postFormat'] == 1 ) {
        $postFormat  = 1;  // GitHUB
    } elseif ( @$_POST['postFormat'] == 2 ) {
        $postFormat  = 2;  // JoomlaCode
    } else {
        $postFormat = 3;   // Forum (default)

    }



    /** TIMER-POPS ***************************************************************************/
    // mt_get: returns the current microtime
    function mt_get(){
        global $mt_time;
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);

    }

    // mt_start: starts the microtime counter
    function mt_start(){
        global $mt_time; $mt_time = mt_get();

    }

    // mt_end: calculates the elapsed time
    function mt_end($len=3){
        global $mt_time;
        $time_end = mt_get();
        return round($time_end - $mt_time, $len);

    }

    // start the timer-pop
    mt_start();



    // build the initial arrays used throughout fpa
    if ( defined ( '_FPA_BRA' ) ) {
        $fpa['ARRNAME']         = 'Bug Report Assistant';
        $fpa['diagLOG']         = 'braDiag.log';

    } else {
        $fpa['ARRNAME']         = 'Forum Post Assistant';
        $fpa['diagLOG']         = 'fpaDiag.log';

    }

    $snapshot['ARRNAME']        = 'Environment Support Snapshot';
    $instance['ARRNAME']        = 'Application Instance';
    $system['ARRNAME']          = 'Systems Environment';
    $phpenv['ARRNAME']          = 'PHP Environment';
    $phpenv['phpLASTERR']       = '';
    $phpextensions['ARRNAME']   = 'PHP Extensions';
    $phpreq['ARRNAME']          = 'PHP Requirements';
    $phpreq['libxml']           = '';
    $phpreq['xml']              = '';
    $phpreq['zlib']             = '';
    $phpreq['zip']              = '';
    $phpreq['openssl']          = '';
    $phpreq['curl']             = '';
    $phpreq['iconv']            = '';
    $phpreq['mbstring']         = '';
    $phpreq['mysql']            = '';
    $phpreq['mysqli']           = '';
    $phpreq['mcrypt']           = '';
    $phpreq['suhosin']          = '';
//    $phpreq['test']             = '';
    $apachemodules['ARRNAME']   = 'Apache Modules';
    $apachereq['ARRNAME']       = 'Apache Requirements';
    $apachereq['mod_rewrite']   = '';
    $apachereq['mod_expires']   = '';
    $apachereq['mod_deflate']   = '';
    $apachereq['mod_security']  = '';
    $apachereq['mod_evasive']   = '';
    $apachereq['mod_dosevasive'] = '';
    $apachereq['mod_ssl']       = '';
    $apachereq['mod_qos']       = '';
    $apachereq[' mod_userdir']  = '';
//    $apachereq['test']          = '';
    $database['ARRNAME']        = 'dataBase Instance';
    $tables['ARRNAME']          = 'Table Structure';
    $modecheck['ARRNAME']       = 'Permissions Checks';
    // folders to be tested for permissions
    $folders['ARRNAME']         = 'Core Folders';
    $folders[]                  = 'images/';
    $folders[]                  = 'components/';
    $folders[]                  = 'modules/';
    $folders[]                  = 'plugins/';               // J!1.5 and above | either / or
    $folders[]                  = 'mambots/';               // J!1.0 only
    $folders[]                  = 'language/';
    $folders[]                  = 'templates/';
    $folders[]                  = 'cache/';
    $folders[]                  = 'logs/';
    $folders[]                  = 'tmp/';
    $folders[]                  = 'administrator/components/';
    $folders[]                  = 'administrator/modules/';
    $folders[]                  = 'administrator/language/';
    $folders[]                  = 'administrator/templates/';
    $folders[]                  = 'sites/';                 // nooku only?
//    $folders[]                  = 'test/';
    $elevated['ARRNAME']        = 'Elevated Permissions';
    $component['ARRNAME']       = 'Components';
    $module['ARRNAME']          = 'Modules';
    $plugin['ARRNAME']          = 'Plugins';
    $template['ARRNAME']        = 'Templates';
?>



<?php
    // build the developer-mode function to display the raw arrays
    function showDev( &$section ) {

        // this can only have inline styling because it is outputed before the html styling
        if ( defined( '_FPA_DEV' ) ) {
            echo '<div style="width:750px;margin: 0px auto;margin-bottom:10px;font-family:arial;font-size:10px;color:#808080;">';
            echo '<div style="text-shadow: 1px 1px 1px #F5F5F5;font-weight:bold;color:#4D8000;text-transform:uppercase;padding-bottom:2px;">';
            echo '<span style="color: #808080;font-weight:normal;text-transform:lowercase;">[developer-mode information]</span><br />';
            echo $section['ARRNAME'] .' Array :';
            echo '</div>';

            echo '<div style="-moz-box-shadow: inset -3px -3px 3px #CAE897;-webkit-box-shadow: inset -3px -3px 3px #CAE897;box-shadow: inset -3px -3px 3px #CAE897;padding:5px;background-color:#E2F4C4; border:1px solid #4D8000;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">';
                print_r ( $section );
            echo '<p><em>elapse runtime: <strong>'. mt_end() .'</strong> seconds</em></p>';
            echo '</div>';

            echo '</div>';
        } // end if _FPA_DEV defined

     } // end developer-mode function
?>



<?php
    /** DETERMINE SOME SETTINGS BEFORE FPA MIGHT PLAY WITH THEM ******************************/
    $phpenv['phpERRORDISPLAY']  = ini_get( 'display_errors' );
    $phpenv['phpERRORREPORT']   = ini_get( 'error_reporting' );
    $fpa['ORIGphpMEMLIMIT']     = ini_get( 'memory_limit' );
    $fpa['ORIGphpMAXEXECTIME']  = ini_get( 'max_execution_time' );
    $phpenv['phpERRLOGFILE']    = ini_get( 'error_log' );
    $system['sysSHORTOS']       = strtoupper( substr( PHP_OS, 0, 3 ) ); // WIN, DAR, LIN, SOL
    $system['sysSHORTWEB']      = strtoupper( substr( $_SERVER['SERVER_SOFTWARE'], 0, 3 ) ); // APA = Apache, MIC = MS IIS



    // if the user see's Out Of Memory or Execution Timer pops, double the current memory_limit and max_execution_time
    if ( @$_POST['increasePOPS'] == 1 ) {
        ini_set ( 'memory_limit', $fpa['ORIGphpMEMLIMIT']*2 );
        ini_set ( 'max_execution_time', $fpa['ORIGphpMAXEXECTIME']*2 );

    }



    /** DETERMINE IF THERE IS A KNOWN ERROR ALREADY *******************************************
     ** here we try and determine if there is an existing php error log file, if there is we
     ** then look to see how old it is, if it's less than one day old, lets see if what the last
     ** error this and try and auto-enter that as the problem description
     *****************************************************************************************/

    /** is there an existing php error-log file? *********************************************/
    if ( file_exists( $phpenv['phpERRLOGFILE'] ) ) {
        // when was the file last modified?
        $phpenv['phpLASTERRDATE'] = date ("dS F Y H:i:s.", filemtime( $phpenv['phpERRLOGFILE'] ));

        // determine the number of seconds for one day
        $age = 1 * 60*60*24;
        // get the modified time in seconds
        $file_time = filemtime( $phpenv['phpERRLOGFILE'] );
        // get the current time in seconds
        $now_time = time();

            // if the file was modified less than one day ago, grab the last error entry
            if ( $file_time - $now_time < $age ) {
                // !FIXME memory allocation error on large php_error file
                $phpenv['phpLASTERR'] = array_pop( file( $phpenv['phpERRLOGFILE'] ) );

            }

    }
?>



<?php
    /** SEEING A WHITE SCREEN WHILST RUNNING FPA? OR SOMEONE HELPING YOU SENT YOU HERE? *******
     ** uncomment _FPA_DIAG above and re-run FPA
     **
     ** display_errors, enables php errors to be displayed on the screen
     ** error_reporting, sets the level of errors to report, "-1" is all errors
     ** log_errors, enables errors to be logged to a file, fpa_error.log in the "/" folder
     *****************************************************************************************/

    if ( defined( '_FPA_DEV' ) OR defined( '_FPA_DIAG' ) ) {
        // these can only have inline styling because it is outputed before the html styling
        echo '<div style="text-align:center; margin:0px auto; margin-bottom: 5px; width:750px;">';

        if ( defined( '_FPA_DEV' ) AND defined( '_FPA_DIAG' ) ) {
            $divwidth = '350px';

        } else {
            $divwidth = '740px';

        }

        // display developer-mode notice
        if ( defined( '_FPA_DEV' ) ) {
            ini_set( 'display_errors', 'Off' ); // default-display

            echo '<div style="text-shadow: 1px 1px 1px #FFF;float:right; text-align:center; width:'. $divwidth .'; background-color:#CAFFD8; border:1px solid #4D8000; color:#404040; font-size:10px; font-family:arial; padding:5px;-moz-box-shadow: 3px 3px 3px #C0C0c0;-webkit-box-shadow: 3px 3px 3px #C0C0c0;box-shadow: 3px 3px 3px #C0C0c0;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">';
            echo '<strong style="color:#4D8000;">DEVELOPER MODE is enabled</strong><br />';
            echo 'This means that a variety of diagnostic information will be displayed on-screen to assist with fpa troubleshooting.';
            echo '</div>';

        } // end developer-mode display

        // display diagnostic-mode notice
        if ( defined( '_FPA_DIAG' ) ) {
            ini_set( 'display_errors', 1 );

            ini_set ( 'error_reporting', '-1' );
            ini_set( 'error_log', $fpa['diagLOG'] );

            echo '<div style="text-shadow: 1px 1px 1px #FFF;float:left; text-align:center; width:'. $divwidth .'; background-color:#CAFFD8; border:1px solid #4D8000; color:#404040; font-size:10px; font-family:arial; padding:5px;-moz-box-shadow: 3px 3px 3px #C0C0c0;-webkit-box-shadow: 3px 3px 3px #C0C0c0;box-shadow: 3px 3px 3px #C0C0c0;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">';
            echo '<strong style="color:#4D8000;">DIAGNOSTIC MODE is enabled</strong><br />';
            echo 'This means that all php errors will be displayed on-screen and logged out to a file named '. $fpa['diagLOG'] .'.';
            echo '</div>';


                if ( file_exists( $fpa['diagLOG'] ) ) {
                    echo '<br style="clear:both;" /><div style="margin-top:10px;text-align:left;text-shadow: 1px 1px 1px #FFF; width:740px; background-color:#FFFFCC; border:1px solid #800000; color:#404040; font-size:10px; font-family:arial; padding:5px;-moz-box-shadow: 3px 3px 3px #C0C0c0;-webkit-box-shadow: 3px 3px 3px #C0C0c0;box-shadow: 3px 3px 3px #C0C0c0;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">';
                    echo '<strong style="color:#800000;">LAST DIAGNOSTIC MODE ERROR</strong> in '. $fpa['diagLOG'] .'<br />';

                    $fpa['fpaLASTERR'] = @array_pop( file( $fpa['diagLOG'] ) );
                    echo $fpa['fpaLASTERR'];
                    echo '</div>';

                } else {
                    echo '<br style="clear:both;" /><div style="margin-top:10px;text-align:left;text-shadow: 1px 1px 1px #FFF; width:740px; background-color:#FFFFCC; border:1px solid #800000; color:#404040; font-size:10px; font-family:arial; padding:5px;-moz-box-shadow: 3px 3px 3px #C0C0c0;-webkit-box-shadow: 3px 3px 3px #C0C0c0;box-shadow: 3px 3px 3px #C0C0c0;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">';
                    echo '<strong style="color:#800000;">LAST DIAGNOSTIC MODE ERROR</strong> in '. $fpa['diagLOG'] .'<br />';
                    echo 'No FPA errors to report at the moment';
                    echo '</div>';

                }

        } // end diagnostic-mode display

        echo '<br style="clear:both;" />';
        echo '</div>';


    } else { // end developer- or diag -mode display
        ini_set( 'display_errors', 0 ); // default-display

    }


    /******************************************************************************************************************/
    /** This is a test variable to check that diagnostic mode works, uncomment to cause an Undefined Variable notice **/
    /** this will display an error if Developer-mode/diagnostic-mode are enabled, otherwise you shouldn't see errors **/
//    echo $ExpectedDiagDevModeErrorVariable;
    /******************************************************************************************************************/


    /** SET THE JOOMLA! PARENT FLAG AND CONSTANTS ********************************************/
    define ( '_VALID_MOS', 1 ); // for J!1.0
    define ( '_JEXEC', 1 );     // for J!1.5, J!1.6, J!1.7



    /** DEFINE LANGUAGE STRINGS **************************************************************/
    define ( '_FPA_LEGEND', 'Legend' );

    define ( '_PHP_DISERR', 'Display PHP Errors Enabled' );
    define ( '_PHP_ERRREP', 'PHP Error Reporting Enabled' );
    define ( '_PHP_LOGERR', 'PHP Errors Being Logged To File' );

    define ( '_FPA_INSTRUCTIONS', 'Instructions' );
    define ( '_FPA_INS_1', 'Enter your problem description <i>(optional)</i>' );
    define ( '_FPA_INS_2', 'Enter any error messages you see <i>(optional)</i>' );
    define ( '_FPA_INS_3', 'Enter any actions taken to resolve the issue <i>(optional)</i>' );
    define ( '_FPA_INS_4', 'Select detail level options of output <i>(optional)</i>' );
    define ( '_FPA_INS_5', 'Click the <span class="normal-note">Generate</span> post button to build the post content' );
    define ( '_FPA_INS_6', 'Copy the contents of the <span class="ok-hilite">&nbsp;Post Detail&nbsp;</span> box and paste it into a post' );
    define ( '_FPA_POST_NOTE', 'Leave ALL fields blank/empty to simply post diagnostic information.' );
    define ( '_FPA_PROB_DSC', 'Problem Description' );
    define ( '_FPA_PROB_MSG', 'Log/Error Message' );
    define ( '_FPA_PROB_ACT', 'Actions Taken To Resolve' );

    /** common screen and post output strings ************************************************/
    define ( '_FPA_GOOD', 'OK/GOOD' );
    define ( '_FPA_WARNINGS', 'WARNINGS' );
    define ( '_FPA_ALERTS', 'ALERTS' );
    define ( '_FPA_BY', 'by' );
    define ( '_FPA_OR', 'or' );
    define ( '_FPA_FOR', 'for' );
    define ( '_FPA_IS', 'is' );
    define ( '_FPA_AT', 'at' );
    define ( '_FPA_LAST', 'Last' );
    define ( '_FPA_DEF', 'default' );
    define ( '_FPA_Y', 'Yes' );
    define ( '_FPA_N', 'No' );
    define ( '_FPA_M', 'Maybe' );
    define ( '_FPA_U', 'Unknown' );
    define ( '_FPA_E', 'Exists' );
    define ( '_FPA_DNE', 'Does Not Exist' );
    define ( '_FPA_F', 'Found' );
    define ( '_FPA_NF', 'Not Found' );
    define ( '_FPA_NC', 'Not Configured' );
    define ( '_FPA_YCON', 'Connected' );
    define ( '_FPA_NCON', 'Not Connected' );
    define ( '_FPA_SUP', 'support' );
    define ( '_FPA_YSUP', 'supported' );
    define ( '_FPA_NSUP', 'not supported' );
    define ( '_FPA_NOA', 'Not Attempted' );
    define ( '_FPA_NER', 'No Errors Reported' );
    define ( '_FPA_ER', 'Error(s) Reported' );
    define ( '_FPA_NA', 'N/A' );
    define ( '_FPA_HIDDEN', 'protected' );
    define ( '_FPA_TNAM', 'Name' );
    define ( '_FPA_TSIZ', 'Size' );
    define ( '_FPA_TENG', 'Engine' );
    define ( '_FPA_TCRE', 'Created' );
    define ( '_FPA_TUPD', 'Updated' );
    define ( '_FPA_TCKD', 'Checked' );
    define ( '_FPA_TCOL', 'Collation' );
    define ( '_FPA_TFRA', 'Fragment Size' );
    define ( '_FPA_TREC', 'Rcds' );  // Number of table records
    define ( '_FPA_TAVL', 'Avg. Length' );
    define ( '_FPA_MODE', 'Mode' );
    define ( '_FPA_WRITABLE', 'Writable' );
    define ( '_FPA_FOLDER', 'Folder' );
    define ( '_FPA_FILE', 'File' );
    define ( '_FPA_OWNER', 'Owner' );
    define ( '_FPA_GROUP', 'Group' );
    define ( '_FPA_VER', 'Version' );
    define ( '_FPA_LOCAL', 'Local' );
    define ( '_FPA_REMOTE', 'Remote' );

    define ( '_PHP_VERLOW', 'PHP version too low' );
    /** END LANGUAGE STRINGS *****************************************************************/
?>



<?php
    /** DETERMINE INSTANCE STATUS & VERSIONING ************************************************
     ** here we check for known files to determine if an instance even exists, then we look for
     ** the version and configuration files. some differ between between versions, so we have a
     ** bit of jiggling to do.
     ** to try and avoid "white-screens" fpa no-longer "includes" these files, but merely tries
     ** to open and read them, although this is slower, it improves the reliability of fpa.
     *****************************************************************************************/

    /** is an instance present? **************************************************************/
    // this is a two-fold sanity check, we look two pairs of known folders, only one pair need exist
    // this caters for the potential of missing folders, but is not exhaustive or too time consuming
    if ( ( file_exists( 'components/' ) AND file_exists( 'modules/' ) ) OR ( file_exists( 'administrator/components/' ) AND file_exists( 'administrator/modules/' ) ) ) {
        $instance['instanceFOUND'] = _FPA_Y;
    } else {
        $instance['instanceFOUND'] = _FPA_N;
    }



    /** what version is the instance? ********************************************************/
    // J1.0 includes/version.php & mambots folder
    if ( file_exists( 'includes/version.php' ) AND file_exists( 'mambots/' ) ) {
        $instance['cmsVFILE'] = 'includes/version.php';

    // J1.5 libraries/joomla/version.php & xmlrpc folder
    } elseif ( file_exists( 'libraries/joomla/version.php' ) AND file_exists( 'xmlrpc/' ) ) {
        $instance['cmsVFILE'] = 'libraries/joomla/version.php';

    // J1.5 & Nooku Server libraries/joomla/version.php & koowa folder
    } elseif ( file_exists( 'libraries/joomla/version.php' ) AND file_exists( 'libraries/koowa/koowa.php' ) ) {
        $instance['cmsVFILE'] = 'libraries/joomla/version.php';

    // J1.6 libraries/joomla/version.php & joomla.xml files
    } elseif ( file_exists( 'libraries/joomla/version.php' ) AND file_exists( 'joomla.xml' ) ) {
        $instance['cmsVFILE'] = 'libraries/joomla/version.php';

    // J1.7 includes/version.php & libraries/joomla/platform.php files
    } elseif ( file_exists( 'includes/version.php' ) AND file_exists( 'libraries/platform.php' ) ) {
        $instance['cmsVFILE'] = 'includes/version.php';

    // fpa could find the required files to determine version(s)
    } else {
        $instance['cmsVFILE'] = _FPA_N;

    }



    /** what version is the framework? (J!1.7 & above) ***************************************/
    // J1.7 libraries/joomla/platform.php
    if ( file_exists( 'libraries/platform.php' ) ) {
        $instance['platformVFILE'] = 'libraries/platform.php';

    // J1.5 Nooku Server libraries/koowa/koowa.php
    } elseif ( file_exists( 'libraries/koowa/koowa.php' ) ) {
        $instance['platformVFILE'] = 'libraries/koowa/koowa.php';

    } else {
        $instance['platformVFILE'] = _FPA_N;

    }



    // read the cms version file into $cmsVContent (all versions)
    if ( $instance['cmsVFILE'] != _FPA_N ) {
        $cmsVContent = file_get_contents( $instance['cmsVFILE'] );
            // find the basic cms information
            preg_match ( '#\$PRODUCT.*=\s[\'|\"](.*)[\'|\"];#', $cmsVContent, $cmsPRODUCT );
            preg_match ( '#\$RELEASE.*=\s[\'|\"](.*)[\'|\"];#', $cmsVContent, $cmsRELEASE );
            preg_match ( '#\$(DEV_LEVEL.*|MAINTENANCE.*)=\s[\'|\"](.*)[\'|\"];#', $cmsVContent, $cmsDEVLEVEL );
            preg_match ( '#\$(DEV_STATUS.*|STATUS.*)=\s[\'|\"](.*)[\'|\"];#', $cmsVContent, $cmsDEVSTATUS );
            preg_match ( '#\$(CODENAME.*|CODE_NAME.*)=\s[\'|\"](.*)[\'|\"];#', $cmsVContent, $cmsCODENAME );
            preg_match ( '#\$(RELDATE.*|RELEASE_DATE.*)=\s[\'|\"](.*)[\'|\"];#', $cmsVContent, $cmsRELDATE );

                $instance['cmsPRODUCT'] = $cmsPRODUCT[1];
                $instance['cmsRELEASE'] = $cmsRELEASE[1];
                $instance['cmsDEVLEVEL'] = $cmsDEVLEVEL[2];
                $instance['cmsDEVSTATUS'] = $cmsDEVSTATUS[2];
                $instance['cmsCODENAME'] = $cmsCODENAME[2];
                $instance['cmsRELDATE'] = $cmsRELDATE[2];

    }



    // read the platform version file into $platformVContent (J!1.7 & above only)
    if ( $instance['platformVFILE'] != _FPA_N ) {
        $platformVContent = file_get_contents( $instance['platformVFILE'] );

            // find the basic platform information
            if ( $instance['platformVFILE'] == 'libraries/koowa/koowa.php' ) {

                // Nooku platform based
                preg_match ( '#VERSION.*=\s[\'|\"](.*)[\'|\"];#', $platformVContent, $platformRELEASE );
                preg_match ( '#VERSION.*=\s[\'|\"].*-(.*)-.*[\'|\"];#', $platformVContent, $platformDEVSTATUS );

                    $instance['platformPRODUCT'] = 'Nooku';
                    $instance['platformRELEASE'] = $platformRELEASE[1];
                    $instance['platformDEVSTATUS'] = $platformDEVSTATUS[1];

            } else {

                // default to the Joomla! platform, as it is most common at the momemt
                preg_match ( '#PRODUCT.*=\s[\'|\"](.*)[\'|\"];#', $platformVContent, $platformPRODUCT );
                preg_match ( '#RELEASE.*=\s[\'|\"](.*)[\'|\"];#', $platformVContent, $platformRELEASE );
                preg_match ( '#MAINTENANCE.*=\s[\'|\"](.*)[\'|\"];#', $platformVContent, $platformDEVLEVEL );
                preg_match ( '#STATUS.*=\s[\'|\"](.*)[\'|\"];#', $platformVContent, $platformDEVSTATUS );
                preg_match ( '#CODE_NAME.*=\s[\'|\"](.*)[\'|\"];#', $platformVContent, $platformCODENAME );
                preg_match ( '#RELEASE_DATE.*=\s[\'|\"](.*)[\'|\"];#', $platformVContent, $platformRELDATE );

                    $instance['platformPRODUCT'] = $platformPRODUCT[1];
                    $instance['platformRELEASE'] = $platformRELEASE[1];
                    $instance['platformDEVLEVEL'] = $platformDEVLEVEL[1];
                    $instance['platformDEVSTATUS'] = $platformDEVSTATUS[1];
                    $instance['platformCODENAME'] = $platformCODENAME[1];
                    $instance['platformRELDATE'] = $platformRELDATE[1];
            }

    }



    /** is Joomla! installed/configured? *****************************************************/
    // determine exactly where the REAL configuration file is, it might not be the one in the "/" folder
    if ( @$instance['cmsRELEASE'] == '1.0' ) {

        if ( file_exists( 'configuration.php' ) ) {
// !TODO add finding configuration outside of "/"
//          $configContent = file_get_contents( 'configuration.php' );

//              if ( preg_match( '#require.*[\"|\'](.*)[\"|\']#', $configContent ) ) {
//                  preg_match ( '#require.*[\"|\'](.*)[\"|\']#', $configContent, $findConfig );

//                  $instance['configPATH'] =  dirname( __FILE__ ) . $findConfig[1];
//              } else {
//                  $instance['configPATH'] =  dirname( __FILE__ ) .'/'. $findConfig[1];
//              }

//                  $instance['configMOVED'] = _FPA_Y;
//              } else {
//                  $instance['configMOVED'] = _FPA_N;
                    $instance['configPATH'] = 'configuration.php';
//              }

            }

    } elseif ( @$instance['cmsRELEASE'] == '1.5' ) {
        $instance['configPATH'] = 'configuration.php';

    } elseif ( @$instance['cmsRELEASE'] >= '1.6' ) {

        // look for a 'defines' override file in the "/" folder.
        if ( file_exists( 'defines.php' ) ) {
            $instance['configPATH'] = 'configuration.php';

        } elseif ( file_exists( 'includes/defines.php' ) ) {
            $instance['configPATH'] = 'configuration.php';

        } else {
            $instance['configDEFINE'] = _FPA_NF;
            $instance['configPATH'] = 'configuration.php';

        }

    } else {
            $instance['configPATH'] = 'configuration.php';
            $instance['configMOVED'] = _FPA_N;
    }


    // find the configuration file (all versions)
    if ( file_exists( $instance['configPATH'] ) ) {
        $instance['instanceCONFIGURED'] = _FPA_Y;

        // determine it's ownership and mode
        if ( is_writable( $instance['configPATH'] ) ) {
            $instance['configWRITABLE']	= _FPA_Y;

        } else {
            $instance['configWRITABLE']	= _FPA_N;

        }


        $instance['configMODE'] = substr( sprintf('%o', fileperms( $instance['configPATH'] ) ),-3, 3 );


        if ( function_exists( 'posix_getpwuid' ) AND $system['sysSHORTOS'] != 'WIN' ) { // gets the UiD and converts to 'name' on non Windows systems
            $instance['configOWNER'] = posix_getpwuid( fileowner( $instance['configPATH'] ) );
            $instance['configGROUP'] = posix_getgrgid( filegroup( $instance['configPATH'] ) );

        } else { // only get the UiD for Windows, not 'name'
            $instance['configOWNER']['name'] = fileowner( $instance['configPATH'] );
            $instance['configGROUP']['name'] = filegroup( $instance['configPATH'] );

        }


        /** if present, is the configuration file valid? *****************************************/
        $cmsCContent = file_get_contents( $instance['configPATH'] );

            if ( preg_match ( '#(\$mosConfig_)#', $cmsCContent ) ) {
                $instance['configVALIDFOR'] = '1.0';

            } elseif ( preg_match ( '#(var)#', $cmsCContent ) ) {
                $instance['configVALIDFOR'] = '1.5';

            } elseif ( preg_match ( '#(public)#', $cmsCContent ) AND $instance['platformVFILE'] == _FPA_N ) {
                $instance['configVALIDFOR'] = '1.6';

            } elseif ( preg_match ( '#(public)#', $cmsCContent ) AND $instance['platformVFILE'] != _FPA_N ) {
                $instance['configVALIDFOR'] = '1.7 and above';

            } else {
                $instance['configVALIDFOR'] = _FPA_U;

            }

            // fpa found a configuration.php but couldn't determine the version, is it valid?
            if ( $instance['configVALIDFOR'] == _FPA_U ) {

                    if ( filesize( 'configuration.php' ) < 512 ) {
                        $instance['configSIZEVALID'] = _FPA_N;

                    }

                }


            // check if the configuration.php version matches the discovered version
            if ( $instance['configVALIDFOR'] != _FPA_U AND $instance['cmsVFILE'] != _FPA_N ) {

                if ( version_compare( $instance['cmsRELEASE'], substr( $instance['configVALIDFOR'],0,3 ), '==' ) ) {
                    $instance['instanceCFGVERMATCH'] = _FPA_Y;

                } else {
                    $instance['instanceCFGVERMATCH'] = _FPA_N;

                }


                // set defaults for the configuration's validity and a sanity score of zero
                $instance['configSANE'] = _FPA_N;
                $instance['configSANITYSCORE'] = 0;


                // !TODO add white-space etc checks
                // do some configuration.php sanity/validity checks
                if ( filesize( 'configuration.php' ) > 512 ) {
                    $instance['cfgSANITY']['configSIZEVALID'] = _FPA_Y;

                }

                // !TODO FINISH  white-space etc checks
                $instance['cfgSANITY']['configNOTDIST'] = _FPA_Y; // is not the distribution example
                $instance['cfgSANITY']['configNOWSPACE'] = _FPA_Y; // no white-space
                $instance['cfgSANITY']['configOPTAG'] = _FPA_Y; // has php open tag
                $instance['cfgSANITY']['configCLTAG'] = _FPA_Y; // has php close tag
                $instance['cfgSANITY']['configJCONFIG'] = _FPA_Y; // has php close tag

                // run through the sanity checks, if sane ( =Yes ) increment the score by 1 (should total 6)
                foreach ( $instance['cfgSANITY'] as $i => $sanityCHECK ) {

                    if ( $instance['cfgSANITY'][$i] == _FPA_Y ) {
                        $instance['configSANITYSCORE'] = $instance['configSANITYSCORE'] +1;
                    }

                }

                // if the configuration file is sane, set it as valid
                if ( $instance['configSANITYSCORE'] == '6' ) {
                    $instance['configSANE'] = _FPA_Y; // configuration appears valid?

                }

            } else {
                $instance['instanceCFGVERMATCH'] = _FPA_U;

            }

            // common configuration variables for J!1.5 and above only
            if ( $instance['configVALIDFOR'] != _FPA_U ) {

                // common configuration variable across all versions
                preg_match ( '#\$(mosConfig_offline.*|offline.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configOFFLINE );
                preg_match ( '#\$(mosConfig_sef.*|sef.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configSEF );
                preg_match ( '#\$(mosConfig_gzip.*|gzip.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configGZIP );
                preg_match ( '#\$(mosConfig_caching.*|caching.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configCACHING );
                preg_match ( '#\$(mosConfig_error_reporting.*|error_reporting.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configERRORREP );
                preg_match ( '#\$(mosConfig_debug.*|debug.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configSITEDEBUG );
                preg_match ( '#dbtype.*=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configDBTYPE );

                // J!1.0 assumed 'mysql' with no variable, so we'll just add it
                if (!array_key_exists('1', $configDBTYPE)) {
                    $configDBTYPE[1] = 'mysql';

                }

                preg_match ( '#\$(mosConfig_host.*|host.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configDBHOST );
                preg_match ( '#\$(mosConfig_db.*|db\s.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configDBNAME );
                preg_match ( '#\$(mosConfig_dbprefix.*|dbprefix.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configDBPREF );
                preg_match ( '#\$(mosConfig_user.*|user.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configDBUSER );
                preg_match ( '#\$(mosConfig_password.*|password.*)=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configDBPASS );

                    $instance['configOFFLINE'] = $configOFFLINE[2];
                    $instance['configSEF'] = $configSEF[2];
                    $instance['configGZIP'] = $configGZIP[2];
                    $instance['configCACHING'] = $configCACHING[2];
                    $instance['configERRORREP'] = $configERRORREP[2];
                    $instance['configSITEDEBUG'] = $configSITEDEBUG[2];
                    $instance['configDBTYPE'] = $configDBTYPE[1];
                    $instance['configDBHOST'] = $configDBHOST[2];
                    $instance['configDBNAME'] = $configDBNAME[2];
                    $instance['configDBPREF'] = $configDBPREF[2];
                    $instance['configDBUSER'] = $configDBUSER[2];
                    $instance['configDBPASS'] = $configDBPASS[2];

                    // force all the configuration settings that are either depreciated or unused by the lowest support release (ie: J!1.0)
                    $instance['configLANGDEBUG'] = _FPA_NA;
                    $instance['configSEFSUFFIX'] = _FPA_NA;
                    $instance['configSEFRWRITE'] = _FPA_NA;
                    $instance['configFTP'] = _FPA_NA;
                    $instance['configSSL'] = _FPA_NA;
                    $instance['configACCESS'] = _FPA_NA;
                    $instance['configUNICODE'] = _FPA_NA;
                    // these forced settings will be over-written later by the variable supported release

            }


            // common configuration variables for J!1.5 and above only
            if ( $instance['configVALIDFOR'] != '1.0' AND $instance['configVALIDFOR'] != _FPA_U ) {

                preg_match ( '#sef_rewrite.*=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configSEFREWRITE );
                preg_match ( '#sef_suffix.*=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configSEFSUFFIX );
                preg_match ( '#debug_lang.*=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configLANGDEBUG );
                preg_match ( '#ftp_enable.*=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configFTP );
                preg_match ( '#force_ssl.*=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configSSL );

                    $instance['configSEFRWRITE'] = $configSEFREWRITE[1];
                    $instance['configSEFSUFFIX'] = $configSEFSUFFIX[1];
                    $instance['configLANGDEBUG'] = $configLANGDEBUG[1];
                    $instance['configFTP'] = $configFTP[1];

                    if ( $configSSL ) { // 1.7 hack, 1.7.0 seems not to have this option
                        $instance['configSSL'] = $configSSL[1];

                    } else {
                        $instance['configSSL'] = _FPA_NA;

                    }

            }


            // common configuration variables for J!1.6 and above only
            if ( $instance['configVALIDFOR'] != '1.0' AND $instance['configVALIDFOR'] != '1.5' AND $instance['configVALIDFOR'] != _FPA_U ) {

                preg_match ( '#access.*=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configACCESS );
                preg_match ( '#unicodeslugs.*=\s[\'|\"](.*)[\'|\"];#', $cmsCContent, $configUNICODE );

                    $instance['configACCESS'] = $configACCESS[1];
                    $instance['configUNICODE'] = $configUNICODE[1];

            }

            // check if all the DB credentials are complete
            if ( @$instance['configDBTYPE'] AND $instance['configDBHOST'] AND $instance['configDBNAME'] AND $instance['configDBPREF'] AND $instance['configDBUSER'] AND $instance['configDBPASS'] ) {
                $instance['configDBCREDOK'] = _FPA_Y;
            } else {
                $instance['configDBCREDOK'] = _FPA_N;

            }



        // looking for htaccess (Apache and some others) or web.config (IIS)
        if ( $system['sysSHORTWEB'] != 'MIC' ) {

            // htaccess files
            if ( file_exists( '.htaccess' ) ) {
                $instance['configSITEHTWC'] = _FPA_Y;
            } else {
                $instance['configSITEHTWC'] = _FPA_N;

            }

            if ( file_exists( 'administrator/.htaccess' ) ) {
                $instance['configADMINHTWC'] = _FPA_Y;

            } else {
                $instance['configADMINHTWC'] = _FPA_N;

            }

        } else {

            // web.config file
            if ( file_exists( 'web.config' ) ) {
                $instance['configSITEHTWC'] = _FPA_Y;
                $instance['configADMINHTWC'] = _FPA_NA;

            } else {
                $instance['configSITEHTWC'] = _FPA_N;
                $instance['configADMINHTWC'] = _FPA_NA;
            }

        }


    } else { // no configuration.php found
        $instance['instanceCONFIGURED'] = _FPA_N;
        $instance['configVALIDFOR'] = _FPA_U;

    }
?>



<?php
// TESTING ONLY HUUUUUUGE DB
//$instance['configDBNAME'] = 'njs_bandsite';
?>



<?php
    /** DETERMINE SYSTEM ENVIRONMENT & SETTINGS ***********************************************
     ** here we try to determine the hosting enviroment and configuration
     ** to try and avoid "white-screens" fpa tries to check for function availability before
     ** using any function, but this does mean it has grown in size quite a bit and unfortunately
     ** gets a little messy in places.
     *****************************************************************************************/

    /** what server and os is the host? ******************************************************/
    $phpenv['phpVERSION'] = phpversion();
    $system['sysPLATFUL'] = php_uname('a');
    $system['sysPLATOS'] = php_uname('s');
    $system['sysPLATREL'] = php_uname('r');
    $system['sysPLATFORM'] = php_uname('v');
    $system['sysPLATNAME'] = php_uname('n');
    $system['sysPLATTECH'] = php_uname('m');
    $system['sysSERVNAME'] = $_SERVER['SERVER_NAME'];
    $system['sysSERVIP'] = gethostbyname($_SERVER['SERVER_NAME']);
    $system['sysSERVSIG'] = $_SERVER['SERVER_SOFTWARE'];
    $system['sysENCODING'] = $_SERVER["HTTP_ACCEPT_ENCODING"];
    $system['sysCURRUSER'] = get_current_user(); // current process user
    $system['sysSERVIP'] = gethostbyname($_SERVER['SERVER_NAME']);

// !TESTME for WIN IIS7?
//    $system['sysSERVIP'] =  $_SERVER['LOCAL_ADDR'];

    if ( $system['sysSHORTOS'] != 'WIN' ) {

        // !BUGID #1 $_ENV USER doesn't work on lightspeed server? maybe tie it down to apache only
        $system['sysEXECUSER'] = @$_ENV['USER']; // user that executed this script

            if ( !@$_ENV['USER'] ) {
                $system['sysEXECUSER'] = $system['sysCURRUSER'];

            }

        $system['sysDOCROOT'] = $_SERVER['DOCUMENT_ROOT'];

    } else {
        $localpath = getenv( 'SCRIPT_NAME' );
        $absolutepath = str_replace( '\\', '/', realpath( basename( getenv( 'SCRIPT_NAME' ) ) ) );
        $system['sysDOCROOT'] = substr( $absolutepath, 0, strpos( $absolutepath, $localpath ) );
        $system['sysEXECUSER'] = $system['sysCURRUSER']; // Windows work-around for not using EXEC User

    }


    // looking for the Apache "suExec" Utility
    if ( function_exists( 'exec' ) AND $system['sysSHORTOS'] != 'WIN' ) { // find the owner of the current process running this script
        $system['sysWEBOWNER'] = exec("whoami");

    } elseif ( function_exists( 'passthru' ) AND $system['sysSHORTOS'] != 'WIN' ) {
        $system['sysWEBOWNER'] = passthru("whoami");

    } else {
        $system['sysWEBOWNER'] = _FPA_NA;

    }

        // find the system temp directory
    if ( version_compare( PHP_VERSION, '5.2.1', '>=' ) ) {
        $system['sysSYSTMPDIR'] = sys_get_temp_dir();

        // is the system /tmp writable to this user?
        if ( is_writable( sys_get_temp_dir() ) ) {
            $system['sysTMPDIRWRITABLE'] = _FPA_Y;

        } else {
            $system['sysTMPDIRWRITABLE'] = _FPA_N;

        }

    } else {
        $system['sysSYSTMPDIR'] = _FPA_U;
        $system['sysTMPDIRWRITABLE'] = _FPA_U;

    }
?>



<?php
    /** DETERMINE PHP ENVIRONMENT & SETTINGS ***********************************************
     ** here we try to determine the php enviroment and configuration
     ** to try and avoid "white-screens" fpa tries to check for function availability before
     ** using any function, but this does mean it has grown in size quite a bit and unfortunately
     ** gets a little messy in places.
     *****************************************************************************************/

    /** general php related settings? *****************************************************/
    if ( version_compare( PHP_VERSION, '5.0', '>=' ) ) {
        $phpenv['phpSUPPORTSMYSQLI'] = _FPA_Y;

    } elseif ( version_compare( PHP_VERSION, '4.4.9', '<=' ) ) {
        $phpenv['phpSUPPORTSMYSQLI'] = _FPA_N;

    } else {
        $phpenv['phpSUPPORTSMYSQLI'] = _FPA_U;

    }

    // find the current php.ini file
    if ( version_compare( PHP_VERSION, '5.2.4', '>=' ) ) {
        $phpenv['phpINIFILE']       = php_ini_loaded_file();

    } else {
        $phpenv['phpINIFILE'] = _FPA_U;

    }

    // find the other loaded php.ini file(s)
    if (version_compare(PHP_VERSION, '4.3.0', '>=')) {
        $phpenv['phpINIOTHER']      = php_ini_scanned_files();

    } else {
        $phpenv['phpINIOTHER'] = _FPA_U;

    }

    $phpenv['phpREGGLOBAL']         = ini_get( 'register_globals' );
    $phpenv['phpMAGICQUOTES']       = ini_get( 'magic_quotes_gpc' );
    $phpenv['phpSAFEMODE']          = ini_get( 'safe_mode' );
    $phpenv['phpOPENBASE']          = ini_get( 'open_basedir' );
    $phpenv['phpMAGICQUOTES']       = ini_get( 'magic_quotes_gpc' );
    $phpenv['phpSESSIONPATH']       = session_save_path();

    // if open_basedir is in effect, don't bother doing session_save.path test, will error if path not in open_basedir
    if ( isset( $phpenv['phpOPENBASE'] ) ) {

        // is the session_save.path writable to this user?
        if ( is_writable( session_save_path() ) ) {
            $phpenv['phpSESSIONPATHWRITABLE'] = _FPA_Y;

        } else {
            $phpenv['phpSESSIONPATHWRITABLE'] = _FPA_N;

        }

    } else {
        $phpenv['phpSESSIONPATHWRITABLE'] = _FPA_U;

    }

    // input and upload related settings
    $phpenv['phpUPLOADS']           = ini_get( 'file_uploads' );
    $phpenv['phpMAXUPSIZE']         = ini_get( 'upload_max_filesize' );
    $phpenv['phpMAXPOSTSIZE']       = ini_get( 'post_max_size' );
    $phpenv['phpMAXINPUTTIME']      = ini_get( 'max_input_time' );
    $phpenv['phpMAXEXECTIME']       = ini_get( 'max_execution_time' );
    $phpenv['phpMEMLIMIT']          = ini_get( 'memory_limit' );

    /** API and ownership related settings ***************************************************/
    $phpenv['phpAPI']               = php_sapi_name();

        // looking for php to be installed as a CGI or CGI/Fast
        if (substr($phpenv['phpAPI'], 0, 3) == 'cgi') {
            $phpenv['phpCGI'] = _FPA_Y;

            // looking for the Apache "suExec" utility
            if ( ( $system['sysCURRUSER'] === $system['sysWEBOWNER'] ) AND ( substr($phpenv['phpAPI'], 0, 3) == 'cgi' ) ) {
                $phpenv['phpAPACHESUEXEC'] = _FPA_Y;
                $phpenv['phpOWNERPROB'] = _FPA_N;

            } else {
                $phpenv['phpAPACHESUEXEC'] = _FPA_N;
                $phpenv['phpOWNERPROB'] = _FPA_M;

            }

            // looking for the "phpsuExec" utility
            if ( ( $system['sysCURRUSER'] === $system['sysEXECUSER'] ) AND ( substr($phpenv['phpAPI'], 0, 3) == 'cgi' ) ) {
                $phpenv['phpPHPSUEXEC'] = _FPA_Y;
                $phpenv['phpOWNERPROB'] = _FPA_N;

            } else {
                $phpenv['phpPHPSUEXEC'] = _FPA_N;
                $phpenv['phpOWNERPROB'] = _FPA_M;

            }

        } else {
            $phpenv['phpCGI'] = _FPA_N;
            $phpenv['phpAPACHESUEXEC'] = _FPA_N;
            $phpenv['phpPHPSUEXEC'] = _FPA_N;
            $phpenv['phpOWNERPROB'] = _FPA_M;

        }



        /** WARNING WILL ROBINSON! ****************************************************************
         ** THIS IS A TEST FEATURE AND AS SUCH NOT GUARANTEED TO BE 100% ACCURATE
         ** try and cater for custom "su" environments, like cluster, grid and cloud computing.
         ** this would include weird ownership combinations that allow group access to non-owner files
         ** (like GoDaddy and a couple of grid and cloud providers I know of)
         *****************************************************************************************/
        if ( ( $instance['instanceCONFIGURED'] == _FPA_Y ) AND ( $system['sysCURRUSER'] != $instance['configOWNER']['name'] ) AND ( $instance['configWRITABLE'] == _FPA_Y ) AND ( ( substr( $instance['configMODE'],0 ,1 ) < '6' ) OR ( substr( $instance['configMODE'],1 ,1 ) < '6' ) OR ( substr( $instance['configMODE'],2 ,1 ) <= '6' ) ) ) {
            $phpenv['phpCUSTOMSU'] = _FPA_M;
            $phpenv['phpOWNERPROB'] = _FPA_N;

        } else {
            $phpenv['phpCUSTOMSU'] = _FPA_N;
            $phpenv['phpOWNERPROB'] = _FPA_M;

        }
        /*****************************************************************************************/
        /** THIS IS A TEST FEATURE AND AS SUCH NOT GUARANTEED TO BE 100% ACCURATE ****************/
        /*****************************************************************************************/


    // get all the Apache loaded extensions and versions
    foreach ( get_loaded_extensions() as $i => $ext ) {
        $phpextensions[$ext]    = phpversion($ext);

    }

    $phpextensions['Zend Engine'] = zend_version();
?>



<?php
    /** DETERMINE APACHE ENVIRONMENT & SETTINGS ***********************************************
     ** here we try to determine the php enviroment and configuration
     ** to try and avoid "white-screens" fpa tries to check for function availability before
     ** using any function, but this does mean it has grown in size quite a bit and unfortunately
     ** gets a little messy in places.
     *****************************************************************************************/
    /** general apache loaded modules? *******************************************************/
	if ( function_exists( 'apache_get_version' ) ) {  // for Apache module interface

        foreach ( apache_get_modules() as $i => $modules ) {
           $apachemodules[$i] = ( $modules );  // show the version of loaded extensions

        }

        // include the Apache version
        $apachemodules[] = apache_get_version();

	} else {  // for Apache cgi interface

        //cgi-fcgi
	    print_r( get_extension_funcs( "cgi-fcgi" ) );

	}
    // !TODO see if there are IIS specific functions/modules
?>



<?php
    /** COMPLETE MODE (PERMISSIONS) CHECKS ON KNOWN FOLDERS ***********************************
     ** test the mode and writability of known folders from the $folders array
     ** to try and avoid "white-screens" fpa tries to check for function availability before
     ** using any function, but this does mean it has grown in size quite a bit and unfortunately
     ** gets a little messy in places.
     *****************************************************************************************/
    /** build the mode-set details for each folder *******************************************/
    if ( $instance['instanceFOUND'] == _FPA_Y ) {

        foreach ( $folders as $i => $show ) {

            if ( $show != 'Core Folders' ) { // ignore the ARRNAME

                if ( file_exists( $show ) ) {
                    $modecheck[$show]['mode'] = substr( sprintf('%o', fileperms( $show ) ),-3, 3 );

                    if ( is_writable( $show ) ) {
                        $modecheck[$show]['writable'] = _FPA_Y;

                    } else {
                        $modecheck[$show]['writable'] = _FPA_N;

                    }

                    if ( function_exists( 'posix_getpwuid' ) AND $system['sysSHORTOS'] != 'WIN' ) {
                        $modecheck[$show]['owner'] = posix_getpwuid( fileowner( $show ) );
                        $modecheck[$show]['group'] = posix_getgrgid( filegroup( $show ) );

                    } else {
                        $modecheck[$show]['owner']['name'] = fileowner( $show );
                        $modecheck[$show]['group']['name'] = filegroup( $show );

                    }

                } else {
                    $modecheck[$show]['mode'] = '---';
                    $modecheck[$show]['writable'] = '-';
                    $modecheck[$show]['owner']['name'] = '-';
                    $modecheck[$show]['group']['name'] = _FPA_DNE;

                }

            }

        }

        // !CLEANME this needs to be done a little smarter
        // here we take the folders array and unset folders that aren't relevant to a specific release
        function filter_folders( $folders, $instance ) {
        GLOBAL $folders;

            if ( $instance['cmsRELEASE'] != '1.0' ) { // ignore the folders for J!1.0
                unset ( $folders[4] );

            } elseif ( $instance['cmsRELEASE'] == '1.0' ) { // ignore folders for J1.5 and above
                unset ( $folders[3] );
                unset ( $folders[8] );
                unset ( $folders[9] );
                unset ( $folders[12] );

            }

            if ( $instance['platformPRODUCT'] != 'Nooku' ) { // ignore the Nooku sites folder if not Nooku
                unset ( $folders[14] );

            }

        }

        // !FIXME need to fix warning in array_filter
        // new filtered list of folders to check permissions on, based on the installed release
        @array_filter( $folders, filter_folders( $folders, $instance ) );

    }
    unset ( $key, $show );
?>



<?php
    /** directory list of elevated permissions ***********************************************/
    if ( $showElevated == '1' ) {

        $dirCount = 0;

        function getDirectory( $path = '.', $level = 0 ) {
        GLOBAL $elevated, $dirCount;

            // Directories to ignore when listing output. Many hosts
            $ignore = array( '.', '..' );

            // Open the directory to the handle $dh
            $dh = @opendir( $path );

            // Loop through the directory
            while ( false !== ( $file = readdir( $dh ) ) ) {

                // Check that this file is not to be ignored
                if ( !in_array( $file, $ignore ) ) {

                    if ( $dirCount < '10' ) { // 10 or more folder will cancel the processing

                        // Its a directory, so we need to keep reading down...
                        if ( is_dir( "$path/$file" ) ) {

                            $dirName = $path .'/'. $file;
                            $dirMode = substr( sprintf( '%o', fileperms( $dirName ) ),-3, 3 );

                                // looking for --7 or -7- or -77 (default folder permissions are usually 755)
                                if ( substr( $dirMode,1 ,1 ) == '7' OR substr( $dirMode,2 ,1 ) == '7' ) {
                                    $elevated[''. str_replace( './','', $dirName ) .'']['mode'] = $dirMode;

                                    if ( is_writable( $dirName ) ) {
                                        $elevated[''. str_replace( './','', $dirName ) .'']['writable'] = _FPA_Y;

                                    } else {  // custom ownership or setUiD/GiD in-effect
                                        $elevated[''. str_replace( './','', $dirName ) .'']['writable'] = _FPA_N;

                                    }
                                    $dirCount++;

                                }

                                // Re-call this same function but on a new directory.
                                getDirectory ( "$path/$file", ( $level +1 ) );

                        }

                    }

                }

            }
            // Close the directory handle
            closedir( $dh );

        }

            if ( $dirCount == '0' ) {
                $elevated['None'] = 'None';
                $elevated['None']['mode'] = '-';
                $elevated['None']['writable'] = '-';
            }

        getDirectory( '.' );
        ksort( $elevated );

    } // end showElevated
?>



<?php
    /** DETERMINE THE MYSQL VERSION AND IF WE CAN CONNECT *************************************
     ** here we try and find out more about MySQL and if we have an installed instance, see if
     ** talk to it and access the database.
     *****************************************************************************************/
    // !TODO database test-cases
    if ( $instance['instanceCONFIGURED'] == _FPA_Y AND $instance['configDBCREDOK'] == _FPA_Y ) {
        $database['dbDOCHECKS'] = _FPA_Y;

        if ( $instance['configDBHOST'] == 'localhost' OR $instance['configDBHOST'] == '127.0.0.1' ) {
            $database['dbLOCAL'] = _FPA_Y;

        } else {
            $database['dbLOCAL'] = _FPA_N;

        }

        // !TODO DB PING
        /**
            // See if the PHP Functions are available to test for connectivity
            if ( @$opSystem != 'WIN') {

              if ( function_exists('exec') ) {
                $hostPing = 1;
                exec("ping -c 2 ". $cfgARRAY['dbhost'][1] ." 2>&1", $output, $retval);
              } else if ( function_exists('passthru') ) {
                $hostPing = 1;
                passthru("ping -c 2 ". $cfgARRAY['dbhost'][1] ." >/dev/null 2>$0", $retval);
              } else if ( function_exists('system') ) {
                $hostPing = 1;
                system("ping -c 2 ". $cfgARRAY['dbhost'][1] ." >/dev/null 2>$0", $retval);
              } else {
                $hostPing = 0;
                echo '<br /><span class="isOk">ping not attempted, PHP restriciton</span>';
              }

            } else { // Windows Machines IIS Users, by default have no access to the shell, so the above errors
              @$hostPing = 0;
              echo '<br /><span class="isOk">ping not attempted, host restriciton</span>';
            }

            if ( @$hostPing != 0 ) {

              if ( @$retval != 0 ) {
                echo '<br />- '. PINGHOST .' <span class="isNo">'. FAIL .'</span>';
              } else {
                echo '<br />- '. PINGHOST .' <span class="isYes">'. SUCCESS.'</span>';
              }

            }
        **/

        // try and establish if we can talk to the dBase server with a ping, then try and connect and ping with mysql_ping
        if ( $instance['configDBTYPE'] == 'mysql' ) {

            $dBconn = @mysql_connect( $instance['configDBHOST'], $instance['configDBUSER'], $instance['configDBPASS'] );
            $database['dbERROR'] = mysql_errno() .':'. mysql_error();

            if ( $dBconn ) {
                mysql_select_db( $instance['configDBNAME'], $dBconn );
                $database['dbERROR'] = mysql_errno() .':'. mysql_error();

                $database['dbHOSTSERV'] = mysql_get_server_info( $dBconn ); // SQL server version
                $database['dbHOSTINFO'] = mysql_get_host_info( $dBconn ); // connection type to dB
                $database['dbHOSTPROTO'] = mysql_get_proto_info( $dBconn ); // server protocol type
                $database['dbHOSTCLIENT'] = mysql_get_client_info(); // client library version
                $database['dbHOSTDEFCHSET'] = mysql_client_encoding( $dBconn ); // this is the hosts default character-set
                $database['dbHOSTSTATS'] = explode("  ", mysql_stat( $dBconn ) ); // latest statistics

                // find the database collation
                $coResult = mysql_query( "SHOW VARIABLES LIKE 'collation_database'" );

                while ( $row = mysql_fetch_row( $coResult ) ) {
                    $database['dbCOLLATION'] =  $row[1];

                }

                // find the database character-set
                $csResult = mysql_query( "SHOW VARIABLES LIKE 'character_set_database'" );

                while ( $row = mysql_fetch_array( $csResult ) ) {
                    $database['dbCHARSET'] =  $row[1];

                }

                // find all the dB tables and calculate the size
                mysql_select_db($instance['configDBNAME'], $dBconn);
                $tblResult = mysql_query("SHOW TABLE STATUS");

                    $database['dbSIZE'] = 0;
                    $rowCount = 0;

                    while ( $row = mysql_fetch_array( $tblResult ) ) {
                        $rowCount++;

                        $tables[$row['Name']]['TABLE'] = $row['Name'];

                        $table_size = ( $row[ 'Data_length' ] + $row[ 'Index_length' ] ) / 1024;
                        $tables[$row['Name']]['SIZE'] = sprintf( '%.2f', $table_size );
                        $database['dbSIZE'] += sprintf( '%.2f', $table_size );
                        $tables[$row['Name']]['SIZE'] = $tables[$row['Name']]['SIZE'] .' KiB';


                        if ( $showTables == '1' ) {
                            $tables[$row['Name']]['ENGINE'] = $row['Engine'];
                            $tables[$row['Name']]['VERSION'] = $row['Version'];
                            $tables[$row['Name']]['CREATED'] = $row['Create_time'];
                            $tables[$row['Name']]['UPDATED'] = $row['Update_time'];
                            $tables[$row['Name']]['CHECKED'] = $row['Check_time'];
                            $tables[$row['Name']]['COLLATION'] = $row['Collation'];
                            $tables[$row['Name']]['FRAGSIZE'] = sprintf( '%.2f', ( $row['Data_free'] /1024 ) ) .' KiB';
                            $tables[$row['Name']]['MAXGROW'] = sprintf( '%.1f', ( $row['Max_data_length'] /1073741824 ) ) .' GiB';
                            $tables[$row['Name']]['RECORDS'] = $row['Rows'];
                            $tables[$row['Name']]['AVGLEN'] = sprintf( '%.2f', ( $row['Avg_row_length'] /1024 ) ) .' KiB';

                        }

                    }


                if ( $database['dbSIZE'] > '1024' ) {
                    $database['dbSIZE'] = sprintf('%.2f', ( $database['dbSIZE'] /1024 ) ) .' MiB';

                } else {
                    $database['dbSIZE'] = $database['dbSIZE'] .' KiB';

                }

                $database['dbTABLECOUNT'] = $rowCount;
                mysql_close( $dBconn );

            } else {
                $database['dbERROR'] = mysql_errno() .':'. mysql_error();

            } // end mysql if $dBconn is good

        } elseif ( $instance['configDBTYPE'] == 'mysqli' AND $phpenv['phpSUPPORTSMYSQLI'] == _FPA_Y ) { // mysqli

            $dBconn = @new mysqli( $instance['configDBHOST'], $instance['configDBUSER'], $instance['configDBPASS'], $instance['configDBNAME'] );
            $database['dbERROR'] = mysqli_connect_errno( $dBconn ) .':'. mysqli_connect_error( $dBconn );

            if ( $dBconn ) {
                $database['dbHOSTSERV'] = @mysqli_get_server_info( $dBconn ); // SQL server version
                $database['dbHOSTINFO'] = @mysqli_get_host_info( $dBconn ); // connection type to dB
                $database['dbHOSTPROTO'] = @mysqli_get_proto_info( $dBconn ); // server protocol type
                $database['dbHOSTCLIENT'] = @mysqli_get_client_info(); // client library version
                $database['dbHOSTDEFCHSET'] = @mysqli_client_encoding( $dBconn ); // this is the hosts default character-set
                $database['dbHOSTSTATS'] = explode("  ", @mysqli_stat( $dBconn ) ); // latest statistics

                // find the database collation
                $coResult = @$dBconn->query( "SHOW VARIABLES LIKE 'collation_database'" );

                while ( $row = @mysqli_fetch_row( $coResult ) ) {
                    $database['dbCOLLATION'] =  $row[1];

                }

                // find the database character-set
                $csResult = @$dBconn->query( "SHOW VARIABLES LIKE 'character_set_database'" );

                while ( $row = @mysqli_fetch_array( $csResult ) ) {
                    $database['dbCHARSET'] =  $row[1];

                }

                // find all the dB tables and calculate the size
                $tblResult = @$dBconn->query( "SHOW TABLE STATUS" );

                    $database['dbSIZE'] = 0;
                    $rowCount = 0;

                    while ( $row = @mysqli_fetch_array( $tblResult ) ) {
                        $rowCount++;

                        $tables[$row['Name']]['TABLE'] = $row['Name'];

                        $table_size = ($row[ 'Data_length' ] + $row[ 'Index_length' ]) / 1024;
                        $tables[$row['Name']]['SIZE'] = sprintf( '%.2f', $table_size );
                        $database['dbSIZE'] += sprintf( '%.2f', $table_size );
                        $tables[$row['Name']]['SIZE'] = $tables[$row['Name']]['SIZE'] .' KiB';


                        if ( $showTables == '1' ) {
                            $tables[$row['Name']]['ENGINE'] = $row['Engine'];
                            $tables[$row['Name']]['VERSION'] = $row['Version'];
                            $tables[$row['Name']]['CREATED'] = $row['Create_time'];
                            $tables[$row['Name']]['UPDATED'] = $row['Update_time'];
                            $tables[$row['Name']]['CHECKED'] = $row['Check_time'];
                            $tables[$row['Name']]['COLLATION'] = $row['Collation'];
                            $tables[$row['Name']]['FRAGSIZE'] = sprintf( '%.2f', ( $row['Data_free'] /1024 ) ) .' KiB';
                            $tables[$row['Name']]['MAXGROW'] = sprintf( '%.1f', ( $row['Max_data_length'] /1073741824 ) ) .' GiB';
                            $tables[$row['Name']]['RECORDS'] = $row['Rows'];
                            $tables[$row['Name']]['AVGLEN'] = sprintf( '%.2f', ( $row['Avg_row_length'] /1024 ) ) .' KiB';

                        }

                    }


                if ( $database['dbSIZE'] > '1024' ) {
                    $database['dbSIZE'] = sprintf( '%.2f', ( $database['dbSIZE'] /1024 ) ) .' MiB';

                } else {
                    $database['dbSIZE'] = $database['dbSIZE'] .' KiB';

                }
                $database['dbTABLECOUNT'] = $rowCount;

            } else {
               // $database['dbERROR'] = mysqli_connect_errno( $dBconn ) .':'. mysqli_connect_error( $dBconn );

            } // end mysqli if $dBconn is good

        } else {
                $database['dbHOSTSERV'] = _FPA_U; // SQL server version
                $database['dbHOSTINFO'] = _FPA_U; // connection type to dB
                $database['dbHOSTPROTO'] = _FPA_U; // server protocol type
                $database['dbHOSTCLIENT'] = _FPA_U; // client library version
                $database['dbHOSTDEFCHSET'] = _FPA_U; // this is the hosts default character-set
                $database['dbHOSTSTATS'] = _FPA_U; // latest statistics
                $database['dbCOLLATION'] =  _FPA_U;
                $database['dbCHARSET'] =  _FPA_U;

        } // end of dataBase connection routines



        if ( isset( $dBconn ) AND $database['dbERROR'] == '0:' ) {
            $database['dbERROR'] = _FPA_N;

        } elseif ( $database['dbLOCAL'] == _FPA_N AND substr($database['dbERROR'], 0, 4) == '2005' ) { // 2005 = can't access host
            // if this is a remote host, it might be firewalled or disabled from external or non-internal network access
            $database['dbERROR'] = $database['dbERROR'] .' ( might not be an error, this remote SQL server might be firewalled by mistake )';

        }

    // if no configuration or if configured but dBase credentials aren't valid
    } else {
        $database['dbDOCHECKS'] = _FPA_N;
        $database['dbLOCAL'] = _FPA_U;

    }
?>



<?php
    /** find the SITE/ADMIN Components, Modules, Plugins and Templates ***********************/
    if ( $instance['instanceFOUND'] == _FPA_Y ) { // fix for IIS *shrug*

    function getDetails( $path, &$arrname, $loc, $level = 0 ) {
    global $component, $module, $plugin, $template;

    // fix for PHP5.3 pass-variable-by-reference depreciation
    $arrname = $arrname;

    $ignore = array( '.', '..', 'index.htm', 'index.html', '.DS_Store', 'none.xml', 'metadata.xml', 'default.xml', 'form.xml', 'contact.xml', 'edit.xml', 'blog.xml' );
    // Directories to ignore when listing output. Many hosts

    $dh = @opendir( $path );
    // Open the directory to the handle $dh

        while( false !== ( $file = @readdir( $dh ) ) ) {
            // Loop through the directory

            if( !in_array( $file, $ignore ) ) {
            // Check that this file is not to be ignored

                if( is_dir( "$path/$file" ) ) {
                // Its a directory, so we need to keep reading down...

                    getDetails( "$path/$file", $arrname, $loc, ( $level +1 ) );
                    // Re-call this same function but on a new directory.
                    // this is what makes function recursive.

                } else {

                    if ( $path == 'components' ) {
                        $cDir = substr( strrchr( $path .'/'. $file, '/' ), 1 );

                    } else {
                        $cDir = $path .'/'. $file;

                    }


                    if ( preg_match( "/\.xml/i", $file ) ) { #if filename matches .xml in the name

                        $content = file_get_contents( $cDir );

                        if ( preg_match( '#<(extension|install|mosinstall)#', $content, $isValidFile ) ) {
                            $arrname[$loc][$cDir] = '';

                            $arrname[$loc][$cDir]['author'] = '-';
                            $arrname[$loc][$cDir]['authorUrl'] = '-';
                            $arrname[$loc][$cDir]['version'] = '-';
                            $arrname[$loc][$cDir]['creationDate'] = '-';
                            $arrname[$loc][$cDir]['type'] = '-';


                                if ( preg_match( '#<name>(.*)</name>#', $content, $name ) ) {
                                    $arrname[$loc][$cDir]['name'] = strip_tags( substr( $name[1], 0, 30 ) );

                                } else {
                                    $arrname[$loc][$cDir]['name'] = _FPA_U;

                                }

                                if ( preg_match( '#<author>(.*)</author>#', $content, $author ) ) {
                                    $arrname[$loc][$cDir]['author'] = strip_tags( substr( $author[1], 0, 19 ) );

                                    if ( $author[1] == 'Joomla! Project' OR strtolower( $name[1] ) == 'joomla admin' OR strtolower( $name[1] ) == 'rhuk_milkyway' OR strtolower( $name[1] ) == 'ja_purity' OR strtolower( $name[1] ) == 'khepri' OR strtolower( $name[1] ) == 'bluestork' OR strtolower( $name[1] ) == 'atomic' OR strtolower( $name[1] ) == 'hathor' OR strtolower( substr( $name[1], 0, 4 ) ) == 'beez' ) {
                                        $arrname[$loc][$cDir]['type'] = 'Core';

                                    } else {
                                        $arrname[$loc][$cDir]['type'] = '3rd Party';

                                    }

                                } else {
                                    $arrname[$loc][$cDir]['author'] = '-';
                                    $arrname[$loc][$cDir]['type'] = '-';

                                }

                                if ( preg_match( '#<version>(.*)</version>#', $content, $version ) ) {
                                    $arrname[$loc][$cDir]['version'] = substr( $version[1], 0, 13 );

                                } else {
                                    $arrname[$loc][$path .'/'. $file]['version'] = '-';

                                }

                                if ( preg_match( '#<creationDate>(.*)</creationDate>#', $content, $creationDate ) ) {
                                    $arrname[$loc][$cDir]['creationDate'] = $creationDate[1];

                                } else {
                                    $arrname[$loc][$cDir]['creationDate'] = '-';

                                }

                                if ( preg_match( '#<authorUrl>(.*)</authorUrl>#', $content, $authorUrl ) ) {
                                    $arrname[$loc][$cDir]['authorUrl'] = str_replace( array( 'http://', 'https://' ), '', $authorUrl[1] );

                                } else {
                                    $arrname[$loc][$cDir]['authorUrl'] = '-';

                                }

                        } //isValidFile

                    }

                }

            }

        }
        @closedir( $dh );
    }

    @getDetails( 'components', $component, 'SITE' );
    @getDetails( 'administrator/components', $component, 'ADMIN' );

    @getDetails( 'modules', $module, 'SITE' );
    @getDetails( 'administrator/modules', $module, 'ADMIN' );

    // cater for Joomla! 1.0 differences
    if ( @$instance['cmsRELEASE'] == '1.0' ) {
        @getDetails( 'mambots', $plugin, 'SITE' );
    } else {
        @getDetails( 'plugins', $plugin, 'SITE' );

    }

    @getDetails( 'templates', $template, 'SITE' );
    @getDetails( 'administrator/templates', $template, 'ADMIN' );



    } // end if instanceFOUND
?>









        <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo _RES .' : v'. _RES_VERSION .' ('. _RES_RELEASE .')';?></title>

        <?php //!TODO different icons ?>
        <link rel="shortcut icon" href="./templates/rhuk_milkyway/favicon.ico" />

        <style type="text/css" media="screen">
            html, body, div, p, span {
                font-size: 10px;
                font-family: tahoma, arial;
                color: #404040;
            }

             .dev-mode-information {
                margin: 0 auto;
                margin-top:10px;
                margin-bottom:10px;
                padding: 5px;
                width:750px;
                background-color:#CAFFD8;
                border:1px solid #4D8000;
                /** CSS3 **/
                box-shadow: 3px 3px 3px #C0C0C0;
                -moz-box-shadow: 3px 3px 3px #C0C0C0;
                -webkit-box-shadow: 3px 3px 3px #C0C0C0;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .dev-mode-title {
                color: #4D8000;
                font-weight: bold;
                /** CSS3 **/
                text-shadow: 1px 1px 1px #FFF;
            }

             .snapshot-information {
                margin: 0 auto;
                margin-top:10px;
                margin-bottom:10px;
                padding: 5px;
                width:740px;
                background-color:#F3EFE0;
                border:1px solid #999966;
                /** CSS3 **/
                box-shadow: 3px 3px 3px #C0C0C0;
                -moz-box-shadow: 3px 3px 3px #C0C0C0;
                -webkit-box-shadow: 3px 3px 3px #C0C0C0;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .header-information {
                margin: 0px auto;
                margin-top:10px;
                margin-bottom:10px;
                padding: 5px;
                width:740px;
                background-color:#E0FFFF;
                border:1px solid #42AEC2;
                /** CSS3 **/
                box-shadow: 3px 3px 3px #C0C0C0;
                -moz-box-shadow: 3px 3px 3px #C0C0C0;
                -webkit-box-shadow: 3px 3px 3px #C0C0C0;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .header-title {
                color: #404040;
                text-align:center;
                font-size: 14px;
                font-weight: bold;
                padding: 1px;
                text-transform: uppercase;
                margin-left: 1px;
                margin-right: 1px;
                margin-top: 2px;
                margin-bottom: 2px;
                /** CSS3 **/
                text-shadow: 1px 1px 1px #FFF;
            }

            .header-column-title {
                color: #404040;
                font-weight: normal;
                padding: 1px;
                /** CSS3 **/
                text-shadow: 1px 1px 1px #FFF;
            }

            .section-information {
                margin: 0px auto;
                margin-top:10px;
                margin-bottom:10px;
                padding: 5px;
                width:740px;
                background-color:#E0FFFF;
                border:1px solid #42AEC2;
                min-height: 188px;
                /** CSS3 **/
                box-shadow: 3px 3px 3px #C0C0C0;
                -moz-box-shadow: 3px 3px 3px #C0C0C0;
                -webkit-box-shadow: 3px 3px 3px #C0C0C0;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .half-section-container {
                margin: 0px auto;
                padding: 5px;
                width:750px;
            }

            .half-section-information-left {
                float:left;
                min-height: 188px;
                margin-top:10px;
                margin-bottom:10px;
                padding: 5px;
                width:355px;
                background-color:#E0FFFF;
                border:1px solid #42AEC2;
                /** CSS3 **/
                box-shadow: 3px 3px 3px #C0C0C0;
                -moz-box-shadow: 3px 3px 3px #C0C0C0;
                -webkit-box-shadow: 3px 3px 3px #C0C0C0;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .half-section-information-right {
                float:right;
                min-height: 188px;
                margin-top:10px;
                margin-bottom:10px;
                padding: 5px;
                width:355px;
                background-color:#E0FFFF;
                border:1px solid #42AEC2;
                /** CSS3 **/
                box-shadow: 3px 3px 3px #C0C0C0;
                -moz-box-shadow: 3px 3px 3px #C0C0C0;
                -webkit-box-shadow: 3px 3px 3px #C0C0C0;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .section-title {
                color: #404040;
                font-size: 12px;
                font-weight: bold;
                padding: 1px;
                text-transform: uppercase;
                margin-left: 1px;
                margin-right: 1px;
                margin-top: 2px;
                margin-bottom: 2px;
                /** CSS3 **/
                text-shadow: 1px 1px 1px #F5F5F5;
            }

            .mini-content-container {
                font-size: 9px !important;
                float:left;
                width: 83px;
                height: 75px;
                margin: 2px;
            }

            .mini-content-title {
                font-size: 8px;
                font-weight: bold;
                text-align:center;
                margin: 0px auto;
                margin-bottom: 4px !important;
                border-bottom: 1px solid #C0C0C0;
                text-transform: uppercase;
                /** CSS3 **/
                text-shadow: 1px 1px 1px #FFF;
            }

            .mini-content-box {
                font-size: 10px !important;
                text-align:center;
                margin: 0px auto;
                padding: 4px;
                border: 1px solid #42AEC2;
                height: 45px;
                background-color: #FFFFFF;
                /** CSS3 **/
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .mini-content-box-small {
                font-size: 9px !important;
                text-align:center;
                margin: 0px auto;
                padding-left: 2px;
                text-align: left;
            }

            .column-title-container {
                background-color: #42AEC2;
                /** CSS3 **/
                border-top-left-radius: 5px;
                -moz-border-radius-topleft: 5px;
                -webkit-border-top-left: 5px;
                border-top-right-radius: 5px;
                -moz-border-radius-topright: 5px;
                -webkit-border-top-right: 5px;
            }

            .column-title {
                color: #E0FFFF;
                font-weight: bold;
                padding: 1px;
                text-transform: uppercase;
                margin-left: 1px;
                margin-right: 1px;
                margin-top: 2px;
                margin-bottom: 2px;
                /** CSS3 **/
                text-shadow: 1px 1px 1px #808080;
            }

            .row-content-container {
                border-bottom: 1px dotted #C0C0C0;
                width: 99%;
                margin: 0px auto;
                clear:both;
            }

            .nothing-to-display {
                text-align: center;
                font-size: 11px;
            }

            .column-content {
                margin-left: 1px;
                margin-right: 1px;
            }

            .normal {
                color: #404040;
            }

            .normal-note {
                color:#4D8000;
                background-color: #FFF;
                padding:2px;
                margin-left: 5px;
                margin-right: 5px;
                font-weight:normal;
                border:1px solid #4D8000;
                /** CSS3 **/
                box-shadow: 2px 2px 2px #C0C0C0;
                -moz-box-shadow: 2px 2px 2px #C0C0C0;
                -webkit-box-shadow: 2px 2px 2px #C0C0C0;
                border-radius: 3px;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                text-shadow: 1px 1px 1px #F5F5F5;
            }

            .ok {
                color: #008000;
            }

            .ok-hilite {
                background-color: #CAFFD8;
                color: #008000;
                border: 1px solid #4D8000;
                /** CSS3 **/
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .warn {
                background-color: #FFE4B5;
                color: #800000;
                border: 1px solid #FFA500;
                /** CSS3 **/
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .warn-text {
                color: #FFA500;
            }

            .alert {
                background-color: #FFFF00;
                color: #800000;
                border: 1px solid #800000;
                /** CSS3 **/
                border-radius: %px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
            }

            .alert-text {
                color: #800000;
            }

            .protected {
                color: #f60;
                background-color: #FFFFCC;
                line-height:9px;
                font-size:9px;
                font-weight:normal;
                text-transform:none;
            }
        </style>


        <!-- Show/Hide Post Form -->
        <script type="text/javascript">
        function toggle2(showHideDiv, switchTextDiv) {
            var ele = document.getElementById(showHideDiv);
            var text = document.getElementById(switchTextDiv);

            if ( ele.style.display == "block" ) {
                ele.style.display = "none";
            	text.innerHTML = "<span style=\"font-size:12px;color:#4D8000;\"><span style=\"font-size:18px;color:#008000;\">&Theta;</span> Show the <strong><?php echo _RES; ?></strong></span>";
            } else {
            	ele.style.display = "block";
            	text.innerHTML = "<span style=\"font-size:12px;color:#800000;\"><span style=\"font-size:20px;color:#800000;\">&otimes;</span> Hide the <strong><?php echo _RES; ?></strong></span>";
            }
        }
        </script>

        <script>
            document.all["slowScreenSplash"].style.display = "none";
        </script>

        </head>
    <body>

<?php
    /** display the fpa heading ***************************************************************/
    echo '<div class="snapshot-information">';
    echo '<div class="header-title" style="">'. _RES .'</div>';
    echo '<div class="header-column-title" style="text-align:center;">'. _FPA_VER .': v'. _RES_VERSION .'-'. _RES_RELEASE .' ('. _RES_BRANCH .')</div>';

    // Environment Snapshot
    echo '<br />';

    /** ENVIRONMENT SUPPORT NOTICE ************************************************************
     ** this section displays a message explaining if the system, mysql and php environment
     ** can support the discovered version of Joomla!
     **
     ** REQUIREMENTS: (as far as I know!)
     **          ____________________________________________
     **          | J1.7/6 | J!1.5.0-14 | J1.5.0-23 |  J!1.0  |
     ** ------------------------------------------------------
     ** MIN PHP  | 5.2.4  |   4.3.10   |  4.3.10   |  3.0.1  |
     ** MAX PHP  | -----  |   5.2.17   |  5.3.6    |  4.4.9  | // 5.0.0 was first release to include MySQLi support
     ** ------------------------------------------------------
     ** MIN MYSQL| 5.0.4  |   3.23.0   |  3.23.0   |  3.0.0  |
     ** MAX MYSQL| -----  |   5.1.43   |  5.1.43   |  5.0.91 | // only limited to 4.1.29 & 5.1 because install sql still has ENGINE TYPE statements (removed in MySQL 5.5)
     ** ------------------------------------------------------
     ** BAD PHP  | -----  |   4.39, 4.4.2, 5.0.5   |  -----  |
     ** BAD SQL  | -----  |        >5.0.0          |  -----  |
     ** BAD ZEND | -----  |         2.5.10         |  -----  |
     *****************************************************************************************/
    $fpa['supportENV'] = '';

    define ( '_FPA_SUPPHP', 'PHP Supports');
    define ( '_FPA_SUPSQL', 'MySQL Supports');
    define ( '_FPA_BADPHP', 'Known Buggy PHP');
    define ( '_FPA_BADZND', 'Known Buggy Zend');

    echo '<div>';
//    echo '<div class="normal-note" style="text-align:center;color:#4D8000!important;padding-top:10px;">';
//    echo '<div class="snapshot-information" style="text-align:center;color:#4D8000!important;padding-top:10px;">';
//    echo '<span class="header-title">'. $snapshot['ARRNAME'] .'</span>';
    echo '<div style="width:85%;margin:0 auto;margin-top:10px;">';

    /** SUPPORT SECTIONS *************************************************************/
    if ( @$instance['cmsRELEASE'] == '1.7' ) {
        $fpa['supportENV']['minPHP']        = '5.2.4';
        $fpa['supportENV']['minSQL']        = '5.0.4';
        $fpa['supportENV']['maxPHP']        = '6.0.0';  // latest release?
        $fpa['supportENV']['maxSQL']        = '5.5.0';  // latest release?
        $fpa['supportENV']['badPHP'][0]     = _FPA_NA;
        $fpa['supportENV']['badZND'][0]     = _FPA_NA;

    } elseif ( @$instance['cmsRELEASE'] == '1.6' ) {
        $fpa['supportENV']['minPHP']        = '5.2.4';
        $fpa['supportENV']['minSQL']        = '5.0.4';
        $fpa['supportENV']['maxPHP']        = '6.0.0';  // latest release?
        $fpa['supportENV']['maxSQL']        = '5.5.0';  // latest release?
        $fpa['supportENV']['badPHP'][0]     = _FPA_NA;
        $fpa['supportENV']['badZND'][0]     = _FPA_NA;

    } elseif ( @$instance['cmsRELEASE'] == '1.5' ) {

        if ( @$instance['cmsDEVLEVEL'] <= '14' ) {
            $fpa['supportENV']['minPHP']        = '4.3.10';
            $fpa['supportENV']['minSQL']        = '3.23.0';
            $fpa['supportENV']['maxPHP']        = '5.2.17';
            $fpa['supportENV']['maxSQL']        = '5.5.0';  // limited by ENGINE TYPE changes in 5.5 and install sql syntax

        } else {
            $fpa['supportENV']['minPHP']        = '4.3.10';
            $fpa['supportENV']['minSQL']        = '3.23.0';
            $fpa['supportENV']['maxPHP']        = '5.3.6';
            $fpa['supportENV']['maxSQL']        = '5.5.0';  // limited by ENGINE TYPE changes in 5.5 and install sql syntax

        }

        $fpa['supportENV']['badPHP'][0]     = '4.3.9';
        $fpa['supportENV']['badPHP'][1]     = '4.4.2';
        $fpa['supportENV']['badPHP'][2]     = '5.0.4';
        $fpa['supportENV']['badZND'][0]     = '2.5.10';

    } elseif ( @$instance['cmsRELEASE'] == '1.0' ) {
        $fpa['supportENV']['minPHP']        = '3.0.1';
        $fpa['supportENV']['minSQL']        = '3.0.0';
        $fpa['supportENV']['maxPHP']        = '4.4.9';
        $fpa['supportENV']['maxSQL']        = '5.0.91';  // limited by ENGINE TYPE changes in 5.0 and install sql syntax
        $fpa['supportENV']['badPHP'][0]     = _FPA_NA;
        $fpa['supportENV']['badZND'][0]     = _FPA_NA;

    } else {
        $fpa['supportENV']['minPHP']        = _FPA_NA;
        $fpa['supportENV']['minSQL']        = _FPA_NA;
        $fpa['supportENV']['maxPHP']        = _FPA_NA;
        $fpa['supportENV']['maxSQL']        = _FPA_NA;
        $fpa['supportENV']['badPHP'][0]     = _FPA_NA;
        $fpa['supportENV']['badZND'][0]     = _FPA_NA;
    }



    // minimum and maximum PHP support requirements met?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPPHP .' J!'. @$instance['cmsRELEASE'] .'<br />';

        if ( $fpa['supportENV']['minPHP'] == _FPA_NA ) {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['phpSUP4J'] = _FPA_U;

        } elseif ( ( version_compare( PHP_VERSION, $fpa['supportENV']['minPHP'], '>=' ) ) AND ( version_compare( PHP_VERSION, $fpa['supportENV']['maxPHP'], '<=' ) ) ) {
            echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';
            $snapshot['phpSUP4J'] = _FPA_Y;

        } elseif ( ( version_compare( PHP_VERSION, $fpa['supportENV']['minPHP'], '<' ) ) OR ( version_compare( PHP_VERSION, $fpa['supportENV']['maxPHP'], '>' ) ) ) {
            echo '<div class="normal-note"><span class="alert-text">'. _FPA_N .'</span></div>';
            $snapshot['phpSUP4J'] = _FPA_N;

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['phpSUP4J'] = _FPA_U;

        }

    echo '</div>';


    // PHP API
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">PHP API<br />';

        if ( @$phpenv['phpAPI'] ) {

            if ( @$phpenv['phpAPI'] == 'apache2handler' ) {
                echo '<div class="normal-note"><span class="warn-text">'. @$phpenv['phpAPI'] .'</span></div>';

            } else {
                echo '<div class="normal-note"><span class="ok">'. @$phpenv['phpAPI'] .'</span></div>';

            }

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';

        }

    echo '</div>';


    // MySQL supported by PHP?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPPHP .' MySQL<br />';

        if ( array_key_exists( 'mysql', $phpextensions ) ) {
            echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';
            $snapshot['phpSUP4MYSQL'] = _FPA_Y;

        } else {
            echo '<div class="normal-note"><span class="alert-text">'. _FPA_N .'</span></div>';
            $snapshot['phpSUP4MYSQL'] = _FPA_N;

        }

    echo '</div>';


    // MySQLi supported by PHP?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPPHP .' MySQLi<br />';

        if ( array_key_exists( 'mysqli', $phpextensions ) ) {
            echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';
            $snapshot['phpSUP4MYSQL-i'] = _FPA_Y;

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_N .'</span></div>';
            $snapshot['phpSUP4MYSQL-i'] = _FPA_N;

        }

    echo '</div>';

    echo '<br style="clear:both;" /><br />';

    // minimum and maximum MySQL support requirements met?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPSQL .' J!'. @$instance['cmsRELEASE'] .'<br />';

        if ( $fpa['supportENV']['minSQL'] == _FPA_NA OR @$database['dbERROR'] != _FPA_N ) {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['sqlSUP4J'] = _FPA_U;

        } elseif ( ( version_compare( @$database['dbHOSTSERV'], $fpa['supportENV']['minSQL'], '>=' ) ) AND ( version_compare( @$database['dbHOSTSERV'], $fpa['supportENV']['maxSQL'], '<=' ) ) ) {

            // WARNING, will run, but ONLY after modifying install SQL to remove ENGINE TYPE statements (removed in MySQL 5.5)
            if ( ( $instance['cmsRELEASE'] == '1.5' ) AND ( @$database['dbHOSTSERV'] > '5.1.43' ) ) {
                echo '<div class="normal-note"><span class="warn-text">'. _FPA_M .' (<a href="http://forum.joomla.org/viewtopic.php?p=2297327" target="_new">SQL Updates</a>)</span></div>';
                $snapshot['sqlSUP4J'] = _FPA_M;

            } else {
                echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';
                $snapshot['sqlSUP4J'] = _FPA_Y;

            }

        } elseif ( ( version_compare( @$database['dbHOSTSERV'], $fpa['supportENV']['minSQL'], '<' ) ) OR ( version_compare( @$database['dbHOSTSERV'], $fpa['supportENV']['maxSQL'], '>' ) ) ) {

            // WARNING, will run, but ONLY after modifying install SQL to remove ENGINE TYPE statements (removed in MySQL 5.5)
            if ( ( $instance['cmsRELEASE'] == '1.5' ) AND ( @$database['dbHOSTSERV'] > '5.1.43' ) ) {
                echo '<div class="normal-note"><span class="warn-text">'. _FPA_M .' (<a href="http://forum.joomla.org/viewtopic.php?p=2297327" target="_new">SQL Updates</a>)</span></div>';
                $snapshot['sqlSUP4J'] = _FPA_M;

            } else {
                echo '<div class="normal-note"><span class="alert-text">'. _FPA_N .'</span></div>';
                $snapshot['sqlSUP4J'] = _FPA_N;

            }

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['sqlSUP4J'] = _FPA_U;

        }

    echo '</div>';


    // MySQLi supported by MySQL?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPSQL .' MySQLi<br />';

        if ( !@$database['dbHOSTSERV'] OR @$database['dbERROR'] != _FPA_N ) {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['sqlSUP4SQL-i'] = _FPA_U;

        } elseif ( version_compare( @$database['dbHOSTSERV'], '5.0.7', '>=' ) ) {
            echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_N .'</span></div>';
            $snapshot['sqlSUP4SQL-i'] = _FPA_N;

        }

    echo '</div>';


    // J! connection to MySQL
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">MySQL Connection Type<br />';

        if ( @$instance['configDBTYPE'] ) {

            if ( ( @$snapshot['sqlSUP4SQL-i'] == _FPA_N OR @$snapshot['sqlSUP4SQL-i'] == _FPA_U ) AND @$instance['configDBTYPE'] == 'mysqli') {
                echo '<div class="normal-note"><span class="alert-text">'. @$instance['configDBTYPE'] .'</span></div>';

            } else {
                echo '<div class="normal-note"><span class="ok">'. @$instance['configDBTYPE'] .'</span></div>';

            }

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';

        }

    echo '</div>';


    // MySQL default collation
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">MySQL Default Collation<br />';

        if ( @$database['dbHOSTDEFCHSET'] ) {
            echo '<div class="normal-note"><span class="ok">'. @$database['dbHOSTDEFCHSET'] .'</span></div>';

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';

        }

    echo '</div>';

    echo '<br style="clear:both;" /><br />';

    // Unsupported PHP version
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">PHP Version<br />';

        if ( version_compare( PHP_VERSION, '5', '<' ) ) {
            echo '<div class="normal-note"><span class="alert-text">'. PHP_VERSION .'</span></div>';

        } else {
            echo '<div class="normal-note"><span class="ok">'. PHP_VERSION .'</span></div>';

        }

    echo '</div>';


    // known buggy php releases (mainly for installation on 1.5)
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_BADPHP .'<br />';

        foreach ( $fpa['supportENV']['badPHP'] as $badKey => $badValue ) {
            if ( version_compare( PHP_VERSION, $badValue, '==' ) ) {
                $badANS = _FPA_Y;
                continue;

            }

        }

        if ( @$badANS == _FPA_Y ) {
            $badClass = 'alert-text';
            $snapshot['buggyPHP'] = _FPA_N;

        } else {
            $badANS = _FPA_N;
            $badClass = 'ok';
            $snapshot['buggyPHP'] = _FPA_N;

        }

    echo '<div class="normal-note"><span class="'. $badClass .'">'. $badANS .'</span></div>';

    echo '</div>';


    // MySQL Version
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">MySQL Version<br />';
    echo '<div class="normal-note">';
        if ( @$database['dbHOSTSERV'] ) {
            echo @$database['dbHOSTSERV'];
        } else {
            echo _FPA_U;
        }
    echo '</div>';
    echo '</div>';


    // known buggy zend releases (mainly for installation on 1.5)
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_BADZND .'<br />';

        foreach ( $fpa['supportENV']['badZND'] as $badKey => $badValue ) {

            if ( version_compare( $phpextensions['Zend Engine'], $badValue, '==' ) ) {
                $badANS = _FPA_Y;
                continue;

            }

        }

        if ( @$badANS == _FPA_Y ) {
            $badClass = 'alert-text';
            $snapshot['buggyZEND'] = _FPA_Y;

        } else {
            $badANS = _FPA_N;
            $badClass = 'ok';
            $snapshot['buggyZEND'] = _FPA_N;

        }

    echo '<div class="normal-note"><span class="'. $badClass .'">'. $badANS .'</span></div>';

    echo '</div>';


    echo '</div>';
    echo '<div style="clear:both;"><br /></div>';

    echo '</div>';



    //TEST
    echo '<div style="text-align:center;"><a style="color:#4D8000!important;" href="'. _RES_FPALINK .'" target="_github">'. _RES_FPALATEST .' '. _RES .'</a></div>';
    echo '<div style="clear:both;"></div>';
    echo '</div>';

    showDev ( $snapshot );
    ?>



<?php
    /** ENVIRONMENT SUPPORT NOTICE ************************************************************
     ** this section displays a message explaining if the system, mysql and php environment
     ** can support the discovered version of Joomla!
     **
     ** REQUIREMENTS: (as far as I know!)
     **          ____________________________________________
     **          | J1.7/6 | J!1.5.0-14 | J1.5.0-23 |  J!1.0  |
     ** ------------------------------------------------------
     ** MIN PHP  | 5.2.4  |   4.3.10   |  4.3.10   |  3.0.1  |
     ** MAX PHP  | -----  |   5.2.17   |  5.3.6    |  4.4.9  | // 5.0.0 was first release to include MySQLi support
     ** ------------------------------------------------------
     ** MIN MYSQL| 5.0.4  |   3.23.0   |  3.23.0   |  3.0.0  |
     ** MAX MYSQL| -----  |   5.1.43   |  5.1.43   |  4.1.25 | // only limited to 4.1.29 & 5.1 because install sql still has ENGINE TYPE statements (removed in MySQL 5.5)
     ** ------------------------------------------------------
     ** BAD PHP  | -----  |   4.39, 4.4.2, 5.0.5   |  -----  |
     ** BAD SQL  | -----  |        >5.0.0          |  -----  |
     ** BAD ZEND | -----  |         2.5.10         |  -----  |
     *****************************************************************************************/

/** MOVED
    $fpa['supportENV'] = '';

    define ( '_FPA_SUPPHP', 'PHP Supports');
    define ( '_FPA_SUPSQL', 'MySQL Supports');
    define ( '_FPA_BADPHP', 'Known Buggy PHP');
    define ( '_FPA_BADZND', 'Known Buggy Zend');

    echo '<div class="snapshot-information" style="text-align:center;color:#4D8000!important;padding-top:10px;">';
    echo '<span class="header-title">'. $snapshot['ARRNAME'] .'</span>';
    echo '<div style="width:85%;margin:0 auto;margin-top:10px;">';
MOVED **/
    /** SUPPORT SECTIONS *************************************************************/
/** MOVED
    if ( @$instance['cmsRELEASE'] == '1.7' ) {
        $fpa['supportENV']['minPHP']        = '5.2.4';
        $fpa['supportENV']['minSQL']        = '5.0.4';
        $fpa['supportENV']['maxPHP']        = '6.0.0';  // latest release?
        $fpa['supportENV']['maxSQL']        = '5.5.0';  // latest release?
        $fpa['supportENV']['badPHP'][0]     = _FPA_NA;
        $fpa['supportENV']['badZND'][0]     = _FPA_NA;

    } elseif ( @$instance['cmsRELEASE'] == '1.6' ) {
        $fpa['supportENV']['minPHP']        = '5.2.4';
        $fpa['supportENV']['minSQL']        = '5.0.4';
        $fpa['supportENV']['maxPHP']        = '6.0.0';  // latest release?
        $fpa['supportENV']['maxSQL']        = '5.5.0';  // latest release?
        $fpa['supportENV']['badPHP'][0]     = _FPA_NA;
        $fpa['supportENV']['badZND'][0]     = _FPA_NA;

    } elseif ( @$instance['cmsRELEASE'] == '1.5' ) {

        if ( @$instance['cmsDEVLEVEL'] <= '14' ) {
            $fpa['supportENV']['minPHP']        = '4.3.10';
            $fpa['supportENV']['minSQL']        = '3.23.0';
            $fpa['supportENV']['maxPHP']        = '5.2.17';
            $fpa['supportENV']['maxSQL']        = '5.5.0';  // limited by ENGINE TYPE changes in 5.5 and install sql syntax

        } else {
            $fpa['supportENV']['minPHP']        = '4.3.10';
            $fpa['supportENV']['minSQL']        = '3.23.0';
            $fpa['supportENV']['maxPHP']        = '5.3.6';
            $fpa['supportENV']['maxSQL']        = '5.5.0';  // limited by ENGINE TYPE changes in 5.5 and install sql syntax

        }

        $fpa['supportENV']['badPHP'][0]     = '4.3.9';
        $fpa['supportENV']['badPHP'][1]     = '4.4.2';
        $fpa['supportENV']['badPHP'][2]     = '5.0.4';
        $fpa['supportENV']['badZND'][0]     = '2.5.10';

    } elseif ( @$instance['cmsRELEASE'] == '1.0' ) {
        $fpa['supportENV']['minPHP']        = '3.0.1';
        $fpa['supportENV']['minSQL']        = '3.0.0';
        $fpa['supportENV']['maxPHP']        = '4.4.9';
        $fpa['supportENV']['maxSQL']        = '4.1.25';  // limited by ENGINE TYPE changes in 5.0 and install sql syntax
        $fpa['supportENV']['badPHP'][0]     = _FPA_NA;
        $fpa['supportENV']['badZND'][0]     = _FPA_NA;

    } else {
        $fpa['supportENV']['minPHP']        = _FPA_NA;
        $fpa['supportENV']['minSQL']        = _FPA_NA;
        $fpa['supportENV']['maxPHP']        = _FPA_NA;
        $fpa['supportENV']['maxSQL']        = _FPA_NA;
        $fpa['supportENV']['badPHP'][0]     = _FPA_NA;
        $fpa['supportENV']['badZND'][0]     = _FPA_NA;
    }



    // minimum and maximum PHP support requirements met?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPPHP .' J!'. @$instance['cmsRELEASE'] .'<br />';

        if ( $fpa['supportENV']['minPHP'] == _FPA_NA ) {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['phpSUP4J'] = _FPA_U;

        } elseif ( ( version_compare( PHP_VERSION, $fpa['supportENV']['minPHP'], '>=' ) ) AND ( version_compare( PHP_VERSION, $fpa['supportENV']['maxPHP'], '<=' ) ) ) {
            echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';
            $snapshot['phpSUP4J'] = _FPA_Y;

        } elseif ( ( version_compare( PHP_VERSION, $fpa['supportENV']['minPHP'], '<' ) ) OR ( version_compare( PHP_VERSION, $fpa['supportENV']['maxPHP'], '>' ) ) ) {
            echo '<div class="normal-note"><span class="alert-text">'. _FPA_N .'</span></div>';
            $snapshot['phpSUP4J'] = _FPA_N;

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['phpSUP4J'] = _FPA_U;

        }

    echo '</div>';


    // PHP API
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">PHP API<br />';

        if ( @$phpenv['phpAPI'] ) {

            if ( @$phpenv['phpAPI'] == 'apache2handler' ) {
                echo '<div class="normal-note"><span class="warn-text">'. @$phpenv['phpAPI'] .'</span></div>';

            } else {
                echo '<div class="normal-note"><span class="ok">'. @$phpenv['phpAPI'] .'</span></div>';

            }

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';

        }

    echo '</div>';


    // MySQL supported by PHP?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPPHP .' MySQL<br />';

        if ( array_key_exists( 'mysql', $phpextensions ) ) {
            echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';
            $snapshot['phpSUP4MYSQL'] = _FPA_Y;

        } else {
            echo '<div class="normal-note"><span class="alert-text">'. _FPA_N .'</span></div>';
            $snapshot['phpSUP4MYSQL'] = _FPA_N;

        }

    echo '</div>';


    // MySQLi supported by PHP?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPPHP .' MySQLi<br />';

        if ( array_key_exists( 'mysqli', $phpextensions ) ) {
            echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';
            $snapshot['phpSUP4MYSQL-i'] = _FPA_Y;

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_N .'</span></div>';
            $snapshot['phpSUP4MYSQL-i'] = _FPA_N;

        }

    echo '</div>';

    echo '<br style="clear:both;" /><br />';

    // minimum and maximum MySQL support requirements met?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPSQL .' J!'. @$instance['cmsRELEASE'] .'<br />';

        if ( $fpa['supportENV']['minSQL'] == _FPA_NA OR @$database['dbERROR'] != _FPA_N ) {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['sqlSUP4J'] = _FPA_U;

        } elseif ( ( version_compare( @$database['dbHOSTSERV'], $fpa['supportENV']['minSQL'], '>=' ) ) AND ( version_compare( @$database['dbHOSTSERV'], $fpa['supportENV']['maxSQL'], '<=' ) ) ) {

            // WARNING, will run, but ONLY after modifying install SQL to remove ENGINE TYPE statements (removed in MySQL 5.5)
            if ( ( $instance['cmsRELEASE'] == '1.5' ) AND ( @$database['dbHOSTSERV'] > '5.1.43' ) ) {
                echo '<div class="normal-note"><span class="warn-text">'. _FPA_M .' (<a href="http://forum.joomla.org/viewtopic.php?p=2297327" target="_new">SQL Updates</a>)</span></div>';
                $snapshot['sqlSUP4J'] = _FPA_M;

            } else {
                echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';
                $snapshot['sqlSUP4J'] = _FPA_Y;

            }

        } elseif ( ( version_compare( @$database['dbHOSTSERV'], $fpa['supportENV']['minSQL'], '<' ) ) OR ( version_compare( @$database['dbHOSTSERV'], $fpa['supportENV']['maxSQL'], '>' ) ) ) {

            // WARNING, will run, but ONLY after modifying install SQL to remove ENGINE TYPE statements (removed in MySQL 5.5)
            if ( ( $instance['cmsRELEASE'] == '1.5' ) AND ( @$database['dbHOSTSERV'] > '5.1.43' ) ) {
                echo '<div class="normal-note"><span class="warn-text">'. _FPA_M .' (<a href="http://forum.joomla.org/viewtopic.php?p=2297327" target="_new">SQL Updates</a>)</span></div>';
                $snapshot['sqlSUP4J'] = _FPA_M;

            } else {
                echo '<div class="normal-note"><span class="alert-text">'. _FPA_N .'</span></div>';
                $snapshot['sqlSUP4J'] = _FPA_N;

            }

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['sqlSUP4J'] = _FPA_U;

        }

    echo '</div>';


    // MySQLi supported by MySQL?
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_SUPSQL .' MySQLi<br />';

        if ( !@$database['dbHOSTSERV'] OR @$database['dbERROR'] != _FPA_N ) {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';
            $snapshot['sqlSUP4SQL-i'] = _FPA_U;

        } elseif ( version_compare( @$database['dbHOSTSERV'], '5.0.7', '>=' ) ) {
            echo '<div class="normal-note"><span class="ok">'. _FPA_Y .'</span></div>';

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_N .'</span></div>';
            $snapshot['sqlSUP4SQL-i'] = _FPA_N;

        }

    echo '</div>';


    // J! connection to MySQL
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">MySQL Connection Type<br />';

        if ( @$instance['configDBTYPE'] ) {

            if ( ( @$snapshot['sqlSUP4SQL-i'] == _FPA_N OR @$snapshot['sqlSUP4SQL-i'] == _FPA_U ) AND @$instance['configDBTYPE'] == 'mysqli') {
                echo '<div class="normal-note"><span class="alert-text">'. @$instance['configDBTYPE'] .'</span></div>';

            } else {
                echo '<div class="normal-note"><span class="ok">'. @$instance['configDBTYPE'] .'</span></div>';

            }

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';

        }

    echo '</div>';


    // MySQL default collation
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">MySQL Default Collation<br />';

        if ( @$database['dbHOSTDEFCHSET'] ) {
            echo '<div class="normal-note"><span class="ok">'. @$database['dbHOSTDEFCHSET'] .'</span></div>';

        } else {
            echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';

        }

    echo '</div>';

    echo '<br style="clear:both;" /><br />';

    // Unsupported PHP version
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">PHP Version<br />';

        if ( version_compare( PHP_VERSION, '5', '<' ) ) {
            echo '<div class="normal-note"><span class="alert-text">'. PHP_VERSION .'</span></div>';

        } else {
            echo '<div class="normal-note"><span class="ok">'. PHP_VERSION .'</span></div>';

        }

    echo '</div>';


    // known buggy php releases (mainly for installation on 1.5)
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_BADPHP .'<br />';

        foreach ( $fpa['supportENV']['badPHP'] as $badKey => $badValue ) {
            if ( version_compare( PHP_VERSION, $badValue, '==' ) ) {
                $badANS = _FPA_Y;
                continue;

            }

        }

        if ( @$badANS == _FPA_Y ) {
            $badClass = 'alert-text';
            $snapshot['buggyPHP'] = _FPA_N;

        } else {
            $badANS = _FPA_N;
            $badClass = 'ok';
            $snapshot['buggyPHP'] = _FPA_N;

        }

    echo '<div class="normal-note"><span class="'. $badClass .'">'. $badANS .'</span></div>';

    echo '</div>';


    // known buggy zend releases (mainly for installation on 1.5)
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">'. _FPA_BADZND .'<br />';

        foreach ( $fpa['supportENV']['badZND'] as $badKey => $badValue ) {

            if ( version_compare( $phpextensions['Zend Engine'], $badValue, '==' ) ) {
                $badANS = _FPA_Y;
                continue;

            }

        }

        if ( @$badANS == _FPA_Y ) {
            $badClass = 'alert-text';
            $snapshot['buggyZEND'] = _FPA_Y;

        } else {
            $badANS = _FPA_N;
            $badClass = 'ok';
            $snapshot['buggyZEND'] = _FPA_N;

        }

    echo '<div class="normal-note"><span class="'. $badClass .'">'. $badANS .'</span></div>';

    echo '</div>';


    // if Apache, is mod_rewrite installed (for SEF URL's)
    echo '<div style="font-weight:bold;font-size:9px;text-transform:uppercase;width:24%;float:left;text-align:center;">Apache mod_rewrite<br />';

        if ( @$apachemodules['ARRNAME'] ) {

            foreach ( $apachemodules as $key => $show ) {

                if ( $show == 'mod_rewrite' ) {
                    $modANS = _FPA_Y;
                    continue;

                }

            }

            if ( @$modANS == _FPA_Y ) {
                $modClass = 'ok';

            } else {
                $modANS = _FPA_N;
                $modClass = 'warn-text';

            }

        echo '<div class="normal-note"><span class="'. $modClass .'">'. $modANS .'</span></div>';

            } else {
                echo '<div class="normal-note"><span class="warn-text">'. _FPA_U .'</span></div>';

            }

    echo '</div>';

    echo '</div>';
    echo '<div style="clear:both;"><br /></div>';

    echo '</div>';

    showDev( $snapshot );
MOVED **/
?>




    <!-- POST FORM -->
    <div style="margin: 0px auto;text-align:left;text-shadow: 1px 1px 1px #FFF; width:740px; background-color:#FFF;border:1px solid #999966; color:#4D8000; font-size:10px; font-family:arial; padding:5px;-moz-box-shadow: 3px 3px 3px #C0C0C0;-webkit-box-shadow: 3px 3px 3px #C0C0C0;box-shadow: 3px 3px 3px #C0C0C0;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
<!-- <div style="margin: 0px auto;text-align:left;text-shadow: 1px 1px 1px #FFF; width:740px; background-color:#F3EFE0;border:1px solid #999966; color:#4D8000; font-size:10px; font-family:arial; padding:5px;-moz-box-shadow: 3px 3px 3px #C0C0c0;-webkit-box-shadow: 3px 3px 3px #C0C0c0;box-shadow: 3px 3px 3px #C0C0c0;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;"> -->
        <div id="headerDiv" class="">

            <?php
                // someone click the "Generate Post" button, so we want to keep the form open once the page refreshes
                if ( @$_POST['doIT'] == 1 ) {
                    $displayMode = 'block';

                } else {
                    $displayMode = 'none';

                }
            ?>

            <div id="titleText"><a id="myHeader" style="line-height:22px;text-decoration:none;color:#4D8000;" href="javascript:toggle2('myContent','myHeader');" ><span style="font-size:12px;color:#4D8000;"><span style="font-size:18px;color:#008000;">&Theta;</span> Show the <strong><?php echo _RES; ?></strong></span></a></div>

        </div>
        <div style="clear:both;"></div>
        <div id="contentDiv" style="">

            <div id="myContent" style="display: <?php echo $displayMode; ?>;">


            <?php
                //if ( defined( '_FPA_BRA' ) ) { // bug report post form
                // if the Bug Report is used, it might have to show different information
                //}
            ?>

                <div class="half-section-container" style="width:730px;">

                    <div class="half-section-information-left" style="width:340px;padding-top:10px;padding-bottom:10px;border-color:#CCC;box-shadow: inset 3px 3px 3px #C0C0C0;-webkit-box-shadow: inset 3px 3px 3px #C0C0C0;background-color:transparent!important;">

                        <div class="normal-note" style="min-height:135px;">
                        <strong><?php echo _FPA_INSTRUCTIONS;  ?></strong>

                        <?php
                            echo '<ol style="margin-left:-15px;">';
                            echo '<li><span class="normal">'. _FPA_INS_1 .'</span></li>';
                            echo '<li><span class="normal">'. _FPA_INS_2 .'</span></li>';
                            echo '<li><span class="normal">'. _FPA_INS_3 .'</span></li>';
                            echo '<li><span class="normal">'. _FPA_INS_4 .'</span></li><br />';
                            echo '<li><span class="normal">'. _FPA_INS_5 .'</span></li><br />';
                            echo '<li><span class="normal">'. _FPA_INS_6 .'</span></li>';
                            echo '</ol>';
                        ?>
                        </div>

                    <br />

                        <div class="normal-note" style="min-height:145px;padding-left:10px;">
                        <strong>Optional Information</strong><br /><br />

                            <form method="post" name="postDetails" id="postDetails">

                                <div style="text-align:right;padding:2px;"><div class="normal" style="text-align:left;width:120px;float:left;"><?php echo _FPA_PROB_DSC; ?>:</div> <input class="normal-note" style="background-color: #FFFFCC;width:175px;font-size:9px;" type="text" name="probDSC" /></div>
                                <div style="text-align:right;padding:2px;"><div class="normal" style="text-align:left;width:120px;float:left;"><?php echo _FPA_PROB_MSG; ?>:</div> <input class="normal-note" style="background-color: #FFFFCC;width:175px;font-size:9px;" type="text" name="probMSG1" /></div>

                                <?php
                                    if ( $phpenv['phpLASTERR'] ) {
                                        echo '<div style="text-align:right;padding:2px;"><div class="warn-text" style="text-align:left;width:120px;float:left;">'. _FPA_LAST .' '. _FPA_ER .':</div> <input class="normal-note" style="color: #800000;background-color: #FFFFCC;width:175px;font-size:9px;" type="text" value="'. $phpenv['phpLASTERR'] .'" name="probMSG2" /><br /><span class="normal" style="font-size:8px;">auto-completed from your php error log&nbsp;&nbsp;</span></div>';
                                    } else {
                                        echo '<div style="text-align:right;padding:2px;"><div class="normal" style="text-align:left;width:120px;float:left;">'. _FPA_PROB_MSG .':</div> <input class="normal-note" style="background-color: #FFFFCC;width:175px;font-size:9px;" type="text" name="probMSG2" /></div>';
                                    }
                                ?>

                                <div style="text-align:right;padding:2px;"><div class="normal" style="text-align:left;width:120px;float:left;"><?php echo _FPA_PROB_ACT; ?>:</div> <textarea class="normal-note" style="background-color: #FFFFCC;width:175px;font-size:9px;" type="text" name="probACT"></textarea></div>

                                <?php  echo _FPA_POST_NOTE; ?>

                        </div>

                    </div>


                    <div class="half-section-information-right" style="width:340px;padding-top:10px;padding-bottom:10px;border-color:#CCC;box-shadow: inset 3px 3px 3px #C0C0C0;-webkit-box-shadow: inset 3px 3px 3px #C0C0C0;background-color:transparent!important;">

                        <div class="normal-note" style="min-height:135px;">
                        <!-- <strong>Run-Time Options</strong><br /> -->

                        <!-- intended Post location -->
                        <div style="color:#4D8000;">
                        <span style="color:#4D8000;font-weight:bold;padding-right:20px;"><strong>Run-Time Options</strong></span>
                            <input style="font-size:9px;" type="radio" name="postFormat" value="3" <?php echo @$selectshowProtected_1; ?> /><span style="color:#4D8000;padding-right:15px;">Forum</span>
                                <?php
                                    //if ( defined( '_FPA_BRA' ) ) {
                                        echo '<input style="font-size:9px;" type="radio" name="postFormat" value="2" '. @$selectshowProtected_2 .' /><span style="color:#4D8000;padding-right:15px;">JoomlaCode</span>';
                                        echo '<input style="font-size:9px;" type="radio" name="postFormat" value="1" '. @$selectshowProtected_3 .' /><span style="color:#4D8000;padding-right:15px;">GitHUB</span>';
                                    //}
                                ?>
                        </div>
                        <br />



                            <div style="float:left; width:170px;">

                            <?php
                                if ( @$_POST['showElevated'] ) {
                                    $selectshowElevated = 'CHECKED';
                                } else {
                                    $selectshowElevated = '';
                                }

                                if ( @$_POST['showTables'] ) {
                                    $selectshowTables = 'CHECKED';
                                } else {
                                    $selectshowTables = '';
                                }

                                if ( @$_POST['showComponents'] ) {
                                    $selectshowComponents = 'CHECKED';
                                } else {
                                    $selectshowComponents = '';
                                }

                                if ( @$_POST['showModules'] ) {
                                    $selectshowModules = 'CHECKED';
                                } else {
                                    $selectshowModules = '';
                                }

                                if ( @$_POST['showPlugins'] ) {
                                    $selectshowPlugins = 'CHECKED';
                                } else {
                                    $selectshowPlugins = '';
                                }
                                ?>

                                <?php
                                if ( $instance['instanceFOUND'] != _FPA_Y ) {
                                    $dis = 'DISABLED';

                                } else {
                                    $dis = '';
                                }
                                ?>
                                <strong>Optional Settings <?php echo $dis; ?>:</strong><br />
                                <input <?php echo $dis; ?> style="font-size:9px;" type="checkbox" name="showElevated" value="1" <?php echo $selectshowElevated ?> /><span class="normal">Show elevated folder permissions</span><br />
                                <input <?php echo $dis; ?> style="font-size:9px;" type="checkbox" name="showTables" value="1" <?php echo $selectshowTables ?> /><span class="normal">Show database table statistics</span><br />
                                <input <?php echo $dis; ?> style="font-size:9px;" type="checkbox" name="showComponents" value="1" <?php echo $selectshowComponents ?> /><span class="normal">Show Components</span><br />
                                <input <?php echo $dis; ?> style="font-size:9px;" type="checkbox" name="showModules" value="1" <?php echo $selectshowModules ?> /><span class="normal">Show Modules</span><br />
                                <input <?php echo $dis; ?> style="font-size:9px;" type="checkbox" name="showPlugins" value="1" <?php echo $selectshowPlugins ?> /><span class="normal">Show Plugins</span><br />
                            </div>

                            <div style="float:right; width:150px;">
                            <?php
                                if ( $showProtected >= 3 OR  @$_POST['showProtected'] >= 3 ) {
                                    $selectshowProtected_1 = '';
                                    $selectshowProtected_2 = '';
                                    $selectshowProtected_3 = 'CHECKED';
                                } elseif ( $showProtected == 2 OR @$_POST['showProtected'] == 2 ) {
                                    $selectshowProtected_1 = '';
                                    $selectshowProtected_2 = 'CHECKED';
                                    $selectshowProtected_3 = '';
                                } elseif ( $showProtected == 1 OR @$_POST['showProtected'] == 1 ) {
                                    $selectshowProtected_1 = 'CHECKED';
                                    $selectshowProtected_2 = '';
                                    $selectshowProtected_3 = '';
                                } elseif ( $showProtected == 2 ) {
                                    $selectshowProtected_1 = '';
                                    $selectshowProtected_2 = 'CHECKED';
                                    $selectshowProtected_3 = '';
                                } else {
                                    $selectshowProtected_1 = '';
                                    $selectshowProtected_2 = 'CHECKED';
                                    $selectshowProtected_3 = '';
                                }
                            ?>

                                <strong>Information Privacy :</strong><br />
                                <input style="font-size:9px;" type="radio" name="showProtected" value="1" <?php echo $selectshowProtected_1; ?> /><span class="ok">None</span><br /><span style="line-height:8px;padding:0px;margin:0px;margin-left:15px;font-size:8px;">No elements are masked</span><br />
                                <input style="font-size:9px;" type="radio" name="showProtected" value="2" <?php echo $selectshowProtected_2; ?> /><span class="warn-text">Partial (default)</span><br /><span style="line-height:8px;padding:0px;margin:0px;margin-left:15px;font-size:8px;">Some elements are masked</span><br />
                                <input style="font-size:9px;" type="radio" name="showProtected" value="3" <?php echo $selectshowProtected_3; ?> /><span class="alert-text">Strict</span><br /><span style="line-height:8px;padding:0px;margin:0px;margin-left:15px;font-size:8px;">All indentifiable elements are masked</span>
                            </div>

                        <div style="clear:both;"></div>
                        </div>

                    <br />

                        <div class="normal-note" style="min-height:145px;">
                        <div style="clear:both;"><br /></div>

                            <!-- Generate the diagnostic post output -->
                            <div style = "margin: 0px auto; width:90%;text-align:center;margin-top:10px;">
                                <input type="hidden" name="doIT" value="1" />

                                <input type="submit" class="ok-hilite" style="box-shadow: 2px 2px 2px #C0C0C0;-moz-box-shadow: 2px 2px 2px #C0C0C0;-webkit-box-shadow: 2px 2px 2px #C0C0C0;text-transform:uppercase;cursor:pointer;cursor:hand;" name="submit" value="Click Here To Generate Post" />

                                    <div class="normal" style="text-shadow:none!important;margin-left:10px;float:left;width:95%;text-align:left;">
                                        <br />
                                        <span class="ok"><?php echo _FPA_INS_5; ?></span>
                                    </div>


                                <input type="reset" class="warn" style="font-size:8px;cursor:pointer;cursor:hand;" name="reset" value="reset" />


                            <div style="clear:both;"><br /></div>
                            </div>



                            <div style="clear:both;"></div>
                            <?php
                                if ( @$_POST['increasePOPS'] ) {
                                    $selectPOPS = 'CHECKED';
                                } else {
                                    $selectPOPS = '';
                                }
                            ?>

                            <div class="normal" style="margin-left:15px;border-top:1px dotted #CCC;margin-top:30px;margin-right:15px;">
                                <input style="font-size:9px;" type="checkbox" name="increasePOPS" value="1" <?php echo $selectPOPS; ?> />Seeing PHP "<span class="warn-text">Out of Memory</span>" or "<span class="warn-text">Execution Time</span>" Errors?<br />
                                <span style="margin-left:15px;font-size:8px;">Temporarily increase PHP Memory and Execution Time.</span>
                            </div>

                        </div>

                    </div>

                </div>

        <div style="clear:both;"></div>

        <?php
            if ( @$_POST['doIT'] == '1' ) {

                echo '<div class="normal-note" style="width:725px;text-align:center;margin: 0px auto;padding:2px;padding-top:5px;">';

                echo '<span class="ok" style="text-transform:uppercase;">'. _RES .' Post Detail</span><br />';


                /** LOAD UP THE SELECTED CONFIGURATION AND DIAGNOSTIC INFORMATION FOR THE POST ************
                 ** this section loads up a text-box with BBCode for the forum, it will quote each section
                 ** to make viewing easier and once used to the format, hopefully making it simpler to
                 ** pinpoint related information quickly
                 **
                 ** the user then copies and pastes this outputin to forum post
                 **
                 ** many "if/then/else" statements have been placed in single lines for ease of management,
                 ** this looks ugly and goes against coding practices but *shrug*, it's messy otherwise
                 **
                 ** NOTE IF MODIFYING: carriage returns and line breaks MUST be double-quoted, not single-
                 ** quote, hence some of the weird quoting and formating
                 *****************************************************************************************/
                echo '<textarea class="protected" style="width:700px;height:35px;font-size:9px;margin-top:5px;" type="text" rows="10" cols="100" name="postOUTPUT" id="postOUTPUT">';

                    // post the problem description, if any
                    if ( $_POST['probDSC'] ) { echo '[quote="'. _FPA_PROB_DSC .' :: '. _RES .' (v'. _RES_VERSION .') : '. @date( 'jS F Y' ) .'"][size=85]'. $_POST['probDSC'] .'[/quote][/size]'; }

                    // post the first error message, if any
                    if ( $_POST['probMSG1'] ) { echo '[quote="'. _FPA_PROB_MSG .' :: '. _RES .' (v'. _RES_VERSION .') : '. @date( 'jS F Y' ) .'"][size=85]'. $_POST['probMSG1'] .'[/quote][/size]'; }

                    // post the second error message, if any (remember if there is a PHP Error in the log, it will be auto-added here instead of the user adding a message
                    // show the different MSG2 header and content, if there is an existing PHP Error to display
                    if ( $phpenv['phpLASTERR'] AND $_POST['probMSG2'] ) { echo '[quote="'. _FPA_LAST .' PHP '. _FPA_ER .' :: '. _RES .' (v'. _RES_VERSION .') : '. @date( 'jS F Y' ) .'"][size=85][color=#800000]'. $_POST['probMSG2'] .'[/color][/quote][/size]';
                    } elseif ( !@$phpenv['phpLASTERROR'] AND $_POST['probMSG2'] ) { echo '[quote="'. _FPA_PROB_MSG .' :: '. _RES .' (v'. _RES_VERSION .') : '. @date( 'jS F Y' ) .'"][size=85]'. $_POST['probMSG2'] .'[/quote][/size]'; }

                    // post the actions taken, if any
                    if ( $_POST['probACT'] ) { echo '[quote="'. _FPA_PROB_ACT .' '. _FPA_BY .' '. _RES .' (v'. _RES_VERSION .') '. @date( 'jS F Y' ) .'"][size=85]'. $_POST['probACT'] .'[/quote][/size]'; }

//!TODO come back and finish the post details


                    echo '[quote="'. _RES .' (v'. _RES_VERSION .') : '. @date( 'jS F Y' ) .'"]';


                    // do the basic information
                    echo '[quote="Basic Environment ::"][size=85]';

                    // Joomla! cms details
                    echo '[color=#000000][b]Joomla! Instance :: [/b][/color]';
                    if ( $instance['instanceFOUND'] == _FPA_Y ) { echo '[color=#0000F0]'. $instance['cmsPRODUCT'] .' [b]'. $instance['cmsRELEASE'] .'.'. $instance['cmsDEVLEVEL'] .'[/b]-'. $instance['cmsDEVSTATUS'] .' ('. $instance['cmsCODENAME'] .') '. $instance['cmsRELDATE'] .'[/color]';
                    } else { echo '[color=orange]'. _FPA_NF .'[/color]'; }

                    // Joomla! platform details
                    if ( @$instance['platformPRODUCT'] ) {
                    echo "\r\n";
                    echo '[color=#000000][b]Joomla! Platform :: [/b][/color] [color=#0000F0]'. @$instance['platformPRODUCT'] .' [b]'. @$instance['platformRELEASE'] .'.'. @$instance['platformDEVLEVEL'] .'[/b]-'. @$instance['platformDEVSTATUS'] .' ('. @$instance['platformCODENAME'] .') '. @$instance['platformRELDATE'] .'[/color]'; }

                    echo "\r\n";

                    echo '[color=#000000][b]Joomla! Confirgured :: [/b][/color]';
                    if ( $instance['instanceCONFIGURED'] == _FPA_Y ) {
                    echo '[color=#008000]'. _FPA_Y .'[/color] | ';

                        if ( $instance['configWRITABLE'] == _FPA_Y ) { echo '[color=#008000]Writable[/color] ('; } else { echo 'Read-Only ('; }

                        if ( substr( $instance['configMODE'],1 ,1 ) == '7' OR substr( $instance['configMODE'],2 ,1 ) >= '5' OR substr( $instance['configMODE'],3 ,1 ) >= '5' ) { echo '[color=#800000]'; } else { echo '[color=#008000]'; }
                        echo $instance['configMODE'] .'[/color]) | ';
                        echo '[b]Owner:[/b] '. $instance['configOWNER']['name'] .' (uid: '. $instance['configOWNER']['uid'] .'/gid: '. $instance['configOWNER']['gid'] .') | [b]Group:[/b] '. $instance['configGROUP']['name'] .' (gid: '. $instance['configGROUP']['gid'] .') | [b]Valid For:[/b] '. $instance['configVALIDFOR'];

                    echo "\r\n";

                    echo '[color=#000000][b]Configuration Options :: [/b][/color] [b]Offline:[/b] '. $instance['configOFFLINE'] .' | [b]SEF Enabled:[/b] '. $instance['configSEF'] .' | [b]SEF Suffix Enabled:[/b] '. $instance['configSEFSUFFIX'] .' | [b]SEF ReWrite:[/b] '. $instance['configSEFRWRITE'] .' | ';
                    echo '[b].htaccess/web.config:[/b] ';

                        if ( $instance['configSITEHTWC'] == _FPA_N AND $instance['configSEFRWRITE'] == '1' ) { echo '[color=orange]'. $instance['configSITEHTWC'] .' (ReWrite Enabled but no .htaccess?)[/color] | ';
                        } elseif ( $instance['configSITEHTWC'] == _FPA_Y ) { echo '[color=#008000]'. $instance['configSITEHTWC'] .'[/color] | ';
                        } elseif ( $instance['configSITEHTWC'] == _FPA_N ) { echo '[color=orange]'. $instance['configSITEHTWC'] .'[/color] | '; }

                    echo '[b]GZip Enabled:[/b] '. $instance['configGZIP'] .' | [b]Cache Enabled:[/b] '. $instance['configCACHING'] .' | [b]FTP Layer:[/b] '. $instance['configFTP'] .' | [b]SSL:[/b] '. $instance['configSSL'] .' | [b]Error Reporting:[/b] '. $instance['configERRORREP'] .' | [b]Site Debug:[/b] '. $instance['configSITEDEBUG'] .' | ';

                        if ( version_compare( $instance['cmsRELEASE'], '1.5', '>=' ) ) {
                            echo '[b]Language Debug:[/b] '. $instance['configLANGDEBUG'] .' | ';
                        }

                        if ( version_compare( $instance['cmsRELEASE'], '1.6', '>=' ) ) {
                            echo '[b]Default Access:[/b] '. $instance['configACCESS'] .' | [b]Unicode Slugs:[/b] '. $instance['configUNICODE'] .' | ';
                        }

                        echo '[b]Database Credentials Present:[/b] ';
                            if ( $instance['configDBCREDOK'] == _FPA_Y ) { echo '[color=#008000]'; } else { echo '[color=#800000]'; }
                            echo $instance['configDBCREDOK'] .'[/color]';

                    } else { echo '[color=orange]'. _FPA_NF .'[/color]'; }

                    echo "\r\n\r\n";

                    echo '[color=#000000][b]Host Configuration :: [/b][/color] [b]OS:[/b] '. $system['sysPLATOS'] .' |  [b]OS Version:[/b] '. $system['sysPLATREL'] .' | [b]Technology:[/b] '. $system['sysPLATTECH'] .' | [b]Web Server:[/b] '. $system['sysSERVSIG'] .' | [b]Encoding:[/b] '. $system['sysENCODING'] .' | [b]Document Root:[/b] '. $system['sysDOCROOT'] .' | [b]System TMP Writable:[/b] ';
                        if ( $system['sysTMPDIRWRITABLE'] == _FPA_Y ) { echo '[color=#008000]'; } else { echo '[color=#800000]'; }
                        echo $system['sysTMPDIRWRITABLE'] .'[/color]';

                    echo "\r\n\r\n";

                    echo '[color=#000000][b]PHP Configuration :: [/b][/color] [b]Version:[/b] ';
                        if ( version_compare( $phpenv['phpVERSION'], '5.0.0', '<' ) ) { echo '[color=#800000]'. $phpenv['phpVERSION'] .'[/color] | '; } else { echo '[b]'. $phpenv['phpVERSION'] .'[/b] | '; }

                    echo '[b]PHP API:[/b] ';
                        if ( $phpenv['phpAPI'] == 'apache2handler' ) { echo '[color=orange]'. $phpenv['phpAPI'] .'[/color] | '; } else { echo '[b]'. $phpenv['phpAPI'] .'[/b] | '; }

                    echo '[b]Session Path Writable:[/b] ';
                        if ( $phpenv['phpSESSIONPATHWRITABLE'] == _FPA_Y ) { echo '[color=#008000]'. $phpenv['phpSESSIONPATHWRITABLE'] .'[/color] | '; } elseif ( $phpenv['phpSESSIONPATHWRITABLE'] == _FPA_N ) { echo '[color=#800000]'. $phpenv['phpSESSIONPATHWRITABLE'] .'[/color] | '; } else { echo '[color=orange]'. $phpenv['phpSESSIONPATHWRITABLE'] .'[/color] | '; }

                    echo '[b]Display Errors:[/b] '. $phpenv['phpERRORDISPLAY'] .' | [b]Error Reporting:[/b] '. $phpenv['phpERRORREPORT'] .' | [b]Log Errors To:[/b] '. $phpenv['phpERRLOGFILE'] .' | [b]Last Known Error:[/b] '. @$phpenv['phpLASTERRDATE'] .' | [b]Register Globals:[/b] '. $phpenv['phpREGGLOBAL'] .' | [b]Magic Quotes:[/b] '. $phpenv['phpMAGICQUOTES'] .' | [b]Safe Mode:[/b] '. $phpenv['phpSAFEMODE'] .' | [b]Open Base:[/b] '. $phpenv['phpOPENBASE'] .' | [b]Uploads:[/b] '. $phpenv['phpUPLOADS'] .' | [b]Max. Upload Size:[/b] '. $phpenv['phpMAXUPSIZE'] .' | [b]Max. POST Size:[/b] '. $phpenv['phpMAXPOSTSIZE'] .' | [b]Max. Input Time:[/b] '. $phpenv['phpMAXINPUTTIME'] .' | [b]Max. Execution Time:[/b] '. $phpenv['phpMAXEXECTIME'] .' | [b]Memory Limit:[/b] '. $phpenv['phpMEMLIMIT'];

                    echo "\r\n\r\n";

                    echo '[color=#000000][b]MySQL Configuration :: [/b][/color] ';
                    if ( $database['dbDOCHECKS'] == _FPA_N ) {
                        echo '[color=orange]Database credentials incomplete or not available.[/color] Nothing to display.';
                    echo "\r\n";

                            if ( $instance['configDBCREDOK'] != _FPA_Y AND $instance['instanceFOUND'] == _FPA_Y ) {
                                echo '[color=#800000][b]Missing Credentials Detected: [/b][/color] ';

                                if ( @$instance['configDBTYPE'] == '' ) { echo '[color=orange]Connection Type missing[/color] | '; }
                                if ( @$instance['configDBHOST'] == '' ) { echo '[color=orange]MySQL Host missing[/color] | '; }
                                if ( @$instance['configDBPREF'] == '' ) { echo '[color=orange]Table Prefix missing[/color] | '; }
                                if ( @$instance['configDBUSER'] == '' ) { echo '[color=orange]Database Username missing[/color] | '; }
                                if ( @$instance['configDBPASS'] == '' ) { echo '[color=orange]Database Password missing[/color] |'; }

                            }


                    } elseif ( $database['dbERROR'] != _FPA_N ) { echo '[b]Connection Error:[/b] ';

                            if ( $_POST['showProtected'] == '3' ) {
                                echo '[color=orange][b]Strict[/b] Information Privacy was selected[/color], but there was an [color=#800000]error[/color].';
                            } else {
//                                echo "\r\n";
                                echo '[color=#800000]'. $database['dbERROR'] .'[/color] : [color=orange]check credentials in configuration...[/color]';
                            }

                    } else {
                        echo '[b]Version:[/b] [b]'. $database['dbHOSTSERV'] .'[/b] (Client:'. $database['dbHOSTCLIENT'] .') | ';

                            if ( $_POST['showProtected'] >= '1' ) { echo '[b]Hostname:[/b]  [color=orange]--'. _FPA_HIDDEN .'--[/color] ([color=orange]--'. _FPA_HIDDEN .'--[/color]) | ';
                            } else { echo '[b]Hostname:[/b] '. $instance['configDBHOST'] .' ('. $database['dbHOSTINFO'] .') | '; }

                            echo '[b]Collation:[/b] '. $database['dbCOLLATION'] .' ([b]Character Set:[/b] '. $database['dbCHARSET'] .') | [b]Database size:[/b] '. $database['dbSIZE'] .' | [b]Number Of Tables:[/b] '. $database['dbTABLECOUNT'];
                    }



                    echo '[/size][/quote]';






                    // do detailed information
                    echo '[quote="Detailed Environment ::"][size=85]';

                    echo '[color=#000000][b]PHP Extensions :: [/b][/color]';

                        foreach ( $phpextensions as $key => $show ) {

                            if ( $show != $phpextensions['ARRNAME'] ) {

                                // find the requirements and mark them as present or missing
                                if ( $key == 'libxml' OR $key == 'xml' OR $key == 'zip' OR $key == 'openssl' OR $key == 'zlib' OR $key == 'curl' OR $key == 'iconv' OR $key == 'mbstring' OR $key == 'mysql' OR $key == 'mysqli' OR $key == 'mcrypt' OR $key == 'suhosin' OR $key == 'cgi' OR $key == 'cgi-fcgi' ) {
                                    echo '[color=#008000][b]'. $key .'[/b][/color] ('. $show .') | ';
                                } elseif ( $key == 'apache2handler' ) {
                                    echo '[color=orange]'. $key .'[/color] ('. $show .') | ';
                                } else {
                                    echo $key .' ('. $show .') | ';
                                }

                            } // endif !arrname

                            if ( !in_array( $key, $phpreq ) ) {
                                unset ( $phpreq[$key] );
                            }

                        }

                        echo "\r\n";
                        echo '[color=#000000][b]Potential Misisng Extensions :: [/b][/color]';
                        foreach ( $phpreq as $missingkey => $missing ) {
                            echo '[color=orange]'. $missingkey .'[/color] | ';
                        }

                        echo "\r\n\r\n";
                        echo '[color=#000000][b]Switch User Environment[/b] [i](Experimental)[/i][b] :: [/b][/color] [b]PHP CGI:[/b] '. $phpenv['phpCGI'] .' | [b]Server SU:[/b] '. $phpenv['phpAPACHESUEXEC'] .' |  [b]PHP SU:[/b] '. $phpenv['phpPHPSUEXEC'] .' |   [b]Custom SU (Cloud/Grid):[/b] '. $phpenv['phpCUSTOMSU'];
                        echo "\r\n";
                        echo '[b]Potential Ownership Issues?:[/b] ';
                            if ( $phpenv['phpOWNERPROB'] == _FPA_Y ) { echo '[color=#800000]'; } elseif ( $phpenv['phpOWNERPROB'] == _FPA_N ) { echo '[color=#008000]'; } else { echo '[color=orange]'; }
                            echo $phpenv['phpOWNERPROB'] .'[/color] ';


                        // IF APACHE with PHP in Module mode
                        if ( $phpenv['phpAPI'] == 'apache2handler' ) {
                        echo "\r\n\r\n";

                            echo '[color=#000000][b]Apache Modules :: [/b][/color]';

                            foreach ( $apachemodules as $key => $show ) {

                                if ( $show != $apachemodules['ARRNAME'] ) {

                                    // find the requirements and mark them as present or missing
                                    if ( $show == 'mod_rewrite' OR $show == 'mod_cgi' OR $show == 'mod_cgid' OR $show == 'mod_expires' OR $show == 'mod_deflate' OR $show == 'mod_auth'  ) {
                                        echo '[color=#008000][b]'. $show .'[/b][/color] | ';
                                    } elseif ( $show == 'mod_php4' ) {
                                        echo '[color=#800000]'. $show .'[/color] | ';
                                    } else {
                                        echo $show .' | ';
                                    }

                                } // endif !arrname

                                if ( !in_array( $show, $apachereq ) ) {
                                    unset ( $apachereq['ARRNAME'] );
                                    unset ( $apachereq[$show] );
                                }

                            }

                            echo "\r\n";
                            echo '[color=#000000][b]Potential Missing Modules :: [/b][/color]';
                            foreach ( $apachereq as $missingkey => $missing ) {
                                echo '[color=orange]'. $missingkey .'[/color] | ';
                            }

                            echo "\r\n";

                        } // end if Apache and PHP module



                    echo '[/size][/quote]';



                        if ( $instance['instanceFOUND'] == _FPA_Y ) {
                            echo '[quote="Folder Permissions ::"][size=85]';

                                echo '[color=#000000][b]Core Folders :: [/b][/color]';

                                    foreach ( $folders as $i => $show ) {

                                        if ( $show != 'Core Folders' ) {

                                            if ( $_POST['showProtected'] == '3' ) {
                                                echo '[color=orange]--'. _FPA_HIDDEN .'--[/color] (';
                                            } else {
                                                echo $show .' (';
                                            }

                                            if ( substr( $modecheck[$show]['mode'],1 ,1 ) == '7' OR substr( $modecheck[$show]['mode'],2 ,1 ) == '7' ) {
                                                echo '[color=#800000]'. $modecheck[$show]['mode'] .'[/color]) | ';
                                            } else {
                                                echo $modecheck[$show]['mode'] .') | ';
                                            }

                                        }

                                    }


                                    if ( @$_POST['showElevated'] == '1' ) {
                                        echo "\r\n\r\n";

                                        $limitCount = '0';
                                        echo '[color=#000000][b]Folders With Elevated Permissions[/b] [i](First 10)[/i][b] :: [/b] [/color]';

                                            foreach ( $elevated as $key => $show ) {

                                                if ( $limitCount >= '11' ) {
                                                    unset ( $key );
                                                } else {

                                                    if ( $show != $elevated['ARRNAME'] ) {

                                                        if ( $_POST['showProtected'] == '3' ) {
                                                            echo '[color=orange]--'. _FPA_HIDDEN .'--[/color] (';
                                                        } else {

                                                            if ( $key == 'None' ) {
                                                                echo '[color=#008000][b]'. $key .'[/b][/color] ';
                                                            } else {
                                                                echo $key .'/ (';

                                                            }

                                                        }

                                                        if ( $key != 'None' ) {

                                                            if ( substr( $show['mode'],1 ,1 ) == '7' OR substr( $show['mode'],2 ,1 ) == '7' ) {
                                                                echo '[color=#800000]'. $show['mode'] .'[/color]) | ';
                                                            } else {
                                                                echo $show['mode'] .') | ';

                                                            }

                                                        }

                                                    }

                                                }

                                                $limitCount ++;
                                                }

                                    }

                                echo '[/size][/quote]';

                        } // end if InstanceFOUND





                    // do the Database Statistics and Table information
                    if ( $database['dbDOCHECKS'] == _FPA_Y AND $database['dbERROR'] == _FPA_N AND @$_POST['showTables'] == '1' ) {
                        echo '[quote="Database Information ::"][size=85]';

                            echo '[color=#000000][b]Database Statistics :: [/b][/color]';

                                foreach ( $database['dbHOSTSTATS'] as $show ) {
                                    $dbPieces = explode(": ", $show );
                                    echo '[b]'. $dbPieces[0] .':[/b] '. $dbPieces[1] .' | ';

                                }

                            /** REMOVED FROM POST OUTPUT ***************************************************
                             ** TABLE STATISTICS removed from post output to reduce the post content,
                             ** it occassionally pops the forum post character limits
                             *******************************************************************************
                            echo "\r\n\r\n";

                            echo '[color=#000000][b]Table Statistics :: [/b][/color]';
                            echo "\r\n";

                                foreach ( $tables as $i => $show ) {

                                    if ( $show != $tables['ARRNAME'] ) {

                                        if ( $showProtected == '3' ) {
                                            echo '[color=orange]--'. _FPA_HIDDEN .'--[/color] ';
                                        echo "\r\n";

                                        } else {
                                            echo '[color=#000080]'. $show['TABLE'] .'[/color] ';
                                            echo "\r\n";
                                        }

                                        echo '[b]Size:[/b] '. $show['SIZE'] .' | [b]Records:[/b] '. $show['RECORDS'] .' | [b]Avg. Length:[/b] '. $show['AVGLEN'] .' | [b]Fragment Size[/b] (Overhead)[b]:[/b] '. $show['FRAGSIZE'] .' | [b]Engine:[/b] '. $show['ENGINE'] .' | [b]Collation:[/b] '. $show['COLLATION'];
                                        echo "\r\n";
                                    }

                                }
                                ***************************************************************************/

                        echo '[/size][/quote]';

                    } elseif ( ( $database['dbDOCHECKS'] != _FPA_Y OR $database['dbERROR'] != _FPA_N ) AND $_POST['showTables'] == '1' ) {

                                                    // only show the tables if we can connect to the database
//                                if ( $database['dbERROR'] == _FPA_N ) {

//                                }

                    }




                    // do the Extensions information
                    if ( $instance['instanceFOUND'] == _FPA_Y AND ( @$_POST['showComponents'] == '1' OR @$_POST['showModules'] == '1' OR @$_POST['showPlugins'] == '1' ) ) {
                    echo '[quote="Extensions Discovered ::"][size=85]';

                        if ( $_POST['showProtected'] == '3' ) {
                            echo '[color=orange][b]Strict[/b] Information Privacy was selected.[/color] Nothing to display.';
                            echo '[/size][/quote]';
                        } else {


                            if ( @$_POST['showComponents'] == '1' ) {
                                echo '[color=#000000][b]Components :: Site :: [/b][/color]';

                                    foreach ( $component['SITE'] as $key => $show ) {
                                        echo $show['name'] .' ('. $show['version'] .') | ';
                                    }

                                echo "\r\n";

                                echo '[color=#000000][b]Components :: Admin :: [/b][/color]';

                                    foreach ( $component['ADMIN'] as $key => $show ) {
                                        echo $show['name'] .' ('. $show['version'] .') | ';
                                    }

                                echo "\r\n\r\n";
                            }
//                        }


                            if ( @$_POST['showModules'] == '1' ) {
                                echo '[color=#000000][b]Modules :: Site :: [/b][/color]';

                                    foreach ( $module['SITE'] as $key => $show ) {
                                        echo $show['name'] .' ('. $show['version'] .') | ';
                                    }

                                echo "\r\n";

                                echo '[color=#000000][b]Modules :: Admin :: [/b][/color]';

                                    foreach ( $module['ADMIN'] as $key => $show ) {
                                        echo $show['name'] .' ('. $show['version'] .') | ';
                                    }

                            echo "\r\n\r\n";
                            }


                            if ( @$_POST['showPlugins'] == '1' ) {
                                echo '[color=#000000][b]Plugins :: Site :: [/b][/color]';

                                    foreach ( $plugin['SITE'] as $key => $show ) {
                                        echo $show['name'] .' ('. $show['version'] .') | ';
                                    }

                            }

                            echo '[/size][/quote]';

                        } // end if showComponents, Modules, Plugins, if cmsFOUND

                    } // end showProtected != strict


                        // do the template information
                        if ( $instance['instanceFOUND'] == _FPA_Y ) {
                            echo '[quote="Templates Discovered ::"][size=85]';

                                if ( $_POST['showProtected'] == '3' ) {
                                    echo '[color=orange][b]Strict[/b] Information Privacy was selected.[/color] Nothing to display.';
                                    echo '[/size][/quote]';
                                } else {

                                    echo '[color=#000000][b]Templates :: Site :: [/b][/color]';

                                        foreach ( $template['SITE'] as $key => $show ) {
                                            echo $show['name'] .' ('. $show['version'] .') | ';
                                        }

                                    echo "\r\n";

                                    echo '[color=#000000][b]Templates :: Admin :: [/b][/color]';

                                        foreach ( $template['ADMIN'] as $key => $show ) {
                                            echo $show['name'] .' ('. $show['version'] .') | ';
                                        }

                                    echo '[/size][/quote]';

                                } // end if InstanceFOUND

                        } // end showProtected != strict

/**
                echo '[quote="'. _RES .'"][size=85]';
                    if ( $_POST['probDSC'] ) { echo '[b][u][color=black]Problem Description:[/color][/u][/b] &nbsp;'. $_POST['probDSC']; echo "\n\n"; }
                    if ( $_POST['probMSG1'] ) { echo '[b][u][color=black]Error Message:[/color][/u][/b] &nbsp;'. $_POST['probMSG1']; echo "\n"; }
                    if ( $_POST['probMSG2'] ) { echo '[b][u][color=black]Last Known PHP Error:[/color][/u][/b] &nbsp;'. $_POST['probMSG2']; echo "\n"; }
                    if ( $_POST['probACT'] ) { echo "\n"; echo '[b][u][color=black]Actions Taken:[/color][/u][/b] &nbsp;'. $_POST['probACT']; }
                echo '[/size][/quote]';
**/

                echo '[/quote]';
                echo '</textarea>';
                echo '<div style="clear:both;"><br /></div>';
                echo '<span class="ok">'. _FPA_INS_6 .'</span>';
                echo '<div style="clear:both;"><br /></div>';
                echo '</div>';
            }
        ?>

                        </form>

            </div>

        <div style="clear:both;"><br /></div>
        </div>
    </div>
<!-- POST FORM -->





<?php
    // build a full-width div to hold two 'half-width' section, side-by-side
    echo '<div class="half-section-container" style="z-index:1;">'; // start half-section container

        /** display the instance information *************************************************/
        echo '<div class="half-section-information-left">'; // start left content block

        echo '<div class="section-title" style="text-align:center;">'. $instance['ARRNAME'] .' :: Discovery</div>';
        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';
        // this is the column heading area, if any


        /** mini-content, shown in all cases *************************************************/
        echo '<div class="mini-content-container">';
        echo '<div class="mini-content-box">';
        echo '<div class="mini-content-title">CMS Found</div>';

            if ( $instance['instanceFOUND'] == _FPA_Y ) {
                echo @$instance['cmsPRODUCT'] .'<br />';
                echo '<strong>'. @$instance['cmsRELEASE'] .'.'. @$instance['cmsDEVLEVEL'] .'</strong><br />';

                if ( strtolower( @$instance['cmsDEVSTATUS'] ) == 'stable' ) {
                    $statusClass = 'ok-hilite';
                } elseif ( strtolower( substr( @$instance['cmsDEVSTATUS'],0, 4 ) ) == 'alph' OR strtolower( substr( @$instance['cmsDEVSTATUS'],0, 4 ) ) == 'beta' ) {
                    $statusClass = 'alert';
                } elseif ( strtolower( substr( @$instance['cmsDEVSTATUS'],0, 2 ) ) == 'rc' ) {
                    $statusClass = 'warn';
                } else {
                    $statusClass = 'warn';
                }

                echo '<div class="'. $statusClass .'" style="margin: 0px auto;margin-top:1px;">'. @$instance['cmsDEVSTATUS'] .'</div>';
                //echo $instance['cmsCODENAME'];

            } else {
                echo '<div class="warn" style="margin: 0px auto;">'. @$instance['instanceFOUND'] .'</div>';
            }

        echo '</div>';
        echo '</div>';




        // caters for the platform separation
        echo '<div class="mini-content-container">';
        echo '<div class="mini-content-box">';
        echo '<div class="mini-content-title">Platform</div>';

        if ( $instance['platformVFILE'] != _FPA_N ) {
            echo $instance['platformPRODUCT'] .'<br />';
            echo '<strong>'. $instance['platformRELEASE'] .'.'. @$instance['platformDEVLEVEL'] .'</strong><br />';


                if ( strtolower( $instance['platformDEVSTATUS'] ) == 'stable' ) {
                    $statusClass = 'ok-hilite';

                } elseif ( strtolower( substr( $instance['platformDEVSTATUS'],0, 4 ) ) == 'alph' OR strtolower( substr( $instance['platformDEVSTATUS'],0, 4 ) ) == 'beta' ) {
                    $statusClass = 'alert';

                } elseif ( strtolower( substr( $instance['platformDEVSTATUS'],0, 2 ) ) == 'rc' ) {
                    $statusClass = 'warn';

                }

                echo '<div class="'. $statusClass .'" style="margin: 0px auto;">'. $instance['platformDEVSTATUS'] .'</div>';

        } elseif ( $instance['platformVFILE'] == _FPA_N AND $instance['cmsVFILE'] == _FPA_N) {
            echo '<div class="warn" style="margin: 0px auto;">'. _FPA_N .'</div>';

        } else {
            echo _FPA_NA;
        }

        echo '</div>';
        echo '</div>';


        echo '<div class="mini-content-container">';
        echo '<div class="mini-content-box">';
        echo '<div class="mini-content-title">Config Exists</div>';

            if ( $instance['instanceCONFIGURED'] == _FPA_N ) {
                $configuredClass = 'warn';

            } else {
                $configuredClass = 'ok';

            }

        echo '<div class="'. $configuredClass .'" style="margin: 0px auto;">'. $instance['instanceCONFIGURED'] .'</div>';
        echo '</div>';
        echo '</div>';


        echo '<div class="mini-content-container">';
        echo '<div class="mini-content-box">';
        echo '<div class="mini-content-title">Config Version</div>';

            if ( @$instance['instanceCFGVERMATCH'] == _FPA_Y ) {
                echo $instance['configVALIDFOR'];
                echo '<div class="ok" style="width:99%;margin: 0px auto;">matches cms</div>';

            } elseif ( @$instance['instanceCFGVERMATCH'] == _FPA_N ) {
                echo $instance['configVALIDFOR'];
                echo '<div class="warn" style="width:99%;margin: 0px auto;">cms mis-match</div>';

            } elseif ( @$instance['configVALIDFOR'] == _FPA_U ) {
                echo '<div class="warn" style="width:99%;margin: 0px auto;">'. $instance['configVALIDFOR'] .'</div>';

            }

        echo '</div>';
        echo '</div>';


        /** mini-content, only shown if instance found and configured ************************/
        if ( $instance['instanceCONFIGURED'] != _FPA_N AND $instance['instanceFOUND'] != _FPA_N ) {

            // force new line of mini-content-boxes
            echo '<div style="clear:both;"></div>';

            echo '<div class="mini-content-container">';
            echo '<div class="mini-content-box">';
            echo '<div class="mini-content-title">Config Valid</div>';

                if ( @$instance['configSANE'] == _FPA_Y AND @$instance['configSIZEVALID'] != _FPA_N ) {
                    $saneClass = 'ok';
                    $configVALID = _FPA_Y;

                } else {
                    $saneClass = 'warn';
                    $configVALID = _FPA_N;

                }

            echo '<div class="'. $saneClass .'" style="width:50px;margin: 0px auto;">'. $configVALID .'</div>';
            echo '</div>';
            echo '</div>';


            echo '<div class="mini-content-container">';
            echo '<div class="mini-content-box">';
            echo '<div class="mini-content-title">Config Mode</div>';

                // looking for --7 or -7- or -77 (default folder permissions are usually 755)
                if ( substr( $instance['configMODE'],0 ,1 ) == '7' OR substr( $instance['configMODE'],1 ,1 ) == '7' OR substr( $instance['configMODE'],2 ,1 ) == '7' ) {
                    $modeClass = 'alert';

                } elseif ( $instance['configMODE'] <= '644' ) {
                    $modeClass = 'ok';

                } elseif ( substr( $instance['configMODE'],1 ,1 ) >= '5' OR substr( $instance['configMODE'],2 ,1 ) >= '5' ) {
                    $modeClass = 'warn';

                } elseif ( $instance['configMODE'] == _FPA_N ) {
                    $modeClass = 'warn-text';

                } else {
                    $modeClass = 'normal';

                }

            echo '<div class="'. $modeClass .'" style="width:50px;margin: 0px auto;">'. $instance['configMODE'] .'</div>';

                // is the file writable?
                if ( ( $instance['configWRITABLE'] == _FPA_Y ) AND ( substr( $instance['configMODE'],0 ,1 ) == '7' OR substr( $instance['configMODE'],1 ,1 ) == '7' OR substr( $instance['configMODE'],2 ,1 ) == '7' ) ) {
                    $writeClass = 'alert';
                    $canWrite = 'Writable';

                } elseif ( ( $instance['configWRITABLE'] == _FPA_Y ) AND ( substr( $instance['configMODE'],0 ,1 ) <= '6' ) ) {
                    $writeClass = 'ok';
                    $canWrite = 'Writable';

                } elseif ( ( $instance['configWRITABLE'] != _FPA_Y ) ) {
                    $writeClass = 'warn';
                    $canWrite = 'Read Only';

                }

            echo '<div class="'. $writeClass .'" style="width:50px;margin: 0px auto;margin-top:1px;">'. $canWrite .'</div>';
            echo '</div>';
            echo '</div>';


            echo '<div class="mini-content-container">';
            echo '<div class="mini-content-box">';
            echo '<div class="mini-content-title">Config Owner</div>';

                if ( $showProtected <= 2 ) {
                    echo $instance['configOWNER']['name'];

                } else {
                    echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>&nbsp;';

                }

            echo '</div>';
            echo '</div>';


            echo '<div class="mini-content-container">';
            echo '<div class="mini-content-box">';
            echo '<div class="mini-content-title">Config Group</div>';

                if ( $showProtected <= 2 ) {
                    echo $instance['configGROUP']['name'];

                } else {
                    echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>&nbsp;';

                }

            echo '</div>';
            echo '</div>';

        } // end if no instance or configuration found dont display

        echo '</div>';



        // only do mode/permissions checks if an instance was found in the intial checks
        if ( $instance['instanceFOUND'] != _FPA_Y ) {
            // this is the content area
            echo '<div class="row-content-container nothing-to-display" style="">';
            echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">';
            echo 'Instance not found, no '. $instance['ARRNAME'] .' tests performed';

                if ( $instance['instanceCONFIGURED'] == _FPA_Y ) {
                    echo '<br />but there is a configuration.php file.';

                }

            echo '</div>';
            echo '</div>';

        }

        echo '</div>';
        // end content left block



        /** display the system information *************************************************/
        echo '<div class="half-section-information-right">'; // start right content block

        echo '<div class="section-title" style="text-align:center;">'. $instance['ARRNAME'] .' :: Configuration</div>';
        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';

            // only do mode/permissions checks if an instance was found in the intial checks
            if ( $instance['instanceCONFIGURED'] == _FPA_Y AND $instance['configVALIDFOR'] != _FPA_U ) {

                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title">Site Offline</div>';
                echo $instance['configOFFLINE'];
                echo '</div>';
                echo '</div>';


                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title" style="margin-bottom:0px!important;">SEF URL\'s</div>';
                echo '<div class="mini-content-box-small">';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Enabled:<div style="float:right;font-size:9px;">'. $instance['configSEF'] .'</div></div>';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Suffix:<div style="float:right;font-size:9px;">'. $instance['configSEFSUFFIX'] .'</div></div>';

                    if ( $system['sysSHORTWEB'] != 'MIC' AND $instance['configSEFRWRITE'] == '1' AND $instance['configSITEHTWC'] != _FPA_Y ) {
                        $sefColor = 'ff0000';

                    } else {
                        $sefColor = '404040';

                    }

                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;color:#'. $sefColor .';">ReWrite:<div style="float:right;color:#'. $sefColor .';font-size:9px;">'. $instance['configSEFRWRITE'] .'</div></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';


                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title" style="margin-bottom:0px!important;">Compression</div>';
                echo '<div class="mini-content-box-small">';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">GZip:<div style="float:right;font-size:9px;">'. $instance['configGZIP'] .'</div></div>';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Cache:<div style="float:right;font-size:9px;">'. $instance['configCACHING'] .'</div></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';


                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title" style="margin-bottom:0px!important;">Debugging</div>';
                echo '<div class="mini-content-box-small">';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Error Rep:<div style="float:right;font-size:9px;">'. $instance['configERRORREP'] .'</div></div>';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Site Debug:<div style="float:right;font-size:9px;">'. $instance['configSITEDEBUG'] .'</div></div>';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Lang Debug:<div style="float:right;font-size:9px;">'. $instance['configLANGDEBUG'] .'</div></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';


                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title" style="margin-bottom:0px!important;">dataBase</div>';
                echo '<div class="mini-content-box-small">';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Type:<div style="float:right;font-size:9px;">'. $instance['configDBTYPE'] .'</div></div>';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Version:<div style="float:right;font-size:9px;">'. @$database['dbHOSTSERV'] .'</div></div>';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">CharSet:<div style="float:right;font-size:9px;">'. @$database['dbCHARSET'] .'</div></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';


                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title">DB Credentials</div>';

                    if ( $instance['configDBCREDOK'] == _FPA_Y ) {
                        echo '<div class="ok" style="width:99%;margin: 0px auto;font-size:9px;">appears<br />complete</div>';
                    } else {
                        echo '<div class="warn" style="width:99%;margin: 0px auto;font-size:9px;">appears<br />in-complete</div>';

                    }

                echo '</div>';
                echo '</div>';


                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title" style="margin-bottom:0px!important;">Security</div>';
                echo '<div class="mini-content-box-small">';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">SSL Enabled:<div style="float:right;font-size:9px;">'. $instance['configSSL'] .'</div></div>';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Def\' Access:<div style="float:right;font-size:9px;">'. $instance['configACCESS'] .'</div></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';


                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title" style="margin-bottom:0px!important;">Features</div>';
                echo '<div class="mini-content-box-small">';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">FTP Enabled:<div style="float:right;font-size:9px;">'. $instance['configFTP'] .'</div></div>';
                echo '<div style="font-size:9px;width:99%;border-bottom: 1px dotted #c0c0c0;">Unicode Slug:<div style="float:right;font-size:9px;">'. $instance['configUNICODE'] .'</div></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';


            } else { // an instance wasn't found in the initial checks, so no folders to check


                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title">Config Version</div>';
                echo '<div class="warn" style="width:50px;margin: 0px auto;">'. _FPA_U .'</div>';
                echo '</div>';
                echo '</div>';


                echo '<div class="mini-content-container">';
                echo '<div class="mini-content-box">';
                echo '<div class="mini-content-title">Config Valid</div>';

                    if ( @$instance['configSIZEVALID'] == _FPA_N ) {
                        echo '<div class="warn" style="width:99%;margin: 0px auto;">could be empty</div>';

                    }

                echo '</div>';
                echo '</div>';


                echo '<div class="row-content-container nothing-to-display" style="">';
                echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">';
                echo 'configuration not found or appears not to be valid<br />no '. $instance['ARRNAME'] .' checks performed';
                echo '</div>';
                echo '</div>';
            }

        echo '</div>';
        echo '</div>';
        // end content right block

    echo '</div>'; // end half-section container
    showDev( $instance );
?>



<?php
    // build a full-width div to hold two 'half-width' section, side-by-side
    echo '<div class="half-section-container" style="">'; // start half-section container

        /** display the instance information *************************************************/
        echo '<div class="half-section-information-left">'; // start left content block

        echo '<div class="section-title" style="text-align:center;">'. $database['ARRNAME'] .' :: Discovery</div>';
        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';

        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px; width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-right:0px;padding-bottom:3px;text-transform:uppercase;">'. @$instance['configDBTYPE'] .' '. _FPA_VER .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-top-right-radius: 5px;-moz-border-top-right-radius: 5px;-webkit-border-top-right-radius: 5px;  border-top-left-radius: 5px;-moz-border-top-left-radius: 5px;-webkit-border-top-left-radius: 5px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-top: 1px solid #42AEC2;1px solid #ccebeb;">';

            if ( @$database['dbHOSTSERV'] ) {
                echo '<span class="normal">Server: '. $database['dbHOSTSERV'] .'&nbsp;</span>';

            } else {
                echo '<span class="normal">Server:</span> <span class="warn-text">'. _FPA_U .'&nbsp;</span>';

            }

            if ( @$database['dbHOSTCLIENT'] ) {
                echo '<span class="normal">&nbsp;&nbsp;Client: '. $database['dbHOSTCLIENT'] .'&nbsp;</span>';

            } else {
                echo '<span class="normal">&nbsp;&nbsp;Client:</span> <span class="warn-text">'. _FPA_U .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. @$instance['configDBTYPE'] .' Hostname:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';

            if ( $showProtected == 1 ) {

                if ( $instance['configDBHOST'] ) {
                    echo '<div class="normal">&nbsp;'. $instance['configDBHOST'] .'&nbsp;</div>';

                } else {
                    echo '<span class="alert-text">&nbsp;'. _FPA_NC .'&nbsp;</span>';

                }

            } else {
                echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>&nbsp;';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Connection Type:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';

        echo '<span class="normal">';

            if ( $database['dbLOCAL'] == _FPA_Y ) {
                echo '('. _FPA_LOCAL .') '. $database['dbHOSTINFO'] .'&nbsp';

            } elseif ( $database['dbLOCAL'] == _FPA_N AND @$database['dbHOSTINFO'] ) {

                if ( $showProtected <= 2 ) {
                    echo '('. _FPA_REMOTE .') '. $database['dbHOSTINFO'] .'&nbsp';

                } else {
                    echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>&nbsp;';

                }

            } elseif ( $database['dbLOCAL'] == _FPA_N AND !@$database['dbHOSTINFO'] ) {
                echo '('. _FPA_REMOTE .') <span class="warn-text"> '. _FPA_U .'</span>&nbsp';

            } else {
                echo '<span class="warn-text">'. _FPA_U .'</span>';

            }

            echo '</span>';

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">PHP Support:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';

            if ( @$instance['configDBTYPE'] == 'mysqli' AND $phpenv['phpSUPPORTSMYSQLI'] == _FPA_N ) {
                echo '<span class="alert-text">'. $instance['configDBTYPE'] .' '. _FPA_IS .' '. _FPA_NSUP .' '. _FPA_BY .' PHP '. $phpenv['phpVERSION'] .'&nbsp;</span>';

            } elseif ( ( @$instance['configDBTYPE'] == 'mysqli' AND $phpenv['phpSUPPORTSMYSQLI'] == _FPA_Y ) OR @$instance['configDBTYPE'] == 'mysql' ) {
                echo '<span class="ok">'. $instance['configDBTYPE'] .' '. _FPA_IS .' '. _FPA_YSUP .' '. _FPA_BY .' PHP '. $phpenv['phpVERSION'] .'&nbsp;</span>';

            } else {
                echo '<span class="warn-text">PHP '. $phpenv['phpVERSION'] .' '. _FPA_SUP .' '. _FPA_IS .' '. _FPA_U .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Connect To '. @$instance['configDBTYPE'] .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';

            if ( $database['dbDOCHECKS'] == _FPA_N ) {
                echo '<span class="warn-text">&nbsp;'. _FPA_NOA .', '. _FPA_NC .'&nbsp;</span>';

            } elseif ( @$database['dbERROR'] == _FPA_N ) {
                echo '<span class="ok">&nbsp;'. _FPA_Y .', '. _FPA_YCON .'&nbsp;</span>';

            } elseif ( @$database['dbERROR'] != _FPA_N ) {
                echo '<span class="alert-text">&nbsp;'. _FPA_N .', '. _FPA_ER .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';



            if ( @$database['dbERROR'] AND @$database['dbERROR'] != _FPA_N ) {

                echo '<div class="mini-content-box-small" style="">';
                echo '<div class="alert-text" style="line-height:10px;text-shadow: #fff 1px 1px 1px;border-bottom: 1px solid #ccebeb;font-size:8px;width:99%;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Connection Error:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #ccebeb;">';

                echo '<div class="alert" style="margin:5px;font-weight:normal;font-size:9px;padding:2px;">'. $database['dbERROR'] .'</div>';

                echo '</div></div>';
                echo '</div>';
                echo '<br style="clear:both;" />';

            }


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. @$instance['configDBTYPE'] .' Character Set:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #ccebeb;">';

            if ( @$database['dbCHARSET'] ) {
                echo '<span class="normal">&nbsp;'. $database['dbCHARSET'] .'&nbsp;</span>';

            } else {
                echo '<span class="warn-text">&nbsp;'. _FPA_U .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. _FPA_DEF .' Character Set:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #ccebeb;">';

            if ( @$database['dbHOSTDEFCHSET'] ) {
                echo '<span class="normal">&nbsp;'. $database['dbHOSTDEFCHSET'] .'&nbsp;</span>';

            } else {
                echo '<span class="warn-text">&nbsp;'. _FPA_U .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Database Collation:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #ccebeb;">';

            if ( @$database['dbCOLLATION'] ) {
                echo '<div class="normal">&nbsp;'. $database['dbCOLLATION'] .'&nbsp;</div>';

            } elseif ( @$database['dbERROR'] != _FPA_N ) {
                echo '<span class="warn-text">&nbsp;'. _FPA_U .'&nbsp;</span>';

            } else {
                echo '<span class="warn-text">&nbsp;'. _FPA_NC .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;font-weight:bold;padding:1px;padding-right:0px;padding-top:0px;padding-bottom:3px;text-transform:uppercase;">Database size:<div style="line-height:9px;text-transform:none!important;float:right;font-size:11px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-bottom-right-radius: 5px;-moz-border-bottom-right-radius: 5px;-webkit-border-bottom-right-radius: 5px;  border-bottom-left-radius: 5px;-moz-border-bottom-left-radius: 5px;-webkit-border-bottom-left-radius: 5px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #42AEC2;">';

            if ( @$database['dbSIZE'] ) {
                echo '<span class="normal">&nbsp;'. $database['dbSIZE'] .'&nbsp;</span>';

            } else {
                echo '<span class="warn-text">&nbsp;'. _FPA_U .'&nbsp;</span>';

            }

        echo '</div></div>';


        if ( $instance['configDBCREDOK'] != _FPA_Y AND $instance['instanceFOUND'] == _FPA_Y ) {
        echo '<br /><br />';
        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;"><span class="alert-text" style="font-size:8px;">Missing Credentials Detected:</span> ';

            if ( @$instance['configDBTYPE'] == '' ) { echo '<span class="warn-text" style="font-size:9px;font-weight:normal;text-transform:none;">&nbsp;Connection <b>Type</b> missing</span>'; }
            if ( @$instance['configDBHOST'] == '' ) { echo '<span class="warn-text" style="font-size:9px;font-weight:normal;text-transform:none;">&nbsp;MySQL <b>Host</b> missing</span>'; }
            if ( @$instance['configDBPREF'] == '' ) { echo '<span class="warn-text" style="font-size:9px;font-weight:normal;text-transform:none;">&nbsp;Table <b>Prefix</b> missing</span>'; }
            if ( @$instance['configDBUSER'] == '' ) { echo '<span class="warn-text" style="font-size:9px;font-weight:normal;text-transform:none;">&nbsp;Database <b>Username</b> missing</span>'; }
            if ( @$instance['configDBPASS'] == '' ) { echo '<span class="warn-text" style="font-size:9px;font-weight:normal;text-transform:none;">&nbsp;Database <b>Password</b> missing</span>'; }

            echo '</div></div>';
        }


        echo '</div>';

//        echo '<br style="clear:both;" />';

        echo '</div></div>';
        // end content left block


        /** display the system information *************************************************/
        echo '<div class="half-section-information-right">'; // start right content block

        echo '<div class="section-title" style="text-align:center;">'. $database['ARRNAME'] .' :: Performance</div>';
        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';

            // only do mode/permissions checks if an instance was found in the intial checks
            if ( $database['dbDOCHECKS'] == _FPA_Y AND @$database['dbERROR'] == _FPA_N ) {

                $pieces = explode(": ", $database['dbHOSTSTATS'][0] );

                echo '<div class="mini-content-box-small" style="">';
                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px; width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-right:0px;padding-bottom:3px;text-transform:uppercase;">'. $pieces[0] .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-top-right-radius: 5px;-moz-border-top-right-radius: 5px;-webkit-border-top-right-radius: 5px;  border-top-left-radius: 5px;-moz-border-top-left-radius: 5px;-webkit-border-top-left-radius: 5px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-top: 1px solid #42AEC2;1px solid #ccebeb;">';
                echo '<span class="normal">'. $pieces[1] .' seconds&nbsp;</span>';
                echo '</div></div>';
                echo '</div>';


                $pieces = explode(": ", $database['dbHOSTSTATS'][1] );
                echo '<div class="mini-content-box-small" style="">';
                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. $pieces[0] .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
                echo '<span class="normal">'. $pieces[1] .'&nbsp;</span>';
                echo '</div></div>';
                echo '</div>';


                $pieces = explode(": ", $database['dbHOSTSTATS'][2] );
                echo '<div class="mini-content-box-small" style="">';
                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. $pieces[0] .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
                echo '<span class="normal">'. $pieces[1] .'&nbsp;</span>';
                echo '</div></div>';
                echo '</div>';


                $pieces = explode(": ", $database['dbHOSTSTATS'][3] );
                echo '<div class="mini-content-box-small" style="">';
                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. $pieces[0] .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
                echo '<span class="normal">'. $pieces[1] .'&nbsp;</span>';
                echo '</div></div>';
                echo '</div>';


                $pieces = explode(": ", $database['dbHOSTSTATS'][4] );
                echo '<div class="mini-content-box-small" style="">';
                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. $pieces[0] .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
                echo '<span class="normal">'. $pieces[1] .'&nbsp;</span>';
                echo '</div></div>';
                echo '</div>';


                $pieces = explode(": ", $database['dbHOSTSTATS'][5] );
                echo '<div class="mini-content-box-small" style="">';
                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. $pieces[0] .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
                echo '<span class="normal">'. $pieces[1] .'&nbsp;</span>';
                echo '</div></div>';
                echo '</div>';


                $pieces = explode(": ", $database['dbHOSTSTATS'][6] );
                echo '<div class="mini-content-box-small" style="">';
                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. $pieces[0] .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
                echo '<span class="normal">'. $pieces[1] .'&nbsp;</span>';
                echo '</div></div>';
                echo '</div>';


                $pieces = explode(": ", $database['dbHOSTSTATS'][7] );
                echo '<div class="mini-content-box-small" style="">';
                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">'. $pieces[0] .':<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
                echo '<span class="normal">'. $pieces[1] .'&nbsp;</span>';
                echo '</div></div>';
                echo '</div>';



                echo '<div class="mini-content-box-small" style="">';
                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;font-weight:bold;padding:1px;padding-right:0px;padding-top:0px;padding-bottom:3px;text-transform:uppercase;">Number of Tables:<div style="line-height:9px;text-transform:none!important;float:right;font-size:11px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-bottom-right-radius: 5px;-moz-border-bottom-right-radius: 5px;-webkit-border-bottom-right-radius: 5px;  border-bottom-left-radius: 5px;-moz-border-bottom-left-radius: 5px;-webkit-border-bottom-left-radius: 5px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #42AEC2;">';

                    if ( $database['dbTABLECOUNT'] ) {
                        echo '<span class="normal">&nbsp;'. $database['dbTABLECOUNT'] .' tables&nbsp;</span>';

                    } else {
                        echo '<span class="warn-text">&nbsp;'. _FPA_U .'&nbsp;</span>';

                    }

                echo '</div></div>';
                echo '</div>';


            } else { // an instance wasn't found in the initial checks, so no folders to check

                echo '<div class="row-content-container nothing-to-display" style="">';
                echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Unable to contact database<br />no '. $database['ARRNAME'] .' performance to display</div>';
                echo '</div>';

            }


        echo '</div>';
        echo '</div>';
        // end content right block

    echo '<div style="clear:both;"></div>';

    showDev( $database );

    echo '</div>'; // end half-section container
    echo '<div style="clear:both;"></div>';
?>



<?php
    if ( $showTables == '1' ) {

        /** display the table statistics and details for the selected database *******************/
        echo '<div class="section-information">';
        echo '<div class="section-title" style="text-align:center;">'. $tables['ARRNAME'] .'</div>';

        echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';

        echo '<div class="column-title" style="width:20%;float:left;text-align:center;">'. _FPA_TNAM .'</div>';
        echo '<div class="column-title" style="width:7%;float:left;left;text-align:center;">'. _FPA_TSIZ .'</div>';
        echo '<div class="column-title" style="width:6%;float:left;text-align:center;">'. _FPA_TREC .'</div>';
        echo '<div class="column-title" style="width:8%;float:left;text-align:center;">'. _FPA_TAVL .'</div>';
        echo '<div class="column-title" style="width:10%;float:left;text-align:center;">'. _FPA_TFRA .'</div>';
        echo '<div class="column-title" style="width:6%;float:left;text-align:center;">'. _FPA_TENG .'</div>';
        echo '<div class="column-title" style="width:10%;float:left;text-align:center;">'. _FPA_TCOL .'</div>';
        echo '<div class="column-title" style="width:9%;float:left;text-align:center;">'. _FPA_TCRE .'</div>';
        echo '<div class="column-title" style="width:9%;float:left;text-align:center;">'. _FPA_TUPD .'</div>';
        echo '<div class="column-title" style="width:9%;float:left;text-align:center;">'. _FPA_TCKD .'</div>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';

            if ( $instance['instanceFOUND'] == _FPA_Y AND @$database['dbERROR'] == _FPA_N ) {

                foreach ( $tables as $i => $show ) {

                    if ( $show != $tables['ARRNAME'] ) {
                        // produce the output
                        echo '<div style="font-size:9px;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;">';

                            if ( $showProtected <= 2 ) {
                                echo '<div style="font-size:9px;text-align:left;float:left;width:20%;">&nbsp;'. $show['TABLE'] .'</div>';

                            } else {

                                echo '<div style="font-size:9px;text-align:left;float:left;width:20%;">&nbsp;<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span></div>';

                            }

                        echo '<div style="font-size:9px;text-align:center;float:left;width:8%;">'. $show['SIZE'] .'</div>';
                        echo '<div style="font-size:9px;text-align:center;float:left;width:7%;">'. $show['RECORDS'] .'</div>';
                        echo '<div style="font-size:9px;text-align:center;float:left;width:8%;">'. $show['AVGLEN'] .'</div>';
                        echo '<div style="font-size:9px;text-align:center;float:left;width:10%;">'. $show['FRAGSIZE'] .'</div>';
                        echo '<div style="font-size:9px;text-align:center;float:left;width:7%;">'. $show['ENGINE'] .'</div>';


                        if ( $show['COLLATION'] != $database['dbCOLLATION'] ) {
                            echo '<div style="font-size:9px;text-align:center;float:left;width:12%;"><span class="warn-text" style="font-size:9px;">*</span>'. $show['COLLATION'] .'</div>';

                        } else {
                            echo '<div style="font-size:9px;text-align:center;float:left;width:12%;">'. $show['COLLATION'] .'</div>';

                        }

                        $pieces = explode( " ", $show['CREATED'] );
                        echo '<div style="font-size:9px;text-align:center;float:left;width:9%;">'. $pieces['0'] .'</div>';

                        $pieces = explode( " ", $show['UPDATED'] );
                        echo '<div style="font-size:9px;text-align:center;float:left;width:9%;">'. $pieces['0'] .'</div>';

                        $pieces = explode( " ", $show['CHECKED'] );
                        echo '<div style="font-size:9px;text-align:center;float:left;width:9%;">'. $pieces['0'] .'</div>';

                        echo '<br /></div>';

                    } // endif , dont show array name

                } // end foreach


            } else { // an instance wasn't found in the initial checks, so no tables to check
                echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
                echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Unable to contact database, no '. $tables['ARRNAME'] .' to display</div>';
                echo '</div>';

            }


        echo '</div>'; // end container
        showDev( $tables );
        unset ( $key, $show );

    } // end showTables
?>



<?php
    // build a full-width div to hold two 'half-width' section, side-by-side
    echo '<div class="half-section-container" style="">'; // start half-section container

        /** display the instance information *************************************************/
        echo '<div class="half-section-information-left">'; // start left content block

        echo '<div class="section-title" style="text-align:center;">'. $phpenv['ARRNAME'] .' :: Discovery</div>';
        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';

        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px; width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-right:0px;padding-bottom:3px;text-transform:uppercase;">PHP Version:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-top-right-radius: 5px;-moz-border-top-right-radius: 5px;-webkit-border-top-right-radius: 5px;  border-top-left-radius: 5px;-moz-border-top-left-radius: 5px;-webkit-border-top-left-radius: 5px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-top: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpVERSION'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">PHP API:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';

            if ( $phpenv['phpAPI'] == 'apache2handler' ) {
                $status = 'warn-text';

            } else {
                $status = 'ok';

            }

        echo '<span class="'. $status.'">'. $phpenv['phpAPI'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Display Errors:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpERRORDISPLAY'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Error Report Level:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpERRORREPORT'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">MySQLi Support:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpSUPPORTSMYSQLI'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Magic Quotes:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpMAGICQUOTES'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Safe Mode:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpSAFEMODE'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Memory Limit:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">';

            if ( @$_POST['increasePOPS'] == 1 ) { // the user set the increasePOPS setting for memory or time out errors
                echo '<i class="warn-text">(increased by user, was '. $fpa['ORIGphpMEMLIMIT'] .')</i>&nbsp;';

            }

        echo $phpenv['phpMEMLIMIT'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Uploads Enabled:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpUPLOADS'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Max. Upload Size:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpMAXUPSIZE'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Max. Post Size:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpMAXPOSTSIZE'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Max. Input Time:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $phpenv['phpMAXINPUTTIME'] .' seconds&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Max. Execution Time:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">';

            if ( @$_POST['increasePOPS'] == 1 ) { // the user set the increasePOPS setting for memory or time out errors
                echo '<i class="warn-text">(increased by user, was '. $fpa['ORIGphpMAXEXECTIME'] .')</i>&nbsp;';

            }

        echo $phpenv['phpMAXEXECTIME'] .' seconds&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;font-weight:bold;padding:1px;padding-right:0px;padding-top:0px;padding-bottom:3px;text-transform:uppercase;">Register Globals:<div style="line-height:9px;text-transform:none!important;float:right;font-size:11px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-bottom-right-radius: 5px;-moz-border-bottom-right-radius: 5px;-webkit-border-bottom-right-radius: 5px;  border-bottom-left-radius: 5px;-moz-border-bottom-left-radius: 5px;-webkit-border-bottom-left-radius: 5px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #42AEC2;">';
        echo '<span class="normal">'. $phpenv['phpREGGLOBAL'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';

        echo "<br />";

        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Open Base Path:<div style="float:right;">';
        echo '<span class="normal" style="font-size:9px;font-weight:normal;text-transform:none;">'. $phpenv['phpOPENBASE'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Session Path:<div style="float:right;">';
        echo '<span class="normal" style="font-size:9px;font-weight:normal;text-transform:none;">'. $phpenv['phpSESSIONPATH'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Session Path Writable:<div style="float:right;">';

            if ( $phpenv['phpSESSIONPATHWRITABLE'] == _FPA_Y ) {
                echo '<span class="ok" style="font-size:9px;font-weight:normal;text-transform:none;">'. $phpenv['phpSESSIONPATHWRITABLE'] .'&nbsp;</span>';

            } elseif ( $phpenv['phpSESSIONPATHWRITABLE'] == _FPA_N ) {
                echo '<span class="alert-text" style="font-size:9px;font-weight:normal;text-transform:none;">'. $phpenv['phpSESSIONPATHWRITABLE'] .'&nbsp;</span>';

            } else {
                echo '<span class="warn-text" style="font-size:9px;font-weight:normal;text-transform:none;">'. _FPA_U .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">INI File Path:<div style="float:right;">';
        echo '<span class="normal" style="font-size:9px;font-weight:normal;text-transform:none;">'. $phpenv['phpINIFILE'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';

        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Last Known PHP Error:<div style="float:right;">';

            if ( $phpenv['phpLASTERR'] ) {
                echo '<div class="alert" style="margin:5px;font-weight:normal;font-size:9px;padding:2px;text-transform:none;word-wrap: break-word;width:325px;">'. $phpenv['phpLASTERR'] .'</div>';

            } else {
                echo '<span class="ok" style="margin:5px;text-transform:none;font-weight:normal;font-size:9px;padding:2px;">None</span>';

            }

        echo '</div></div>';
        echo '</div>';

        echo '</div></div>';



        /** display the instance information *************************************************/
        echo '<div class="half-section-information-right">'; // start left content block

        echo '<div class="section-title" style="text-align:center;">'. $system['ARRNAME'] .' :: Discovery</div>';
        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';

        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px; width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-right:0px;padding-bottom:3px;text-transform:uppercase;">Platform:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-top-right-radius: 5px;-moz-border-top-right-radius: 5px;-webkit-border-top-right-radius: 5px;  border-top-left-radius: 5px;-moz-border-top-left-radius: 5px;-webkit-border-top-left-radius: 5px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-top: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $system['sysPLATOS'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Kernel Version:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $system['sysPLATREL'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Technology:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $system['sysPLATTECH'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Host Name:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';

            if ( $showProtected == 1 ) {
                echo '<span class="normal">'. $system['sysPLATNAME'] .'&nbsp;</span>';

            } else {
                echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>&nbsp;';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Total Disk Space:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #ccebeb;">';
            if ( function_exists( 'disk_free_space' ) ) {
                $total_space = sprintf( '%.2f', disk_total_space( './' ) /1073741824 );
                echo '<span class="normal">'. $total_space .' GiB&nbsp;</span>';

            } else {
                echo '<span class="normal">'. _FPA_U .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Free Disk Space:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #ccebeb;">';
            if ( function_exists( 'disk_free_space' ) ) {

                $free_space = sprintf( '%.2f', disk_free_space( './' ) /1073741824 );
                $percent_free = $free_space ? round($free_space / $total_space, 2) * 100 : 0;

                if ( $percent_free <= '5' ) {
                    $status = 'warn';

                } else {
                    $status = 'normal';

                }

                echo '<span class="normal">(<span class="'. $status .'">'. $percent_free.'%</span>)  '. $free_space .' GiB&nbsp;</span>';
                $system['sysFREESPACE'] = $free_space .' GiB';
            } else {
                echo '<span class="normal">'. _FPA_U .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Server Name:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';

            if ( $showProtected == 1 ) {
                echo '<span class="normal">'. $system['sysSERVNAME'] .'&nbsp;</span>';

            } else {
                echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>&nbsp;';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Server IP:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';

            if ( $showProtected == 1 ) {
                echo '<span class="normal">'. $system['sysSERVIP'] .'&nbsp;</span>';

            } else {
                echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>&nbsp;';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Server Signature:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $system['sysSERVSIG'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom:1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Server Encoding:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;1px solid #ccebeb;">';
        echo '<span class="normal">'. $system['sysENCODING'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Executing User:<div style="line-height:11px;text-transform:none!important;float:right;font-size:9px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #ccebeb;">';
        echo '<span class="normal">'. $system['sysEXECUSER'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;font-weight:bold;padding:1px;padding-right:0px;padding-top:0px;padding-bottom:3px;text-transform:uppercase;">Server User:<div style="line-height:9px;text-transform:none!important;float:right;font-size:11px;font-weight:normal;width:60%;background-color:#fff;text-align:right;padding:1px;padding-top:0px;border-bottom-right-radius: 5px;-moz-border-bottom-right-radius: 5px;-webkit-border-bottom-right-radius: 5px;  border-bottom-left-radius: 5px;-moz-border-bottom-left-radius: 5px;-webkit-border-bottom-left-radius: 5px;border-right: 1px solid #42AEC2;border-left: 1px solid #42AEC2;border-bottom: 1px solid #42AEC2;">';
        echo '<span class="normal">'. $system['sysWEBOWNER'] .'&nbsp;</span>';
        echo '</div></div>';
        echo '</div>';

        echo "<br />";

        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Doc Root:<div style="float:right;">';


            if ( $showProtected <= 2 ) {
                echo '<span class="normal" style="font-size:9px;font-weight:normal;text-transform:none;">'. $system['sysDOCROOT'] .'&nbsp;</span>';

            } else {
                echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>&nbsp;';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Server Temp:<div style="float:right;">';

            if ( $showProtected <= 2 ) {
                echo '<span class="normal" style="font-size:9px;font-weight:normal;text-transform:none;">'. $system['sysSYSTMPDIR'] .'&nbsp;</span>';

            } else {
                echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>&nbsp;';

            }

        echo '</div></div>';
        echo '</div>';


        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Server Temp Writable:<div style="float:right;">';

            if ( $system['sysTMPDIRWRITABLE'] == _FPA_Y ) {
                echo '<span class="ok" style="font-size:9px;font-weight:normal;text-transform:none;">'. $system['sysTMPDIRWRITABLE'] .'&nbsp;</span>';

            } elseif ( $system['sysTMPDIRWRITABLE'] == _FPA_N ) {
                echo '<span class="alert-text" style="font-size:9px;font-weight:normal;text-transform:none;">'. $system['sysTMPDIRWRITABLE'] .'&nbsp;</span>';

            } else {
                echo '<span class="warn-text" style="font-size:9px;font-weight:normal;text-transform:none;">'. _FPA_U .'&nbsp;</span>';

            }

        echo '</div></div>';
        echo '</div>';


        //echo '<br style="clear:both;" />';
        echo '<div class="mini-content-box-small" style="">';
        echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Switch User Configuration:<div style="float:right;">';
        echo '<br style="clear:both;" />';
        echo '<div style="background-color: #fff;border:1px solid #42aec2;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;text-align:center;margin:2px;width:78px;padding:1px;float:left;font-size:8px;font-weight:normal;">suExec<br /><b>'. $phpenv['phpAPACHESUEXEC'] .'</b></div>';
        echo '<div style="background-color: #fff;border:1px solid #42aec2;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;text-align:center;margin:2px;width:78px;padding:1px;float:left;font-size:8px;font-weight:normal;">PHP suExec<br /><b>'. $phpenv['phpPHPSUEXEC'] .'</b></div>';
        echo '<div style="background-color: #fff;border:1px solid #42aec2;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;text-align:center;margin:2px;width:78px;padding:1px;float:left;font-size:8px;font-weight:normal;">Custom su<br /><b>'. $phpenv['phpCUSTOMSU'] .'</b></div>';
        echo '<div style="background-color: #fff;border:1px solid #42aec2;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;text-align:center;margin:2px;width:78px;padding:1px;float:left;font-size:8px;font-weight:normal;">Ownership Probs<br /><b>';

            if ( $phpenv['phpOWNERPROB'] == _FPA_N ) {
                $status = 'ok';

            } elseif ( $phpenv['phpOWNERPROB'] == _FPA_M ) {
                $status = 'warn-text';

            } elseif ( $phpenv['phpOWNERPROB'] == _FPA_Y ) {
                $status = 'alert-text';

            } else {
                $status = 'warn-text';

            }

        echo '<span class="'. $status .'" style="font-size:8px;">'. $phpenv['phpOWNERPROB'] .'</span>';
        echo '</b></div>';
        echo '</div></div>';
        echo '</div>';


        echo '<br style="clear:both;" />';
        echo '</div></div>';


        echo '<br style="clear:both;" />';
        echo '</div>';


    showDev( $phpenv );
    showDev( $system );
?>



<?php
        echo '<div class="section-information">'; // start right content block

        echo '<div class="section-title" style="text-align:center;">'. $phpextensions['ARRNAME'] .' :: Discovery</div>';
        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';

            foreach ( $phpextensions as $key => $show ) {

                if ( $show != $phpextensions['ARRNAME'] ) {

                    if ( $key == 'exif' ) {
                        $pieces = explode( " $", $show );
                        $show = $pieces[0];

                    }


                    // find the requirements and mark them as present or missing
                    if ( $key == 'libxml' OR $key == 'xml' OR $key == 'zip' OR $key == 'openssl' OR $key == 'zlib' OR $key == 'curl' OR $key == 'iconv' OR $key == 'mbstring' OR $key == 'mysql' OR $key == 'mysqli' OR $key == 'mcrypt' OR $key == 'suhosin' OR $key == 'cgi' OR $key == 'cgi-fcgi' ) {
                        $status = 'ok';
                        $border = '4D8000';
                        $background = 'CAFFD8';
                        $weight = 'normal';
                    } elseif ( $key == 'apache2handler' ) {
                        $status = 'warn-text';
                        $border = 'FFA500';
                        $background = 'FFE4B5';
                        $weight = 'bold';
                    } else {
                        $status = 'normal';
                        $border = '42AEC2';
                        $background = 'FFF';
                        $weight = 'normal';
                    }


                    echo '<div style="background-color: #'. $background .';border:1px solid #'. $border .';border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;text-align:center;margin:2px;padding:1px;min-height:20px;width:82px;float:left;font-size:8px;"><span class="'. $status .'" style="font-size:8px;font-weight:'. $weight .';text-shadow:1px 1px 1px #fff;">'. $key .'</span><br />'. $show .'</div>';

                } // endif !arrname


                // look for recommended extensions that aren't installed
                if ( !in_array( $key, $phpreq ) ) {
                    unset ( $phpreq[$key] );

                }

            } // end foreach


            if ( $phpreq ) {
                echo '<br style="clear:both;" /><br />';
                echo '<div class="mini-content-box-small" style="">';
                echo '<div class="warn-text" style="line-height:10px;font-size:9px;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Potential Missing PHP Extensions:<br /><div style="float:left;text-transform:none;">';

                echo '<br style="clear:both;" />';

                $status = 'warn-text';
                $border = 'FFA500';
                $background = 'FFF';
                $weight = 'bold';


                foreach ( $phpreq as $missingkey => $missing ) {
                    echo '<div style="background-color: #'. $background .';border:1px solid #'. $border .';border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;text-align:center;margin:2px;padding:1px;min-height:10px;width:82px;float:left;font-size:8px;"><span class="'. $status .'" style="font-size:8px;font-weight:'. $weight .';text-shadow:1px 1px 1px #fff;">'. $missingkey .'</span></div>';

                }

                echo '</div></div>';
                echo '</div>';

            }

        echo '<br style="clear:both;" />';
        echo '</div></div>';

    showDev( $phpextensions );
    $phpreq['ARRNAME'] = 'Potential Missing PHP Extensions';

    showDev( $phpreq );
    unset ( $key, $show );
?>



<?php
        if ( $phpenv['phpAPI'] == 'apache2handler' ) {

            /** display the instance information *************************************************/
            echo '<div class="section-information">'; // start right content block
            echo '<div class="section-title" style="text-align:center;">'. $apachemodules['ARRNAME'] .' :: Discovery</div>';
            echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';

                foreach ( $apachemodules as $key => $show ) {

                    if ( $show != $apachemodules['ARRNAME'] ) {

                        // find the requirements and mark them as present or missing
                        if ( $show == 'mod_rewrite' OR $show == 'mod_cgi' OR $show == 'mod_cgid' OR $show == 'mod_expires' OR $show == 'mod_deflate' OR $show == 'mod_auth'  ) {
                            $status = 'ok';
                            $border = '4D8000';
                            $background = 'CAFFD8';
                            $weight = 'normal';

                        } elseif ( $show == 'mod_php4' ) {
                            $status = 'warn-text';
                            $border = 'FFA500';
                            $background = 'FFE4B5';
                            $weight = 'bold';

                        } else {
                            $status = 'normal';
                            $border = '42AEC2';
                            $background = 'FFF';
                            $weight = 'normal';

                        }


                        echo '<div class="'. $status .'" style="background-color: #'. $background .';border:1px solid #'. $border .';font-weight: '. $weight .';border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;text-align:center;margin:2px;width:82px;padding:1px;float:left;font-size:8px;">'. $show .'</div>';


                    } // endif !arrname


                    // look for recommended extensions that aren't installed
                    if ( !in_array( $show, $apachereq ) ) {
                        unset ( $apachereq['ARRNAME'] );
                        unset ( $apachereq[$show] );

                    }

                } // end foreach



                if ( $apachereq ) {
                    echo '<br style="clear:both;" /><br />';
                    echo '<div class="mini-content-box-small" style="">';
                    echo '<div class="warn-text" style="line-height:10px;font-size:9px;text-shadow: #fff 1px 1px 1px;width:99%;border-bottom: 1px solid #ccebeb;font-weight:bold;padding:1px;padding-top:0px;padding-right:0px;padding-bottom:2px;text-transform:uppercase;">Potential Missing Apache Modules:<br /><div style="float:left;text-transform:none;">';

                    echo '<br style="clear:both;" />';

                    $status = 'warn-text';
                    $border = 'FFA500';
                    $background = 'FFF';
                    $weight = 'bold';

                    foreach ( $apachereq as $missingkey => $missing ) {
                        echo '<div style="background-color: #'. $background .';border:1px solid #'. $border .';border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;text-align:center;margin:2px;padding:1px;min-height:10px;width:82px;float:left;font-size:8px;"><span class="'. $status .'" style="font-size:8px;font-weight:'. $weight .';text-shadow:1px 1px 1px #fff;">'. $missingkey .'</span></div>';

                    }

                    echo '</div></div>';
                    echo '</div>';

                }

        echo '<br style="clear:both;" />';
        echo '</div></div>';

        showDev( $apachemodules );
        $apachereq['ARRNAME'] = 'Potential Missing Apache Modules';

        showDev( $apachereq );
        unset ( $key, $show );

        }
?>



<?php
    /** display the mode-set details for each core folder ********************************/
    echo '<div class="section-information">';
    echo '<div class="section-title" style="text-align:center;">'. $folders['ARRNAME'] .' '. $modecheck['ARRNAME'] .'</div>';

    echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';
    // this is the column heading area, if any

    echo '<div class="column-title" style="width:7%;float:left;text-align:center;">'. _FPA_MODE .'</div>';
    echo '<div class="column-title" style="width:8%;float:left;left;text-align:center;">'. _FPA_WRITABLE .'</div>';
    echo '<div class="column-title" style="width:58%;float:left;">'. _FPA_FOLDER .'</div>';
    echo '<div class="column-title" style="width:12%;float:right;text-align:center;">'. _FPA_GROUP .'</div>';
    echo '<div class="column-title" style="width:12%;float:right;text-align:center;">'. _FPA_OWNER .'</div>';
    echo '<div style="clear:both;"></div>';
    echo '</div>';

    // only do mode/permissions checks if an instance was found in the intial checks
    if ( $instance['instanceFOUND'] == _FPA_Y ) {
    // this is the content area

        foreach ( $folders as $i => $show ) {

            if ( $show != 'Core Folders' ) {

                // looking for --7 or -7- or -77 (default folder permissions are usually 755)
                if ( substr( $modecheck[$show]['mode'],1 ,1 ) == '7' OR substr( $modecheck[$show]['mode'],2 ,1 ) == '7' ) {
                    $modeClass = 'alert';
                    $alertClass = 'alert-text';
                    $userClass = 'normal';
                    $groupClass = 'normal';
                } elseif ( $modecheck[$show]['mode'] == '755' ) {
                    $modeClass = 'ok';
                    $alertClass = 'normal';
                    $userClass = 'normal';
                    $groupClass = 'normal';
                } else if ( substr( $modecheck[$show]['mode'],0 ,1 ) <= '5' AND $modecheck[$show]['mode'] != '---' ) {
                    $modeClass = 'warn';
                    $alertClass = 'warn-text';
                    $userClass = 'normal';
                    $groupClass = 'normal';
                } else if ( $modecheck[$show]['group']['name'] == _FPA_N ) {
                    $modeClass = 'warn-text';
                    $alertClass = 'warn-text';
                    $userClass = 'warn-text';
                    $groupClass = 'warn-text';
                } else {
                    $modeClass = 'normal';
                    $alertClass = 'normal';
                    $userClass = 'normal';
                    $groupClass = 'normal';
                }

                // is the folder writable?
                if ( ( $modecheck[$show]['writable'] != _FPA_Y ) ) {
                    $writeClass = 'warn-text';
                } elseif ( ( $modecheck[$show]['writable'] == _FPA_Y ) AND ( substr( $modecheck[$show]['mode'],0 ,1 ) == '7' ) ) {
                    $writeClass = 'normal';
                } elseif ( $modecheck[$show]['writable'] == _FPA_N ) {
                    $writeClass = 'ok';
                }

                // is the 'executing' owner the same as the folder owner? and is the users groupID the same as the folders groupID?
                if ( ( $modecheck[$show]['owner']['name'] != $system['sysEXECUSER'] ) AND ( $modecheck[$show]['group']['name'] != _FPA_DNE ) ) {
                    $userClass = 'warn-text';
                    $groupClass = 'normal';
                } elseif ( isset( $modecheck[$show]['group']['gid'] ) AND isset( $modecheck[$show]['owner']['gid'] ) ) {

                    if ( $modecheck[$show]['group']['gid'] != $modecheck[$show]['owner']['gid'] ) {
                        $userClass = 'normal';
                        $groupClass = 'warn-text';
                    }

                } elseif ( $modecheck[$show]['group']['name'] == _FPA_DNE ) {
                    $modeClass = 'warn-text';
                    $alertClass = 'warn-text';
                    $writeClass = 'warn-text';
                    $userClass = 'warn-text';
                    $groupClass = 'warn-text';
                }

                // produce the output
                echo '<div style="border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;">';

                echo '<div class="column-content '. $modeClass .'" style="float:left;width:7%;text-align:center;">';
                echo $modecheck[$show]['mode'];  // display the mode
                echo '</div>';

                echo '<div class="column-content '. $writeClass .'" style="width:8%;float:left;text-align:center;">';
                echo $modecheck[$show]['writable'];  // display if writable
                echo '</div>';

                echo '<div class="column-content '. $alertClass .'" style="width:58%;float:left;padding-left:5px;">';

                    if ( $showProtected <= 2 ) {
                        echo $show;  // display the folder name
                    } else {
                        echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>';
                    }

                echo '</div>';

                echo '<div class="column-content '. $groupClass .'" style="float:right;width:12%;text-align:center;">';
                echo $modecheck[$show]['group']['name'];  // display the group
                echo '</div>';

                echo '<div class="column-content '. $userClass .'"" style="float:right;width:12%;text-align:center;">';
                echo $modecheck[$show]['owner']['name'];  // display the owner
                echo '</div>';

                echo '<div style="clear:both;"></div>';
                echo '</div>';
            }
        }

    } else { // an instance wasn't found in the initial checks, so no folders to check
        echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
        echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Instance not found, no '. $modecheck['ARRNAME'] .' performed</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '<div style="clear:both;"></div>';

    // !TODO fix missing heading properly rather than this messy work-around
    $folders['ARRNAME'] = 'Core Folders';
    showDev( $folders );
    showDev( $modecheck );
    unset ( $key, $show );
?>


<?php
    /** display the folders with elevated permissions ************************************/
    if ( $showElevated == '1' ) {

        echo '<div class="section-information">';
        echo '<div class="section-title" style="text-align:center;">'. $elevated['ARRNAME'] .' (First 10)</div>';

        echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';

        echo '<div class="column-title" style="width:7%;float:left;text-align:center;">'. _FPA_MODE .'</div>';
        echo '<div class="column-title" style="width:8%;float:left;left;text-align:center;">'. _FPA_WRITABLE .'</div>';
        echo '<div class="column-title" style="width:58%;float:left;">'. _FPA_FOLDER .'</div>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';


        // only do mode/permissions checks if an instance was found in the intial checks
        if ( $instance['instanceFOUND'] == _FPA_Y ) {

            foreach ( $elevated as $key => $show ) {

                if ( $show != $elevated['ARRNAME'] ) {

                    // looking for --7 or -7- or -77 (default folder permissions are usually 755)
                    if ( substr( $show['mode'],1 ,1 ) == '7' OR substr( $show['mode'],2 ,1 ) == '7' ) {
                        $modeClass = 'alert';
                        $alertClass = 'alert-text';
                    } else {
                        $modeClass = 'normal';
                        $alertClass = 'normal';
                    }

                    // is the folder writable?
                    if ( ( $show['writable'] == _FPA_Y ) ) {
                        $writeClass = 'alert-text';
                    } else {
                        $writeClass = 'warn-text';
                    }

                    // hilite the "cancelled processing" message, after 25 elevated folders (limited to save resources on a really bad site
//                    if ( substr( $key, 0, 4 ) == '*PRO' ) {
//                        $alertClass = 'alert';
//                    }

                    echo '<div style="border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;">';

                    echo '<div class="column-content '. $modeClass .'" style="float:left;width:7%;text-align:center;">';
                    echo $show['mode'];  // display the mode
                    echo '</div>';

                    echo '<div class="column-content '. $writeClass .'" style="width:8%;float:left;text-align:center;">';
                    echo $show['writable'];  // display if writable
                    echo '</div>';

                    echo '<div class="column-content '. $alertClass .'" style="width:58%;float:left;padding-left:5px;">';

                        if ( $showProtected <= 2 ) {

                            if ( $key == 'None' ) {
                                echo '<span style="color:#008000"><b>'. $key .'</b></span>';
                            } else {
                                echo $key .'/ (';
                            }

//                        }
//                            echo $key;  // display the folder name

                        } else {
                            echo '<span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span>';
                        }

                    echo '</div>';

                    echo '<div style="clear:both;"></div>';
                    echo '</div>';

                } // endif ARRNAME

            } // end for each


        } else { // an instance wasn't found in the initial checks, so no folders to check
            echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
            echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Instance not found, no '. $elevated['ARRNAME'] .' checks performed</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '<div style="clear:both;"></div>';

        showDev( $elevated );
        unset ( $key, $show );
    } // endif showElevated
?>














<?php
    if ( $showComponents == '1' ) {

        echo '<div class="section-information">';

        echo '<div class="section-title" style="text-align:center;">'. $component['ARRNAME'] .' :: Site</div>';

        echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';
        echo '<div class="column-title" style="width:20%;float:left;text-align:left;">Name</div>';
        echo '<div class="column-title" style="width:14%;float:left;text-align:center;">Version</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Created</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Author</div>';
        echo '<div class="column-title" style="width:19%;float:left;">Address</div>';
        echo '<div class="column-title" style="width:10%;float:right;text-align:center;">Status</div>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';


        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';


        echo '<div class="mini-content-box-small" style="">';
        if ( $instance['instanceFOUND'] == _FPA_Y ) { // an instance wasn't found in the initial checks, so no folders to check

            foreach ( $component['SITE'] as $key => $show ) {

                if ( $show['type'] != '3rd Party' ) {
                    $typeColor = '404040';
                } else {
                    $typeColor = '000080';
                }

                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;border-bottom:1px solid #ccebeb;padding:1px;padding-right:0px;padding-bottom:3px;">';

                if ( $showProtected <= 2 ) {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                } else {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';"><span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span></div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                }
//                echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';

                echo '</div>';
            }

        } else {
//        if ( $instance['instanceFOUND'] == _FPA_N ) { // an instance wasn't found in the initial checks, so no folders to check
            echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
            echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Instance not found, no '. $component['ARRNAME'] .' checks performed</div>';
            echo '</div>';
        }

        echo '</div></div>';




        echo '<br style="clear:both;" />';
        echo '<div class="section-title" style="text-align:center;">'. $component['ARRNAME'] .' :: Admin</div>';

        echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';
        echo '<div class="column-title" style="width:20%;float:left;text-align:left;">Name</div>';
        echo '<div class="column-title" style="width:14%;float:left;text-align:center;">Version</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Created</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Author</div>';
        echo '<div class="column-title" style="width:19%;float:left;">Address</div>';
        echo '<div class="column-title" style="width:10%;float:right;text-align:center;">Status</div>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';

        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';
        if ( $instance['instanceFOUND'] == _FPA_Y ) { // an instance wasn't found in the initial checks, so no folders to check

            foreach ( $component['ADMIN'] as $key => $show ) {

                if ( $show['type'] != '3rd Party' ) {
                    $typeColor = '404040';
                } else {
                    $typeColor = '000080';
                }

                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;border-bottom:1px solid #ccebeb;padding:1px;padding-right:0px;padding-bottom:3px;">';

                if ( $showProtected <= 2 ) {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                } else {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';"><span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span></div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                }
//                echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';

                echo '</div>';
            }


        } else {
//        if ( $instance['instanceFOUND'] == _FPA_N ) { // an instance wasn't found in the initial checks, so no folders to check
            echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
            echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Instance not found, no '. $component['ARRNAME'] .' checks performed</div>';
            echo '</div>';
        }


        echo '</div></div>';



        echo '<br style="clear:both;" />';
        echo '</div></div>';
        // end content left block


    echo '<div style="clear:both;"></div>';

    showDev( $component );
    unset ( $key, $show );
    echo '</div>'; // end half-section container

    echo '<div style="clear:both;"></div>';

    } // end showComponents
?>










<?php
    if ( $showModules == '1' ) {

        echo '<div class="section-information">';

        echo '<div class="section-title" style="text-align:center;">'. $module['ARRNAME'] .' :: Site</div>';

        echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';
        echo '<div class="column-title" style="width:20%;float:left;text-align:left;">Name</div>';
        echo '<div class="column-title" style="width:14%;float:left;text-align:center;">Version</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Created</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Author</div>';
        echo '<div class="column-title" style="width:19%;float:left;">Address</div>';
        echo '<div class="column-title" style="width:10%;float:right;text-align:center;">Status</div>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';


        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';


        echo '<div class="mini-content-box-small" style="">';
        if ( $instance['instanceFOUND'] == _FPA_Y ) { // an instance wasn't found in the initial checks, so no folders to check

            foreach ( $module['SITE'] as $key => $show ) {

                if ( $show['type'] != '3rd Party' ) {
                    $typeColor = '404040';
                } else {
                    $typeColor = '000080';
                }

                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;border-bottom:1px solid #ccebeb;padding:1px;padding-right:0px;padding-bottom:3px;">';

                if ( $showProtected <= 2 ) {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                } else {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';"><span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span></div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                }
//                echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';

                echo '</div>';
            }

        } else {
//        if ( $instance['instanceFOUND'] == _FPA_N ) { // an instance wasn't found in the initial checks, so no folders to check
            echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
            echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Instance not found, no '. $module['ARRNAME'] .' checks performed</div>';
            echo '</div>';
        }

        echo '</div></div>';




        echo '<br style="clear:both;" />';
        echo '<div class="section-title" style="text-align:center;">'. $module['ARRNAME'] .' :: Admin</div>';

        echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';
        echo '<div class="column-title" style="width:20%;float:left;text-align:left;">Name</div>';
        echo '<div class="column-title" style="width:14%;float:left;text-align:center;">Version</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Created</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Author</div>';
        echo '<div class="column-title" style="width:19%;float:left;">Address</div>';
        echo '<div class="column-title" style="width:10%;float:right;text-align:center;">Status</div>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';

        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';
        if ( $instance['instanceFOUND'] == _FPA_Y ) { // an instance wasn't found in the initial checks, so no folders to check

            foreach ( $module['ADMIN'] as $key => $show ) {

                if ( $show['type'] != '3rd Party' ) {
                    $typeColor = '404040';
                } else {
                    $typeColor = '000080';
                }

                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;border-bottom:1px solid #ccebeb;padding:1px;padding-right:0px;padding-bottom:3px;">';

                if ( $showProtected <= 2 ) {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                } else {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';"><span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span></div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                }
//                echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';

                echo '</div>';
            }

        } else {
//        if ( $instance['instanceFOUND'] == _FPA_N ) { // an instance wasn't found in the initial checks, so no folders to check
            echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
            echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Instance not found, no '. $module['ARRNAME'] .' checks performed</div>';
            echo '</div>';
        }

        echo '</div></div>';



        echo '<br style="clear:both;" />';
        echo '</div></div>';
        // end content left block


    echo '<div style="clear:both;"></div>';

    showDev( $module );
    unset ( $key, $show );
    echo '</div>'; // end half-section container

    echo '<div style="clear:both;"></div>';

    } // end showModules
?>










<?php
    if ( $showPlugins == '1' ) {

        echo '<div class="section-information">';

        echo '<div class="section-title" style="text-align:center;">'. $plugin['ARRNAME'] .' :: Site</div>';

        echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';
        echo '<div class="column-title" style="width:20%;float:left;text-align:left;">Name</div>';
        echo '<div class="column-title" style="width:14%;float:left;text-align:center;">Version</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Created</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Author</div>';
        echo '<div class="column-title" style="width:19%;float:left;">Address</div>';
        echo '<div class="column-title" style="width:10%;float:right;text-align:center;">Status</div>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';


        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';



        echo '<div class="mini-content-box-small" style="">';
        if ( $instance['instanceFOUND'] == _FPA_Y ) { // an instance wasn't found in the initial checks, so no folders to check

            foreach ( $plugin['SITE'] as $key => $show ) {

                if ( $show['type'] != '3rd Party' ) {
                    $typeColor = '404040';
                } else {
                    $typeColor = '000080';
                }

                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;border-bottom:1px solid #ccebeb;padding:1px;padding-right:0px;padding-bottom:3px;">';

                if ( $showProtected <= 2 ) {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                } else {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';"><span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span></div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                }
//                echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';

                echo '</div>';
            }

        } else {
//        if ( $instance['instanceFOUND'] == _FPA_N ) { // an instance wasn't found in the initial checks, so no folders to check
            echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
            echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Instance not found, no '. $plugin['ARRNAME'] .' checks performed</div>';
            echo '</div>';
        }

        echo '</div></div>';


        echo '<br style="clear:both;" />';
        echo '</div></div>';
        // end content left block


    echo '<div style="clear:both;"></div>';

    showDev( $plugin );
    unset ( $key, $show );
    echo '</div>'; // end half-section container

    echo '<div style="clear:both;"></div>';

    } // end showPlugins
?>










<?php
        echo '<div class="section-information">';

        echo '<div class="section-title" style="text-align:center;">'. $template['ARRNAME'] .' :: Site</div>';

        echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';
        echo '<div class="column-title" style="width:20%;float:left;text-align:left;">Name</div>';
        echo '<div class="column-title" style="width:14%;float:left;text-align:center;">Version</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Created</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Author</div>';
        echo '<div class="column-title" style="width:19%;float:left;">Address</div>';
        echo '<div class="column-title" style="width:10%;float:right;text-align:center;">Status</div>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';


        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';


        echo '<div class="mini-content-box-small" style="">';
        if ( $instance['instanceFOUND'] == _FPA_Y ) { // an instance wasn't found in the initial checks, so no folders to check

            foreach ( $template['SITE'] as $key => $show ) {

                if ( $show['type'] != '3rd Party' ) {
                    $typeColor = '404040';
                } else {
                    $typeColor = '000080';
                }

                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;border-bottom:1px solid #ccebeb;padding:1px;padding-right:0px;padding-bottom:3px;">';

                if ( $showProtected <= 2 ) {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                } else {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';"><span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span></div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                }
//                echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';

                echo '</div>';
            }

        } else {
            //        if ( $instance['instanceFOUND'] == _FPA_N ) { // an instance wasn't found in the initial checks, so no folders to check
            echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
            echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Instance not found, no '. $template['ARRNAME'] .' checks performed</div>';
            echo '</div>';
        }

        echo '</div></div>';




        echo '<br style="clear:both;" />';
        echo '<div class="section-title" style="text-align:center;">'. $template['ARRNAME'] .' :: Admin</div>';

        echo '<div class="column-title-container" style="width:99%;margin: 0px auto;clear:both;display:block;">';
        echo '<div class="column-title" style="width:20%;float:left;text-align:left;">Name</div>';
        echo '<div class="column-title" style="width:14%;float:left;text-align:center;">Version</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Created</div>';
        echo '<div class="column-title" style="width:14%;float:left;">Author</div>';
        echo '<div class="column-title" style="width:19%;float:left;">Address</div>';
        echo '<div class="column-title" style="width:10%;float:right;text-align:center;">Status</div>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';

        echo '<div class="" style="width:99%;margin: 0px auto;clear:both;margin-bottom:10px;">';
        if ( $instance['instanceFOUND'] == _FPA_Y ) { // an instance wasn't found in the initial checks, so no folders to check

            foreach ( @$template['ADMIN'] as $key => $show ) {

                if ( $show['type'] != '3rd Party' ) {
                    $typeColor = '404040';
                } else {
                    $typeColor = '000080';
                }

                echo '<div style="line-height:10px;font-size:8px;color:#404040;text-shadow: #fff 1px 1px 1px;border-bottom:1px solid #ccebeb;padding:1px;padding-right:0px;padding-bottom:3px;">';

                if ( $showProtected <= 2 ) {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                } else {
                    echo '<div style="float:left;width:20%;color:#'. $typeColor .';"><span class="protected">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</span></div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';
                }
//                echo '<div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['name'] .'</div><div style="float:left;width:15%;text-align:center;color:#'. $typeColor .';">'. $show['version'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['creationDate'] .'</div><div style="float:left;width:15%;color:#'. $typeColor .';">'. $show['author'] .'</div><div style="float:left;width:20%;color:#'. $typeColor .';">'. $show['authorUrl'] .'</div><div style="float:right;width:10%;color:#'. $typeColor .';text-align:center;">'. $show['type'] .'</div><br />';

                echo '</div>';
            }

//        if ( $instance['instanceFOUND'] == _FPA_N ) { // an instance wasn't found in the initial checks, so no folders to check
        } else {
            echo '<div style="text-align:center;border-bottom:1px dotted #C0C0C0;width:99%;margin: 0px auto;padding-top:1px;padding-bottom:1px;clear:both;font-size: 11px;">';
            echo '<div class="warn" style=" margin-top:10px;margin-bottom:10px;">Instance not found, no '. $template['ARRNAME'] .' checks performed</div>';
            echo '</div>';
        }

        echo '</div></div>';



        echo '<br style="clear:both;" />';
        echo '</div></div>';
        // end content left block


    echo '<div style="clear:both;"></div>';

    showDev( $template );
    unset ( $key, $show );
    echo '</div>'; // end half-section container

    echo '<div style="clear:both;"></div>';
?>














<?php
    showDev( $fpa );

    if ( defined( '_FPA_DEV' ) ) {

        echo '<div class="dev-mode-information">';
        echo '<span class="dev-mode-title">'. _RES .' Memory Statistics : </span> (requires PHP4.3.2 & PHP5.2.0)<br />';
        echo '<div style="margin-left: 10px;">';

            function convert($size) {
                $unit=array('b','kb','mb','gb','tb','pb');
                return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
            }

                if( function_exists('memory_get_usage') ) {
                    echo 'Currently Allocated Memory: '. convert( memory_get_usage() ) .'<br />'; // currently allocated memory
                } else {
                    echo _PHP_VERLOW .', memory_get_usage '. _FPA_DNE .'<br />';
                }


                if( function_exists('memory_get_peak_usage') ) {
                    echo 'Total Peak Memory: '. convert( memory_get_peak_usage(true) ); // total peak memory usage
                } else {
                    echo _PHP_VERLOW .', memory_get_peak_usage '. _FPA_DNE .'<br />';
                }

        echo '<p style="font-weight:bold;"><em>total runtime : '. mt_end() .' seconds</em></p>';
        echo '</div>';
        echo '</div>';
    }







        echo '<div class="snapshot-information" style="text-align:center;color:#4D8000!important;padding-top:10px;">';
        echo '<span class="header-title">Legends and Settings</span>';
        echo '<div style="width:85%;margin:0 auto;margin-top:10px;">';
        // LEGENDS
        echo '<div class="half-section-container" style="clear:both;width:100%;">';
//        echo '<div class="normal" style="width:30px;float:left;margin-right:10px;font-variant:small-caps;">'. _FPA_LEGEND .'</div>';
        echo '<div class="ok-hilite" style="text-align:center;width:21%;float:left;margin-left:10px;margin-right:10px;">'. _FPA_GOOD .'</div>';
        echo '<div class="warn" style="text-align:center;width:21%;float:left;margin-left:10px;margin-right:10px;">'. _FPA_WARNINGS .'</div>';
        echo '<div class="alert" style="text-align:center;width:21%;float:left;margin-left:10px;margin-right:10px;">'. _FPA_ALERTS .'</div>';
        echo '<div class="protected" style="text-align:center;width:21%;float:left;margin-left:10px;margin-right:10px;">[&nbsp;--&nbsp;'. _FPA_HIDDEN .'&nbsp;--&nbsp;]</div>';
        echo '</div>';
        echo '<div style="clear:both;"><br /></div>';


        // SELECTIONS
        echo '<div style="font-weight:bold;width:25%;float:left;text-align:center;">Developer-Mode<br />';
            if ( defined ( '_FPA_DEV' ) ) {
                echo '<div class="normal-note">Enabled</div>';
            } else {
                echo '<div class="normal-note">Disabled (Default)</div>';
            }
        echo '</div>';

        echo '<div style="font-weight:bold;width:25%;float:left;text-align:center;">Information Privacy<br />';
            if ( $showProtected == 1 ) {
                echo '<div class="normal-note"><span class="ok">None</span></div>';
            } elseif ( $showProtected == 2 ) {
                echo '<div class="normal-note"><span class="warn-text">Partial</span> (Default)</div>';
            } elseif ( $showProtected >= 3 ) {
                echo '<div class="normal-note"><span class="alert-text">Strict</span></div>';
            }
        echo '</div>';

        echo '<div style="font-weight:bold;width:25%;float:left;text-align:center;">Elevated Permissions<br />';
            if ( $showElevated == 1 ) {
                echo '<div class="normal-note">Show</div>';
            } else {
                echo '<div class="normal-note">Hide (Default)</div>';
            }
        echo '</div>';

        echo '<div style="font-weight:bold;width:25%;float:left;text-align:center;">DataBase Tables<br />';
            if ( $showTables == '1' ) {
                echo '<div class="normal-note">Show</div>';
            } else {
                echo '<div class="normal-note">Hide (Default)</div>';
            }
        echo '</div>';

        echo '<br style="clear:both;" />';

        echo '<div style="font-weight:bold;width:25%;float:left;text-align:center;">Diagnostic-Mode<br />';
            if ( defined ( '_FPA_DIAG' ) ) {
                echo '<div class="normal-note">Enabled</div>';
            } else {
                echo '<div class="normal-note">Disabled (Default)</div>';
            }
        echo '</div>';

        echo '<div style="font-weight:bold;width:25%;float:left;text-align:center;">Components<br />';
            if ( $showComponents == '1' ) {
                echo '<div class="normal-note">Show</div>';
            } else {
                echo '<div class="normal-note">Hide (Default)</div>';
            }
        echo '</div>';

        echo '<div style="font-weight:bold;width:25%;float:left;text-align:center;">Modules<br />';
            if ( $showModules == '1' ) {
                echo '<div class="normal-note">Show</div>';
            } else {
                echo '<div class="normal-note">Hide (Default)</div>';
            }
        echo '</div>';

        echo '<div style="font-weight:bold;width:25%;float:left;text-align:center;">Plugins<br />';
            if ( $showPlugins == '1' ) {
                echo '<div class="normal-note">Show</div>';
            } else {
                echo '<div class="normal-note">Hide (Default)</div>';
            }
        echo '</div>';

        echo '</div>';
        echo '<div style="clear:both;"><br /></div>';

        echo '<div style="text-align:right!important;"><a style="color:#4D8000!important;" href="'. _RES_FPALINK .'" target="_github">'. _RES_FPALATEST .' '. _RES .'</a></div>';
        echo '</div>';


?>



    </body>
</html>