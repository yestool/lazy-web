<?php

namespace App\MdExt;

use Parsedown;


class CombinedExtensions extends Parsedown
{
  private $extensions = [];
  private $tocExtension;

  public function __construct()
  {
    $this->extensions = [
      ['instance' => new ButtonExtension(), 'priority' => 30],
      ['instance' => new AlertExtension(), 'priority' => 20],
      ['instance' => $this->tocExtension = new TocExtension(), 'priority' => 10]
    ];
    // 按优先级从高到低排序
    usort($this->extensions, fn($a, $b) => $b['priority'] <=> $a['priority']);
    array_unshift($this->InlineTypes['['], 'SpecialCharacter');
  }

  // 添加扩展的方法（可选）
  public function addExtension(Parsedown $extension, int $priority = 0)
  {
    $this->extensions[] = ['instance' => $extension, 'priority' => $priority];
    usort($this->extensions, fn($a, $b) => $b['priority'] <=> $a['priority']);
  }

  // 处理 inline 扩展
  protected function inlineSpecialCharacter($Excerpt)
  {
    foreach ($this->extensions as $ext) {
      $result = $ext['instance']->inlineSpecialCharacter($Excerpt);
      if ($result !== null) {
        return $result;
      }
    }
    return parent::inlineSpecialCharacter($Excerpt);
  }

  protected function blockHeader($Line)
  {
    $block = null;
    foreach ($this->extensions as $ext) {
      $result = $ext['instance']->blockHeader($Line);
      if ($result !== null) {
        $block = $result; // 保留最后一个非 null 结果
      }
    }
    return $block ?: parent::blockHeader($Line);
  }

  public function getToc()
  {
    return $this->tocExtension->getToc();
  }

  public function text($text)
  {
    $this->tocExtension->resetToc(); // 重置 TOC 数据
    // 第一次解析：收集所有标题
    $html = parent::text($text);
    // 替换 [TOC] 为生成的目录
    $tocHtml = '<nav class="toc">' . $this->tocExtension->generateTocHtml() . '</nav>';
    $html = preg_replace('/\[TOC\]/', $tocHtml, $html);

    return $html;
  }
}
