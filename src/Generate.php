<?php

/**
 * 
 */

namespace Zaptrance\Payment;

class Generate
{

	private static function recursive_permutations($items,$perms = array( ))
    {
        static $list;
        if (empty($items)) {
            $list[] = join(',', $perms);      
        } else {
            for ($i = count($items)-1;$i>=0;--$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                static::recursive_permutations($newitems, $newperms);
            };
            return $list;
        }
    }

    public static function my_hash($MI,$API,$SI,$AMT,$TI){
        $number = 1;
        $n = intval($MI[0])*512;
        for($i=0;$i<strlen($SI);$i++){

            $number = (intval($SI[$i])*$n)+$number;

        }
        $combo = str_replace(",","",static::recursive_permutations(range(0,4))[$number%124]);
        $args = func_get_args();
        $str = "";
        for($k = 0;$k<5;$k++){
            $str .= $args[$combo[$k]];
        }

        return hash('sha512',$str);
    }
	

}