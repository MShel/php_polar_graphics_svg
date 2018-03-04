<?php
namespace svg;

class Graph
{
    protected $dimensions;
    protected $func;
    protected $x_dots;
    protected $y_dots;
    protected $brushWidth;
    protected $brushColor;
    protected $step;
    protected $c_x;
    protected $c_y;
    public $m_x;
    public $m_y;

    public function __construct(array $data)
    {
        $this->dimensions = [$data['canvas_width'], $data['canvas_height']];
        $this->func = $data['func'];
        $this->brushWidth = $data['brush_width'];
        $this->brushColor = $data['brush_color'];
        $this->m_x = (int)$data['canvas_height'] / 2;
        $this->m_y = (int)$data['canvas_width'] / 2;
        $this->step = $data['step'];
        $this->c_x = $this->m_x - 10;
        $this->c_y = $this->c_y - 10;
    }


    //drawing axes function
    public function draw_axes($dim)
    {


        $y_m = 10;
        $x_m = $dim[0] - 10;

        $x_axe = ("\n\r" . "<line x1='0' y1='$this->m_y' x2='$this->m_x' y2='$this->m_y' style='stroke:black;stroke-width:0.2'/>");
        $zero_point = ("<text x='$this->c_x' y='$this->c_y' fill='black' style='font-size:5px'>0</text>");
        $x_name = ("<text x='$x_m' y='$this->c_y' fill='black' style='font-size:5px'>x</text>");
        $y_name = ("<text x='$this->c_x' y='$y_m ' fill='black' style='font-size:5px'>y</text>");

        $x_axe .= ("\n\r" . "<line x1='$this->m_x' y1='$this->m_y' x2='$dim[0]' y2='$this->m_y' style='stroke:black;stroke-width:0.2'/>");
        $y_axe = ("<line x1='$this->m_x' y1='0' x2='$this->m_x' y2='$dim[1]' style='stroke:black;stroke-width:0.2'/>");
        $axes_string = implode("\n\r", array($x_axe, $y_axe, $zero_point, $x_name, $y_name));

        //returning fully prepared axes
        return ($axes_string);
    }

    public function draw_func_polar($func, $step)
    {
        $vertexCollection = [];
        $i = 0;
        $y = 0;
        $x = 0;

        //TODO this whole magic $i thing should go.
        while ($x < ($this->dimensions[0]) && $y < ($this->dimensions[1]) && ($i < 2000)) {
            $x = deg2rad($i);
            $r = $func($x);

            $y = ($r * sin($x)) + $this->m_y;
            $x = ($r * cos($x)) + $this->m_x;

            if ($i != 0) {
                $vertexCollection[] = $x . "," . $y . " ";
            }
            $i += $step;
        }
        $gluedVertexes = implode("", $vertexCollection);
        $graph = PHP_EOL . "<polyline points='{$gluedVertexes}' style='fill:white;stroke:{$this->brushColor};stroke-width:{$this->brushWidth}' />";
        $graph .= PHP_EOL . "<text x='10' y='30 ' fill='black' style='font-size:5px'>dimensions:y: {$this->dimensions[1]} x:{$this->dimensions[0]}</text>";

        return $graph;
    }


    //rendering graph
    public function render_graph()
    {
        echo('<svg xmlns="http://www.w3.org/2000/svg" version="1.1">');
        echo(self:: draw_func_polar($this->func, $this->step));
        echo(self:: draw_axes($this->dimensions));
        echo("\n" . '</svg>');
    }
}
    

    

    

 
    
