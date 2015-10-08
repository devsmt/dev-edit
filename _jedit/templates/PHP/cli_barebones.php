#!/usr/bin/env php
<?php
/*
scopo del programma
 */
namespace {
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

    function println($msg) {echo $msg . "\n";}

    class ENV {
        static function envBootstrap() {
            $time_limit_sec = 8 * 60 * 60; // 8h
            set_time_limit($time_limit_sec);
            ini_set('memory_limit', '521M');
            date_default_timezone_set('Europe/Rome');
            mb_internal_encoding('UTF-8');
        }
    }

    //----------------------------------------------------------------------------
    //  controller
    //----------------------------------------------------------------------------
    //main controller
    class Main {
        public function run() {
            $action = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : 'test';
            $action = strtoupper($action);
            switch ($action) {
            case 'X':
                die(' ... ');
                break;
            //---------------------------------------------------
            //  dev methods
            //---------------------------------------------------
            case 'TEST':
                die($this->actionTest());
                break;

            default:
                die($this->actionUsage());
                break;
            }
        }

        //----------------------------------------------------------------------------
        //  actions
        //----------------------------------------------------------------------------
        function actionUsage() {
            return "
            uso:
            {$_SERVER['argv'][0]} [action] [--go] [--test]
            uso del programma
            \n\n";
        }
        function actionTest() {
            echo "run tests";
        }
    }

    //----------------------------------------------------------------------------
    //  main
    //----------------------------------------------------------------------------

    define('DEBUG', CLI::hasFlag('debug'));
    ENV::envBootstrap();
    try {
        $c = new Main();
        $c->run();
    } catch (Exception $e) {
        $msg = sprintf('Exception: %s line:%s file:%s %s', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
        println($msg);
    }
}
