<?php

namespace App\Utils;



class ConsoleLogger {

    // ANSI 颜色代码
    const COLOR_RESET   = "\033[0m";
    const COLOR_RED     = "\033[31m";
    const COLOR_GREEN   = "\033[32m";
    const COLOR_YELLOW  = "\033[33m";
    const COLOR_BLUE    = "\033[34m";
    const COLOR_MAGENTA = "\033[35m";
    const COLOR_CYAN    = "\033[36m";


    // 单例模式（可选），确保全局只有一个实例
    private static $instance = null;

    // 私有构造函数，防止直接实例化（如果使用单例模式）
    private function __construct() {}

    // 获取单例实例（可选）
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // 输出信息到控制台的方法
    public function log($message, $color = self::COLOR_RESET) {
        $timestamp = date('Y-m-d H:i:s');
        // 检查是否在 CLI 环境下运行
        if (PHP_SAPI === 'cli') {
            // 将消息输出到控制台，添加换行符
            echo $color . "[$timestamp]: [Console] " . $message . self::COLOR_RESET . PHP_EOL;
        } else {
            // 如果不是 CLI 环境，可以选择写入文件或忽略
            file_put_contents('php://stdout', $color . "[$timestamp] - [Console] " . $message . self::COLOR_RESET  . PHP_EOL);
        }
    }

    // 支持传入对象或数组并格式化输出
    public function dump($data, $color = self::COLOR_RESET) {
      $timestamp = date('Y-m-d H:i:s');
        if (PHP_SAPI === 'cli') {
            echo "[$timestamp]: [Console Dump] " . print_r($data, true) . self::COLOR_RESET . PHP_EOL;
        } else {
            file_put_contents('php://stdout', $color . "[$timestamp] - [Console Dump] " . print_r($data, true) . self::COLOR_RESET . PHP_EOL);
        }
    }

    public function info($message) {
      if ($message instanceof String){
        $this->log($message, self::COLOR_GREEN);
      }else{
        $this->dump($message, self::COLOR_GREEN);
      }
    }

    public function warning($message) {
      if ($message instanceof String){
        $this->log($message, self::COLOR_YELLOW);
      }else{
        $this->dump($message, self::COLOR_YELLOW);
      }
    }

    public function error($message) {
      if ($message instanceof String){
        $this->log($message, self::COLOR_RED);
      } else {
        $this->dump($message, self::COLOR_RED);
      }
    }



}