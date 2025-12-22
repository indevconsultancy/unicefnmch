<?php
class Helper {
    function abc($a,$b)
    {
        return ($a+$b);
    }

    function fonts()
    {
        $fonts = [
                'Courier',
                'Arial',
                'Verdana',
                'Georgia',
                'Palatino Linotype',
                'Times New Roman',
                'Comic Sans MS',
                'Impact',
                'Lucida Sans Unicode',
                'Tahoma',
                'Lucida Console',
                'Courier New',
                'Monaco',
                'Monospace'
            ];
        return $fonts;
    }
}