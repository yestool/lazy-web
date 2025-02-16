<?php

namespace App\Utils;

/**
 * 
 * // 测试各种日志级别
 * $logger->info("这是一条信息");
 * $logger->error("发生了一个错误");
 * $logger->debug("调试信息");
 * 
 * // 使用上下文
 * $logger->warning("用户 {user} 尝试访问 {page}", [
 *     'user' => 'admin',
 *     'page' => '/dashboard'
 * ]);
 * 
 * 
 */

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class ConsoleLogger implements LoggerInterface
{
    // 定义日志级别对应的颜色
    private const COLORS = [
        LogLevel::EMERGENCY => "\033[41;37m", // 红底白字
        LogLevel::ALERT     => "\033[41;37m",
        LogLevel::CRITICAL  => "\033[41;37m",
        LogLevel::ERROR     => "\033[31m",     // 红色
        LogLevel::WARNING   => "\033[33m",     // 黄色
        LogLevel::NOTICE    => "\033[32m",     // 绿色
        LogLevel::INFO      => "\033[36m",     // 青色
        LogLevel::DEBUG     => "\033[34m"      // 蓝色
    ];
    
    private const RESET_COLOR = "\033[0m";
    
    // 日志输出流
    private $stream;
    
    public function __construct()
    {
        // 使用 php://output 作为默认输出流
        $this->stream = fopen('php://stdout', 'w');
    }
    
    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        // 获取时间戳
        $timestamp = date('Y-m-d H:i:s');
        
        // 处理上下文变量替换
        $message = $this->interpolate($message, $context);
        
        // 获取颜色代码
        $color = self::COLORS[$level] ?? '';
        
        // 格式化输出
        $output = sprintf(
            "%s[%s] %s: %s%s\n",
            $color,
            $timestamp,
            strtoupper($level),
            $message,
            self::RESET_COLOR
        );
        
        // 输出到流
        fwrite($this->stream, $output);
    }
    
    private function interpolate($message, array $context = []): string
    {
        // 构建替换数组
        $replace = [];
        foreach ($context as $key => $val) {
            if (is_string($val) || method_exists($val, '__toString')) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        
        // 替换消息中的占位符
        return strtr($message, $replace);
    }
    
    public function __destruct()
    {
        // 关闭输出流
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }
}