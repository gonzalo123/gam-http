<?php
//http://localhost:8082/tests/gam_http/demo.php
require_once('http.php');

class Timer
{
    private static $_start = null;

    static function start()
    {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        self::$_start = $mtime;
    }

    static function end()
    {
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        return ($endtime - self::$_start);
    }
}

Timer::start();
$out = Http::connect('localhost', 8082)
    ->silentMode()
    ->get('/tests/gam_http/sleep.php', array('sleep' => 3))
    ->post('/tests/gam_http/sleep.php', array('sleep' => 2))
    ->get('/tests/gam_http/sleep.php', array('sleep' => 2))
    ->post('/tests/gam_http/sleep.php', array('sleep' => 2))
    ->get('/tests/gam_http/sleep.php', array('sleep' => 2))
    ->post('/tests/gam_http/sleep.php', array('sleep' => 2))
    ->get('/tests/gam_http/sleep.php', array('sleep' => 1))
    ->run();
print_r($out);
echo '<p>This page was created in ' . Timer::end() . ' seconds.</p>';

Timer::start();

$out = Http::multiConnect()
    ->add(Http::connect('localhost', 8082)->get('/tests/gam_http/sleep.php', array('sleep' => 1)))
    ->add(Http::connect('localhost', 8082)->get('/tests/gam_http/sleep.php', array('sleep' => 2)))
    ->run();
print_r($out);
echo '<p>This page was created in ' . Timer::end() . ' seconds.</p>';

Timer::start();
echo Http::connect('localhost', 8082)
    ->doGet('/tests/gam_http/sleep.php', array('sleep' => 3));
echo '<p>This page was created in ' . Timer::end() . ' seconds.</p>';

Timer::start();    
echo Http::connect('localhost', 8082)
    ->doPost('/tests/gam_http/sleep.php', array('sleep' => 2));
echo '<p>This page was created in ' . Timer::end() . ' seconds.</p>';

Timer::start();
echo Http::connect('localhost', 8082)
    ->doDelete('/tests/gam_http/sleep.php', array('sleep' => 1));
echo '<p>This page was created in ' . Timer::end() . ' seconds.</p>';

Timer::start();    
try {
   echo Http::connect('localhost', 8082)
        ->doGet('/tests/gam_http/sleep.php', array('sleep' => 3));
} catch (Http_Exception $e) {
    switch ($e) {
        case Http_Exception::INTERNAL_ERROR:
            echo "Internal Error";
            break;
    }
}
echo '<p>This page was created in ' . Timer::end() . ' seconds.</p>';
