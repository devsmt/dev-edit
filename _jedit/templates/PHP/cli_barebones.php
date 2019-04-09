// # !/usr/bin/env php
<?php
/*
scopo del programma
*/
declare(strict_types=1);

namespace {

    //----------------------------------------------------------------------------
    //  subroutines and reusable stuff
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

        public static function bootstrap() {
            $time_limit_sec = 8 * 60 * 60; // 8h
            set_time_limit($time_limit_sec);
            ini_set('memory_limit', '521M');
            date_default_timezone_set('Europe/Rome');
            mb_internal_encoding('UTF-8');

            error_reporting(E_ALL); // Report all PHP errors
            ini_set('display_errors', 'stderr');
            ini_set('html_errors', '0');

            ini_set('memory_limit', -1);
            ini_set('apc.enable_cli', 1);// attenione, apc non funziona in CLI, funziona solo quando richiamto da web
            ini_set('apc.enabled', 1);

            define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/..'));
            //set_include_path(implode(PATH_SEPARATOR, [LIBRARY_ROOT, get_include_path()]));

            $config_env = 'dev';// 'test' 'prod'
            defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $config_env));

            // test software version
            if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 70001) {
                die("this program needs php 7 \n");
            }
            if (PHP_SAPI != 'cli') {
                die("this program needs php CLI \n");
            }

            // tutti i warning vengono presentati come eccezioni
            set_error_handler(function ($errno, $errstr, $errfile, $errline, array $errcontext) {
                    // error was suppressed with the @-operator
                    if (0 === error_reporting()) {
                        return false;
                    }
                    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
            }, E_WARNING);
        }
        /*
        //----------------------------------------------------------------------------
        // log procedure apposita per programmi CLI
        //----------------------------------------------------------------------------
        public static function flog($isOK, $message, array $errors=[]) {
            $log_msg =  sprintf('%s %s msg:"%s" errors:%s params:%s '.PHP_EOL,
                date('Y-m-d H:i:s'),
                $isOK ? 'OK' : 'KO',
                $message,
                json_encode($errors),
                $request =  implode(' ', array_slice($GLOBALS['argv'], $pos=1 ) )
                );
            $pgm_name = str_replace('.php','', basename( $GLOBALS['argv'][0] ) );
            $_log_path = sprintf('%s/../var/logs', APPLICATION_PATH );
            $_log_file = sprintf('%s_%s.log', $pgm_name, date('my') );

            $log_path = realpath( $_log_path );
            if( empty($log_path) ) {
                echo "log_path non valido $_log_path \n";
                return;
            } else {
                $log_path = "$log_path/$_log_file";
                return file_put_contents($log_path, $log_msg, (FILE_APPEND | LOCK_EX) );
            }
        }
        */

    }

    // funzione: base controller
    class CliController {
        // Exit with nonzero status codes if and only if the program terminated with errors.
        public $statusCode=0;// 0 => no errors
        // dati action e parametri in input esegue il corrispondente metodo e ritorna lo status
        protected function call($action, array $params=[]):int {
            $method = self::resolveAction($action);
            if (method_exists($this, $method)) {
                println( $this->$method() );
            } else {
                println( "unimplemented method:$method " );
            }
            // Exit with nonzero status codes if and only if the program terminated with errors.
            return $this->statusCode;
        }

        // logica di risoluzione della action
        public static function resolveAction($action) {
            $action = strtolower($action);
            if (empty($action)) {
                return '';
            }
            return $action.'Action';
        }
    }

    // Write to stdout for useful information, stderr for warnings and errors
    function println(string $msg) { echo $msg . "\n";}
    function printErr(string $msg) { fwrite(STDERR, $msg . "\n" );  }

    // test util
    function ok($res, $expected, string $label) {
        if ($res === $expected) {
            echo colored("ok $label \n", 'bgreen');
        } else {
            echo colored(sprintf("ERROR(%s)  got=expected  %s==%s \n",
                $label, json_encode($res), json_encode($expected)
            ), 'bred');
        }
    }

    // stampa stringa colorata
    function colored(string $str, string $foreground_color = '', string $background_color = ''):string {
        $a_fg = [ 'bred' => '1;31', 'bgreen' => '1;32' ];
        return sprintf( "\033[%sm%s\033[0m", $a_fg[$foreground_color], $str );
    }

    // configurazioni del programma
    // class Config {
    //     public static $config = [
    //         'DEV' => [
    //         ],
    //     ];
    //     public static function get($key, $env=null) {
    //         return self::$config[ENV][$key];
    //     }
    // }

    //----------------------------------------------------------------------------
    //  controller
    //----------------------------------------------------------------------------
    class Main extends CliController {
        public function run():int {
            // init params
            define('DEBUG', CLI::hasFlag('debug'));
            $action = $_SERVER['argv'][1] ?? 'test';
            return $this->call($action);
        }
        //----------------------------------------------------------------------------
        //  actions
        //----------------------------------------------------------------------------
        function usageAction():string {
            return "
            uso:
            {$_SERVER['argv'][0]} [action] [--debug]
            uso del programma
            \n\n";
        }
        function testAction():string {
            ok(self::resolveAction('login'), 'loginAction', 'test resolve');//test resolver
            return '';
        }
    }

    //----------------------------------------------------------------------------
    //  main
    //----------------------------------------------------------------------------
    CLI::bootstrap();
    try {
        // CLI::flog($isOK=true, $message='begin procedure');
        $c = new Main();
        exit( $statusCode = $c->run()  );
    } catch (Exception $e) {
        $msg = sprintf('Exception: %s line:%s file:%s trace:%s', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
        printErr($msg);
        exit( $c->statusCode  );
    }
}




