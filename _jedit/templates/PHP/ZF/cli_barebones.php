
/* USE IN CLI_BAREBONES.PHP !!! */
    class ZF {

        // inizializza un l'environment Zend
        static function envBootstrap() {
            // Define path to application directory
            defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
            define('LIBRARY_ROOT', realpath(dirname(__FILE__) . '/../library'));

            set_include_path(implode(PATH_SEPARATOR, [LIBRARY_ROOT, get_include_path()]));
            $configSection = 'general';
            defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $configSection));
            require_once 'Zend/Application.php';
            $application = new \Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configuration/config.ini');
            // questo serve  aforzare env=dev
            $_SERVER['HTTP_HOST'] = 'localhost';
            require_once 'Zend/Loader/Autoloader.php';
            $loader = \Zend_Loader_Autoloader::getInstance();
            // $loader->registerNamespace('DMS');
            // $loader->registerNamespace('AS400');
            require_once APPLICATION_PATH . '/bootstrap.php';
            $bootstrap = new \Bootstrap($configSection);

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
/* USE IN CLI_BAREBONES.PHP !!! */
