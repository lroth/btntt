<?php

namespace Btn\AppBundle\Util;

class Form {
    public static function getExpr($bindedData, $expr, $filters, $filterForm)
    {
        $result = array();

        foreach ((array)$filters as $key => $field) {
            if (isset($bindedData[$key])) {

                $func = 'get'.ucfirst($key);

                if (method_exists($filterForm, $func)) {
                    $res   = $filterForm->$func($bindedData[$key], $expr);
                    if ($res) {
                        $result[] = $res;
                    }
                }  else  {
                    $result[] = $expr->eq($field, $expr->literal($bindedData[$key]));
                }
            }
        }

        return $result;
    }
}