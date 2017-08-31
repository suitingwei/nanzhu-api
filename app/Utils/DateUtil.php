<?php
namespace App\Utils;

use Illuminate\Pagination\AbstractPaginator;

/**
 * 这个工具类最重要的功能类似StringUtil里的groupByFirstChar,
 * 主要用于对一个有日期的数组进行分组如:
 * [
 *   [ date => 11-25],
 *   [ date => 11-26],
 *   [ date => 11-26],
 *   [ date => 11-26],
 *   [ date => 11-27],
 * ]
 *
 * 变形完毕之后应该返回:
 * [
 *  [ date => 11-25,
 *    data => [ xxx ]
 *  ],
 *
 *  [ date => 11-26,
 *    data => [ xxx,
 *              yyy,
 *              zzz
 *          ]
 *  ],
 *
 *  [ date => 11-27,
 *    data => [ xxx ]
 *  ],
 *
 *
 *
 * ]
 * Class DateUtil
 * @package App\Utils
 */
class DateUtil
{

    /**
     * 将输入的items按照日期分组
     *
     * @param          $items
     * @param callable $formatter
     *
     * @return array
     * @internal param string $dateKey
     *
     */
    public static function groupByDate($items, callable $formatter = null)
    {
        if ($items instanceof AbstractPaginator) {
            $items = $items->items();
        }

        $results = [];
        foreach ($items as $key => $item) {
            $date = $item->created_at->toDateString();

            //如果提供了回调函数,对数据进行回调处理
            $item = $formatter ? $formatter($item) : $item;

            //如果这个日期的数据已经存在了,直接插入这一天的数据里
            if (($index = array_search($date, array_column($results, 'date'))) !== false) {
                $results[$index]['data'][] = $item;
                continue;
            }

            //如果这个日期的数据还不存在,直接插入数据,生成固定的格式
            $results [] = ['date' => $date, 'data' => [$item]];
        }

        return $results;
    }
}
