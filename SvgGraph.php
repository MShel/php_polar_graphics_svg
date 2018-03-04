<?php

class SvgGraph
{

    private $dimensions;
    private $func;
    private $func_prep;
    private $x_dots;
    private $y_dots;

    public $m_x;
    public $m_y;

    //getting user params for the graphic
    public function __construct($data)
    {
        $this->dimensions = explode("x", $data['dimens']);

        $this->func = $this->define_func($data['func']);

        $dim = $this->dimensions;
        $this->brush_width = $data['brush_width'];
        $this->brush_color = $data['brush_color'];
        $this->system = $data['system'];

        $middle_y = $dim[1] / 2;
        $middle_x = $dim[0] / 2;
        $this->m_x = $middle_x;
        $this->m_y = $middle_y;
        $this->step = $data['step'];
        $centr_x = $middle_x - 10;
        $centr_y = $middle_y + 10;
        $this->c_x = $centr_x;
        $this->c_y = $centr_y;

    }

    /*defining the function
    /* it can be function or array so we defining functyion or sending dots to class
    */
    public function define_func($func)
    {

        if (!is_array($func)) {
            $this->name_of_func = $func;
            $func = str_replace("x", "%f", $func);
            return ("$func");

        } else {
            $this->x_dots = $func[0];
            $this->y_dots = $func[1];

            return ("dots");
        }
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


    private function calc_string($mathString)
    {
        $cf_DoCalc = create_function("", "return (" . $mathString . ");");
        return $cf_DoCalc();
    }


    //drawing function
    public function draw_func($func, $step = 1)
    {
        $vertex = array();
        $i = 0;
        $y = 0;
        $df = $func;
        //fistly wr doing positive + side of graphic
        while (($x < ($this->dimensions[0])) && ($y < ($this->dimensions[1]))) {
            //$func ="";
            $func = sprintf($df, $i, $i, $i);
            $y = round(($this->calc_string($func)) + $this->m_y);
            $x = round($i) + $this->m_x;
            if ($i != 0) {
                $vertex[] = $x . "," . $y . " ";
            }
            $i = $i + $step;
        }
        $vertexs = implode("", (array_unique($vertex)));
        $graph = ("\n\n\n" . "<polyline points='{$vertexs}' style='fill:white;stroke:{$this->brush_color};stroke-width:{$this->brush_width}' />");

        $i = 0;
        $y = 0;
        $vertex = array();

        while (($i > -($this->m_x)) && ($y < ($this->dimensions[1]))) {
            $i = $i - $step;
            $func = sprintf($df, $i, $i, $i);
            $y = round(($this->calc_string($func)) + $this->m_y);
            $x = round($i) + $this->m_x;
            if ($y < $this->dimensions[1]) {
                $vertex[] = $x . "," . $y . " ";
            }
        }

        $vertexs = implode("", (array_unique($vertex)));
        $graph .= ("\n\n\n" . "<polyline points='{$vertexs}' style='fill:white;stroke:{$this->brush_color};stroke-width:{$this->brush_width}' />");
        $graph .= ("\n\n\n" . "<text x='10' y='10 ' fill='black' style='font-size:5px'>$this->func</text>");
        $graph .= ("\n\n\n" . "<text x='10' y='30 ' fill='black' style='font-size:5px'>dimensions:y: {$this->dimensions[1]} x:{$this->dimensions[0]}</text>");

        return $graph;
    }

    public function draw_func_polar($func, $step)
    {
        $vertex = array();
        $i = 0;
        $y = 0;
        $x = 0;
        $step = $step;
        $df = $func;


        while (($x < ($this->dimensions[0])) && ($y < ($this->dimensions[1])) && ($i < 2000)) {
            $x = deg2rad($i);
            $func = sprintf($df, $x, $x, $x, $x);

            $r = ($this->calc_string($func));
            //echo($r."<br>");

            $y = ($r * sin($x)) + $this->m_y;
            $x = ($r * cos($x)) + $this->m_x;

            if ($i != 0) {
                $vertex[] = $x . "," . $y . " ";
            }
            $i = $i + $step;
        }

        $vertexs = implode("", (array_unique($vertex)));
        $graph = "";
        $graph .= ("\n\n\n" . "<polyline points='{$vertexs}' style='fill:white;stroke:{$this->brush_color};stroke-width:{$this->brush_width}' />");
        $graph .= ("\n\n\n" . "<text x='10' y='10 ' fill='black' style='font-size:5px'>function: $this->name_of_func</text>");
        $graph .= ("\n\n\n" . "<text x='10' y='30 ' fill='black' style='font-size:5px'>dimensions:y: {$this->dimensions[1]} x:{$this->dimensions[0]}</text>");

        return $graph;
    }

    public function draw_polyline()
    {
        $graph = "";
        for ($i = 0; $i < count($this->x_dots); $i++) {
            $x = $this->x_dots[$i] + $this->m_x;
            $y = $this->y_dots[$i];

            if (($this->y_dots[$i] < $this->dimensions[1]) && ($x < $this->dimensions[0])) {
                $vertex[] = $x . "," . $y . " ";
            }
        }
        $vertexs = implode("", (array_unique($vertex)));
        $graph .= ("\n\n\n" . "<polyline points='{$vertexs}' style='fill:white;stroke:{$this->brush_color};stroke-width:{$this->brush_width}' />");
        $graph .= ("\n\n\n" . "<text x='10' y='10 ' fill='black' style='font-size:5px'>Grpaphic by dots</text>");
        $graph .= ("\n\n\n" . "<text x='10' y='30 ' fill='black' style='font-size:5px'>dimensions:y: {$this->dimensions[1]} x:{$this->dimensions[0]}</text>");

        //returning fully prepared graph
        return $graph;
    }

    //rendering graph
    public function render_graph()
    {
        echo('<svg xmlns="http://www.w3.org/2000/svg" version="1.1">');

        if (($this->func) == 'dots') {
            echo("test");
            echo(self:: draw_polyline());
        } else {
            if (($this->system) == 'cartesian') {
                echo(self:: draw_func($this->func, $this->step));
            } elseif (($this->system) == 'polar') {
                echo(self:: draw_func_polar($this->func, $this->step));
            }
        }

        echo(self:: draw_axes($this->dimensions));
        echo("\n" . '</svg>');
    }
}
    

    

    

 
    
