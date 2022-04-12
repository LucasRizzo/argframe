<?php
// Cartesian product provided by https://gist.github.com/jwage/11193216

class Cartesian
{
    public static function build($set)
    {
        if (!$set) {
            return array(array());
        }
        $subset = array_shift($set);
        $cartesianSubset = self::build($set);
        $result = array();
        foreach ($subset as $value) {
            foreach ($cartesianSubset as $p) {
                array_unshift($p, $value);
                $result[] = $p;
            }
        }
        return $result;
    }
}
