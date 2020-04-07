<?php


namespace LuTauch\App\Model;

use stdClass;

class OptionsModel
{

    /**
     * Creates sql query by iterating values sent from the user in parameter and adding key word AND/OR between them.
     * Each part of the query represents one option group (e.g. packet size, delivery type, etc.). Parts are connected by AND.
     * The inner values in each part represents specific options (e.g. packet_s, weekend delivery, etc.) and those are connected by OR.
     * @param stdClass $values
     * @return string
     */
    public function getOptionsGroupSQLString($values)
    {
        $innerSql = '';

        //iterating over the option groups
        foreach ($values as $optionGroup) {
            //skipping an empty option group
            if (!$optionGroup) {
                continue;
            }

            $innerSql .= '(';

            $optionGroupCount = count($optionGroup);

            //iterating over the values from each option group
            foreach ($optionGroup as $innerIteratorKey => $item) {
                //setting value 1 for each
                $innerSql .= $item . ' = 1';

                //setting OR to the condition
                if ($innerIteratorKey !== $optionGroupCount - 1) {
                    $innerSql .= ' OR ';
                }
            }

            $innerSql .= ') AND ';
        }

        if (strlen($innerSql) > 4)
        {
            $innerSql = substr($innerSql, 0, -5);
        }

        return $innerSql;
    }


    public function getCategoryOfPacket($weight) {
        if ($weight < 20000) {
            return 'packet_s = 1';
        } else if (($weight >= 20000) && ($weight < 31500)){
            return 'packet_m = 1';
        } else if (($weight >= 31500) && ($weight < 50000)){
            return'packet_l = 1';
        } else {
            return 'packet_xl = 1';
        }
    }






}