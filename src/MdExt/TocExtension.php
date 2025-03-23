<?php

namespace App\MdExt;

use Parsedown;

class TocExtension extends Parsedown
{
    public $toc = [];
    public $headerCount = 0;

    protected function blockHeader($Line)
    {
        $block = parent::blockHeader($Line);
        if ($block !== null) {
            $level = (int) substr($block['element']['name'], 1);
            $text = $block['element']['text'];
            $id = 'toc-' . (++$this->headerCount);
            
            $block['element']['attributes']['id'] = $id;
            
            $this->toc[] = [
                'level' => $level,
                'text' => $text,
                'id' => $id
            ];
        }
        return $block;
    }

    public function generateTocHtml()
    {
        if (empty($this->toc)) {
            return '<p>No table of contents available.</p>';
        }
        
        $html = '<ul>' . PHP_EOL;
        foreach ($this->toc as $item) {
            $indent = str_repeat('  ', $item['level'] - 1);
            $html .= $indent . sprintf('<li><a href="#%s">%s</a></li>', $item['id'], htmlspecialchars($item['text'])) . PHP_EOL;
        }
        $html .= '</ul>';
        return $html;
    }

    public function getToc()
    {
        return $this->toc;
    }

    public function resetToc()
    {
        $this->toc = [];
        $this->headerCount = 0;
    }
}