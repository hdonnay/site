<?php

define('PIECRUST_BENCHMARKS_ROOT_DIR', dirname(__DIR__) . '/src/test-websites/benchmarks/');
define('PIECRUST_BENCHMARKS_CACHE_DIR', dirname(__DIR__) . '/tmp/cache');

require_once 'global_setup.php';
require_once 'piecrust_setup.php';
require_once 'util.php';

require_once 'Benchmark/Timer.php';
require_once 'Benchmark/Iterate.php';


use PieCrust\Page\Page;
use PieCrust\Page\PageRenderer;
use PieCrust\PieCrust;


function init_app($cache)
{
    $pc = new PieCrust(array('cache' => $cache, 'root' => PIECRUST_BENCHMARKS_ROOT_DIR));
    $pc->setCacheDir(PIECRUST_BENCHMARKS_CACHE_DIR);
    $pc->getConfig();
    return $pc;
}

function run_query($pieCrust, $uri)
{
    $page = Page::createFromUri($pieCrust, $uri);
    $renderer = new PageRenderer($pieCrust);
    return $renderer->get($page, null, false);
}

function run_detailed_query($bench, $pieCrust, $uri)
{   
    $pieCrust->getConfig();
    $bench->setMarker('App config loaded');

    $page = Page::createFromUri($pieCrust, $uri);
    $bench->setMarker('Created page');
    
    $page->getConfig();
    $bench->setMarker('Loaded page config and contents');
    
    $renderer = new PageRenderer($pieCrust);
    $bench->setMarker('Created renderer');
    
    $page = $renderer->get($page, null, false);
    $bench->setMarker('Rendered page');
    
    return $page;
}

include 'header.php';

echo '<h2>Benchmarks</h2>';

$runCount = 100;
function filter_end_marker($value) { return preg_match('/^end_/', $value['name']); }
function map_diff_time($value) { return $value['diff']; }
function display_profiling_times($runCount, $prof)
{
    $diffValues = array_map('map_diff_time', array_filter($prof, 'filter_end_marker'));
    echo '<p>Ran '.$runCount.' times.</p>';
    echo '<p>Median time: <strong>'.(median($diffValues)*1000).'ms</strong></p>';
    echo '<p>Average time: <strong>'.(average($diffValues)*1000).'ms</strong></p>';
    echo '<p>Max time: <strong>'.(max($diffValues)*1000).'ms</strong></p>';
}

//
// App init benchmark.
//
echo '<h3>App Init Benchmark (non-caching config)</h3>';
ensure_cache(PIECRUST_BENCHMARKS_CACHE_DIR, true);
$bench = new Benchmark_Iterate();
$bench->start();
$bench->run($runCount, 'init_app', false);
$bench->stop();
display_profiling_times($runCount, $bench->getProfiling());

echo '<h3>App Init Benchmark (caching config)</h3>';
ensure_cache(PIECRUST_BENCHMARKS_CACHE_DIR, true);
$bench = new Benchmark_Iterate();
$bench->start();
$bench->run($runCount, 'init_app', true);
$bench->stop();
display_profiling_times($runCount, $bench->getProfiling());

//
// Page rendering benchmark.
//
echo '<h3>Page Rendering Benchmark</h3>';
ensure_cache(PIECRUST_BENCHMARKS_CACHE_DIR, true);
$bench = new Benchmark_Iterate();
$bench->start();
$pieCrust = init_app(true);
$bench->run($runCount, 'run_query', $pieCrust, '/empty');
$bench->stop();
display_profiling_times($runCount, $bench->getProfiling());

//
// Marked run (uncached, then cached).
//
echo '<h3>Timed Benchmark</h3>';

$pieCrust = init_app(true);

echo '<h4>Uncached</h4>';
ensure_cache(PIECRUST_BENCHMARKS_CACHE_DIR, true);
$bench = new Benchmark_Timer();
$bench->start();
run_detailed_query($bench, $pieCrust, '/empty');
$bench->stop();
$bench->display();

echo '<h4>Cached</h4>';
$bench = new Benchmark_Timer();
$bench->start();
run_detailed_query($bench, $pieCrust, '/empty');
$bench->stop();
$bench->display();

include 'footer.php';
