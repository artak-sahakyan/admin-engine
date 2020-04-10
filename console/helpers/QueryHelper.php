<?php

namespace console\helpers;

use yii\db\Query;

class QueryHelper
{
    /**
     * Get db rows use minimal memory
     *
     * @param mixed $where
     * @param null $limit
     * @return \Generator
     */
    public static function getRows(Query $query, $limit = null)
    {
        $perPage = 100;
        $page = 0;
        $i = 0;

        $query->limit($perPage);

        while (
            $rowsPart = $query->offset($perPage * $page)->all()
        ) {

            foreach ($rowsPart as $row) {

                if (isset($limit) && $i >= $limit) {
                    break 2;
                }

                yield $row;

                $i++;
            }

            $page++;
        }
    }


}