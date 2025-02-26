<?php

namespace App\Utils;

use DateTime;
use DateTimeZone;

/**
 * 通用类
 */
class CommonUtils {
    

    /**
     * date '2025-02-26T10:35' to '2025-02-26 10:35:00'
     *
     * @param string $dateString 用户输入日期字符串
     * @param string $format 格式化字符串
     * @return string 返回
     */
    public static function DateFormat($dateString, $format = 'Y-m-d H:i:s') {
      $dateTime = new DateTime($dateString);
      $formattedDate = $dateTime->format($format);
      return $formattedDate;
    }

    public static function DateNow($format = 'Y-m-d H:i:s') {
      $dateTimeZone = new DateTimeZone('Asia/Shanghai');
      $dateTime = new DateTime('now', $dateTimeZone);
      $formattedDate = $dateTime->format($format);
      return $formattedDate;
    }
}
