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
You can then proceed to use ZF resources just as you would in an MVC application:

$db = $application->getBootstrap()->getResource('db');

$row = $db->fetchRow('SELECT * FROM something');
// If you wish to add configurable arguments to your CLI script, take a look at Zend_Console_Getopt

// If you find that you have common code that you also call in MVC applications,
// look at wrapping it up in an object and calling that object's methods from both
// the MVC and the command line applications. This is general good practice.


/*
usare action

Bootstrap.php

protected function _initRouter()
{
    if( PHP_SAPI == 'cli' )
    {
        $this->bootstrap( 'FrontController' );
        $front = $this->getResource( 'FrontController' );
        $front->setParam('disableOutputBuffering', true);
        $front->setRouter( new Application_Router_Cli() );
        $front->setRequest( new Zend_Controller_Request_Simple() );
    }
}
Init error would probably barf as written above, the error handler is probably not yet instantiated unless you've changed the default config.

protected function _initError ()
{
    $this->bootstrap( 'FrontController' );
    $front = $this->getResource( 'FrontController' );
    $front->registerPlugin( new Zend_Controller_Plugin_ErrorHandler() );
    $error = $front->getPlugin ('Zend_Controller_Plugin_ErrorHandler');
    $error->setErrorHandlerController('index');

    if (PHP_SAPI == 'cli')
    {
        $error->setErrorHandlerController ('error');
        $error->setErrorHandlerAction ('cli');
    }
}

// You probably, also, want to munge more than one parameter from the command line,
// here's a basic example:

class Application_Router_Cli extends Zend_Controller_Router_Abstract
{
    public function route (Zend_Controller_Request_Abstract $dispatcher)
    {
        $getopt     = new Zend_Console_Getopt (array ());
        $arguments  = $getopt->getRemainingArgs();

        if ($arguments)
        {
            $command = array_shift( $arguments );
            $action  = array_shift( $arguments );
            if(!preg_match ('~\W~', $command) )
            {
                $dispatcher->setControllerName( $command );
                $dispatcher->setActionName( $action );
                $dispatcher->setParams( $arguments );
                return $dispatcher;
            }

            echo "Invalid command.\n", exit;

        }

        echo "No command given.\n", exit;
    }


    public function assemble ($userParams, $name = null, $reset = false, $encode = true)
    {
        echo "Not implemented\n", exit;
    }
}


// Lastly, in your controller, the action that you invoke make use of the params
// that were orphaned by the removal of the controller and action by the CLI router:

public function echoAction()
{
    // disable rendering as required
    $database_name     = $this->getRequest()->getParam(0);
    $udata             = array();

    if( ($udata = $this->getRequest()->getParam( 1 )) )
        $udata         = explode( ",", $udata );

    echo $database_name;
    var_dump( $udata );
}

// You could then invoke your CLI command with:
php index.php Controller Action

*/

/* metodo 2
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




