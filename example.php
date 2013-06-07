<?php
require_once('svg_graphics.php');
$Xcoord = array(10,20,300,200,25,47,55,90);
$Ycoord = array(20,60,80,100,120,140,11,160);
$dots   = array($Xcoord,$Ycoord);

define("pi",3.14159265358979323846264338327950288419716939937510);
$data  = array (
                  'dimens' => "300x300",
                  'func'   => '100*sin(8*x)', //instead of function here you can use array of dots ($dots in this example)
                  'step'   => pi,  //please use pi for polar coordinates
                  'brush_color'  => 'red',
                  'system'       => 'polar',   // you can use polar or cartesian
                  'brush_width'  => 0.2
               );
$graph = new svg_graph($data);
$graph->render_graph();
?>

 