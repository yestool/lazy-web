<?php

namespace App\MdExt;

use Parsedown;

class AlertExtension extends Parsedown
{

  public function __construct()
  {
    array_unshift($this->InlineTypes['['], 'SpecialCharacter');
  }

  protected function inlineSpecialCharacter($Excerpt)
  {
    if (preg_match('/\[alert(?P<attributes>.*?)\](?P<text>.*?)\[\/alert\]/s', $Excerpt['text'], $matches)) {
      $attributes = $this->parseAttributes($matches['attributes']);
      $text = $matches['text'];

      return [
        'extent' => strlen($matches[0]),
        'element' => [
          'name' => 'div',
          'text' => $text,
          'attributes' => $attributes,
        ],
      ];
    }
  }

  protected function parseAttributes($attributeString)
  {
    $attributes = [];
    $pairs = explode(' ', trim($attributeString));

    foreach ($pairs as $pair) {
      if (strpos($pair, '=') !== false) {
        list($key, $value) = explode('=', $pair, 2);
        $attributes[trim($key)] = trim($value, '"\'');
      }
    }

    return $attributes;
  }
}
