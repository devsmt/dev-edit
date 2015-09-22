#!/usr/bin/env php
<?php
/*
scopo del programma
*/

//----------------------------------------------------------------------------
//  subroutines
//----------------------------------------------------------------------------
class CLI {
    public static function hasFlag($flag) {
        if (isset($_SERVER['argv']) && !empty($_SERVER['argv'])) {
            $argv = $_SERVER['argv'];
            $s_argv = implode(' ', $argv) . ' ';
            $substr = " --$flag ";
            return strpos($s_argv, $substr) !== false;
        } else {
            return false;
        }
    }
}
function println($msg){ echo $msg."\n"; }

class ENV {
    // inizializza un l'environment Zend
    static function envBootstrap() {
        // Define path to application directory
        defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
        define('LIBRARY_ROOT', realpath(dirname(__FILE__) . '/../library'));
        define('DB_LIB', 'la_dat', false);
        set_include_path(implode(PATH_SEPARATOR, [LIBRARY_ROOT, get_include_path()]));
        $configSection = 'general';
        defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $configSection));
        require_once 'Zend/Application.php';
        $application = new \Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configuration/config.ini');
        // questo serve  aforzare env=dev
        $_SERVER['HTTP_HOST'] = 'localhost';
        require_once 'Zend/Loader/Autoloader.php';
        $loader = \Zend_Loader_Autoloader::getInstance();
        $loader->registerNamespace('DMS');
        $loader->registerNamespace('AS400');
        require_once APPLICATION_PATH . '/bootstrap.php';
        $bootstrap = new \Bootstrap($configSection);
        // // utilizzato dai DAO
        // require_once LIBRARY_ROOT.'/apache-log4php/src/main/php/Logger.php';
        // Logger::configure(array('rootLogger' => array('appenders' => array('default'),), 'appenders' => array('default' => array('class' => 'LoggerAppenderConsole', 'layout' => array('class' => 'LoggerLayoutSimple')))));
        // $GLOBALS['logger'] = Logger::getLogger('main');
        // \Zend_Session::start();
        $time_limit_sec = 8 * 60 * 60; // 8h
        set_time_limit($time_limit_sec);
        ini_set('memory_limit', '521M');
        date_default_timezone_set('Europe/Rome');
        mb_internal_encoding('UTF-8');
    }
    public static function registerExceptionErrHandler() {
        // tutti i warning vengono presentati come eccezioni
        // le eccezioni hanno un handler custom(in prdoduzione)
        set_error_handler(function ($errno, $errstr, $errfile, $errline, array $errcontext) {
                // error was suppressed with the @-operator
                if (0 === error_reporting()) {
                    return false;
                }
                throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }, E_WARNING);
    }
}

//----------------------------------------------------------------------------
//  controller
//----------------------------------------------------------------------------
//main controller
class Main {
    public static function run() {
        $action = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : 'test';
        $action = strtoupper( $action );
        switch($action) {
            case 'x':
                die(' ... ');
            break;
            default:
                die(self::actionUsage());
            break;
        }
    }

    //----------------------------------------------------------------------------
    //  actions
    //----------------------------------------------------------------------------
    function actionUsage() {
            return <<<__END__
    uso:
        {$_SERVER['argv'][0]} [action] [--go] [--test]
    uso del programma
__END__;
    }
}

//----------------------------------------------------------------------------
//  main
//----------------------------------------------------------------------------


define('DEBUG', CLI::hasFlag('debug') );

// if called directly, run
if ( basename($_SERVER['argv'][0]) == basename(__FILE__) ) {
    try {
        Main::run();
    } catch (Exception $e) {
        $msg = sprintf('Exception: %s line:%s file:%s %s', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
        println($msg);
    }
} else {
    // used as a library, register error handler
}



