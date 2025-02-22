<?php

namespace App\Services;

use Odan\Session\SessionInterface;

class SessionManager
{
    private $session;
    
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    // 设置session值
    public function set(string $key, $value): void
    {
        $this->session->set($key, $value);
    }
    
    // 获取session值
    public function get(string $key, $default = null)
    {
        return $this->session->get($key, $default);
    }
    
    // 检查session键是否存在
    public function has(string $key): bool
    {
        return $this->session->has($key);
    }
    
    // 删除session值
    public function delete(string $key): void
    {
        $this->session->delete($key);
    }
    
    // 清除所有session
    public function clear(): void
    {
        $this->session->clear();
    }
    
    // 获取所有session数据
    public function getAll(): array
    {
        return $this->session->all();
    }
    
    // 获取flash数据
    public function getFlash()
    {
        return $this->session->getFlash();
    }
    
}