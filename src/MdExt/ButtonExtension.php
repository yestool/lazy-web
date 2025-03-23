<?php

namespace App\MdExt;

use Parsedown;

class ButtonExtension extends Parsedown
{

  public function __construct()
  {
    array_unshift($this->InlineTypes['['], 'SpecialCharacter');
  }


  protected function inlineSpecialCharacter($Excerpt)
  {
    if (preg_match('/^\[button(?P<attributes>.*?)\](.*?)\[\/button\]/', $Excerpt['text'], $matches)) {
      $attributes = $this->parseAttributes($matches['attributes']);
      return [
        'extent' => strlen($matches[0]),
        'element' => [
          'name' => 'button',
          'attributes' => $attributes,
          'text' => $matches[2],
        ],
      ];
    }
    return parent::inlineSpecialCharacter($Excerpt);
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
