# PolarGraphicsSvg

This class can render a math formula as a SVG vector polyline chart.

See example below:

```$php
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
$graph->render_graph();

```

