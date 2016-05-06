<?php
/*

USE IN CLI_BAREBONES.PHP !!!

*/


function is($val, $expected_val, $description = '') {
    $pass = ($val == $expected_val);
    CLITest::ok($pass, $description);
    if (!$pass) {
        CLITest::diag("         got: '$val'");
        CLITest::diag("    expected: '$expected_val'");
    }
    return $pass;
}
function ok($test, $description = '') {
    return CLITest::ok($test, $description, $data = null);
}
class CLITest {
    static $errc = 0;
    public static function ok($test, $label, $data = null) {
        if ($test == false) {
            echo CLI::sprintc("ERROR $label: $test", 'red')."\n\n";
            if (!empty($data)) {
                echo  var_export($data, 1) ;
            }
            self::$errc++;
        } else {
            echo CLI::sprintc("OK $label", 'green')."\n\n";
        }
    }
    public static function diag($l, $data = '') {
        if (!empty($data)) {
            echo CLI::sprintc( $l );
        } else {
            echo CLI::sprintc( $l.':'.var_export($data, 1) );
        }
        echo "\n\n";
    }
}

// cli utils
class CLI {
    // stampa stringa colorata
    public static function colored($str, $foreground_color = '', $background_color = '') {
        // ForeGround
        static $a_fg = [
        'black' => '0;30',
        'red' => '0;31',
        'green' => '0;32',
        'brown' => '0;33',
        'blue' => '0;34',
        'purple' => '0;35',
        'cyan' => '0;36',
        'white' => '0;37',
        // Bold
        'bblack' => '1;30',
        'bred' => '1;31',
        'bgreen' => '1;32',
        'byellow' => '1;33',
        'bblue' => '1;34',
        'bpurple' => '1;35',
        'bcyan' => '1;36',
        'bwhite' => '1;37',
        ];
        // background
        static $a_bg = [
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'light_gray' => '47',
        ];
        $str_result = '';
        // FG color
        if (isset( $a_fg[$foreground_color])) {
            $s .= sprintf("\e[%sm", $a_fg[$foreground_color] );
        }
        // BG color
        if (isset( $a_bg[$background_color])) {
            $str_result .= sprintf("\033[%sm",  $a_bg[$background_color] );
        }
        $str_result .= $str . "\033[0m";
        return $str_result;
    }
}



/*
//$stdin_txt = stream_get_contents(STDIN);
foreach(CLI::$a_bg as $bg_k => $bg_v) {
    echo CLI::sprintc('test '.$bg_k, null, $bg_k)."\n";
}
foreach(CLI::$a_fg as $k => $v) {
    echo CLI::sprintc('test '.$k, $k)."\n";
}

is(1, 1);
is(1, 2);
*/


/* USE IN CLI_BAREBONES.PHP !!! */




