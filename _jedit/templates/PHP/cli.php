<?php
/*
utilities da aggiungere a un programma CLI
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
    // ForeGround colors
    static $a_fg = [
        'black' => '0;30',
        'dark_gray' => '1;30',
        'blue' => '0;34',
        'light_blue' => '1;34',
        'green' => '0;32',
        'light_green' => '1;32',
        'cyan' => '0;36',
        'light_cyan' => '1;36',
        'red' => '0;31',
        'light_red' => '1;31',
        'purple' => '0;35',
        'light_purple' => '1;35',
        'brown' => '0;33',
        'yellow' => '1;33',
        'light_gray' => '0;37',
        'white' => '1;37',
        // Bold
        'bblack' => '1;30' ,
        'bred' => '1;31'   ,
        'bgreen' => '1;32' ,
        'byellow' => '1;33',
        'bblue' => '1;34'  ,
        'bpurple' => '1;35',
        'bcyan' => '1;36'  ,
        'bwhite' => '1;37' ,
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



    // usa FG o BG
    public static function sprintc($str, $foreground_color = '',$background_color = '') {
        $s = '';
        // FG color
        if (isset(self::$a_fg[$foreground_color])) {
            $s.= "\e[" . self::$a_fg[$foreground_color].'m';
        }
        // BG color
        if (isset(self::$a_bg[$background_color])) {
            $s.= "\033[" . self::$a_bg[$background_color].'m';
        }
        $s .= $str . "\033[0m";
        return $s;
    }
    // stampa stringa colorata
    public static function printc($str, $foreground_color = 'green' ) {
        echo self::sprintc($str, $foreground_color )."\n";
    }
}

//$stdin_txt = stream_get_contents(STDIN);


/* USE IN CLI_BAREBONES.PHP !!! */

/*
foreach(CLI::$a_bg as $bg_k => $bg_v) {
    echo CLI::sprintc('test '.$bg_k, null, $bg_k)."\n";
}
foreach(CLI::$a_fg as $k => $v) {
    echo CLI::sprintc('test '.$k, $k)."\n";
}

is(1, 1);
is(1, 2);
*/

