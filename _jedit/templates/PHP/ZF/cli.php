<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH',
              realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'production'));

require_once 'Zend/Application.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/config.php'
);

//only load resources we need for script, in this case db and mail
$application->getBootstrap()->bootstrap(array('db', 'mail'));
// You can then proceed to use ZF resources just as you would in an MVC application:

$db = $application->getBootstrap()->getResource('db');

$row = $db->fetchRow('SELECT * FROM something');
// If you wish to add configurable arguments to your CLI script, take a look at Zend_Console_Getopt

// If you find that you have common code that you also call in MVC applications,
// look at wrapping it up in an object and calling that object's methods from both
// the MVC and the command line applications. This is general good practice.




/* metodo 1
// initialize the application path, library and autoloading
defined('APPLICATION_PATH') ||
 define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));

// NOTE: if you already have "library" directory available in your include path
// you don't need to modify the include_path right here
// so in that case you can leave last 4 lines commented
// to avoid receiving error message:
// Fatal error: Cannot redeclare class Zend_Loader in ....
// NOTE: anyway you can uncomment last 4 lines of this comments block
// to manually set the include path directory
// $paths = explode(PATH_SEPARATOR, get_include_path());
// $paths[] = realpath(__DIR__.'/../library');
// set_include_path(implode(PATH_SEPARATOR, $paths));
// unset($paths);

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();

// we need this custom namespace to load our custom class
$loader->registerNamespace('Custom_');

// define application options and read params from CLI
$getopt = new Zend_Console_Getopt(array(
    'action|a=s' => 'action to perform in format of "module/controller/action/param1/param2/param3/.."',
    'env|e-s'    => 'defines application environment (defaults to "production")',
    'help|h'     => 'displays usage information',
));

try {
    $getopt->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    // Bad options passed: report usage
    echo $e->getUsageMessage();
    return false;
}

// show help message in case it was requested or params were incorrect (module, controller and action)
if ($getopt->getOption('h') || !$getopt->getOption('a')) {
    echo $getopt->getUsageMessage();
    return true;
}

// initialize values based on presence or absence of CLI options
$env      = $getopt->getOption('e');
defined('APPLICATION_ENV')
 || define('APPLICATION_ENV', (null === $env) ? 'production' : $env);

// initialize Zend_Application
$application = new Zend_Application (
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// bootstrap and retrive the frontController resource
$front = $application->getBootstrap()
      ->bootstrap('frontController')
      ->getResource('frontController');

// This is a dummy-router that shouldn't do anything on routing
class Custom_Controller_Router_Cli extends Zend_Controller_Router_Abstract implements Zend_Controller_Router_Interface {
 public function route(Zend_Controller_Request_Abstract $dispatcher){}
    public function assemble($userParams, $name = null, $reset = false, $encode = true){}
    public function getFrontController(){}
    public function setFrontController(Zend_Controller_Front $controller){}
    public function setParam($name, $value){}
    public function setParams(array $params){}
    public function getParam($name){}
    public function getParams(){}
    public function clearParams($name = null){}
    public function addRoute() {}
    public function setGlobalParam() {}
    public function addConfig(){}
    // TODO: possibly some additional methods should be added
}

// magic starts from this line!
//
// we will use Zend_Controller_Request_Simple and some kind of custom code
// to emulate missed in Zend Framework ecosystem
// "Zend_Controller_Request_Cli" that can be found as proposal here:
// http://framework.zend.com/wiki/display/ZFPROP/Zend_Controller_Request_Cli
//
// I like the idea to define request params separated by slash "/"
// for ex. "module/controller/action/param1/param2/param3/.."
//
// NOTE: according to the current implementation param1,param2,param3,... are omited
//    only module/controller/action are used
//
// TODO: allow to omit "module", "action" params
//      and set them to "default" and "index" accordantly
//
// so lets split the params we've received from the CLI
// and pass them to the reqquest object
// NOTE: I think this functionality should be moved to the routing
$params = array_reverse(explode('/', $getopt->getOption('a')));
$module = array_pop($params);
$controller = array_pop($params);
$action = array_pop($params);
$request = new Zend_Controller_Request_Simple ($action, $controller, $module);

// set front controller options to make everything operational from CLI
$front->setRequest($request)
   ->setResponse(new Zend_Controller_Response_Cli())
   ->setRouter(new Custom_Controller_Router_Cli())
   ->throwExceptions(true);

// lets bootstrap our application and enjoy!
$application->bootstrap()
   ->run();

*/


/* metodo 2
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
*/
