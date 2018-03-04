<?php
require_once('autoload.php');

use \svg\Graph;

$data = [
    'canvas_width' => 300,
    'canvas_height' => 300,
    'func' => function ($x) {
        return 100 * sin(8 * $x);
    },
    'brush_color' => 'red',
    'brush_width' => 0.1,
    'step' => 1
];

$graph = new Graph($data);
ob_start();
$graph->render_graph();
$svgGraph = ob_get_clean();

file_put_contents('test.html', $svgGraph);
