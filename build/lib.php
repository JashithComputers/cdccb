<?php
function cleanArrayNode(&$item1, $key)
{
        $item1 = getCleanText($item1);
}

function getCleanText($str)
{
        $output = "";
        $allowedChars = array(" ","\n","\t");
        for($i=0;$i<strlen($str);++$i){
                $ch = substr($str,$i,1);
                if(ctype_print($ch) || in_array($ch,$allowedChars)){
                        $output .= $ch;
                }
        }
        return $output;
}
