<?php

namespace App\Utils;

/**
 * 密码工具类
 */
class PasswordUtils {
    /**
     * 生成密码哈希值
     *
     * @param string $password 用户输入的原始密码
     * @return string 返回哈希后的密码
     * @throws \Exception 如果哈希生成失败
     */
    public static function generatePasswordHash($password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if ($hash === false) {
            throw new \Exception("密码哈希生成失败");
        }

        return $hash;
    }

    /**
     * 验证密码
     *
     * @param string $password 用户输入的原始密码
     * @param string $hash 数据库中存储的哈希值
     * @return bool 如果密码匹配返回 true，否则返回 false
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
