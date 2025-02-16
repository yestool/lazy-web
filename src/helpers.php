<?php

if (!function_exists('session')) {
  function session($key = null, $default = null)
  {
      $session = app()->get(SessionInterface::class);
      
      if (is_null($key)) {
          return $session;
      }
      
      if (is_array($key)) {
          foreach ($key as $k => $v) {
              $session->set($k, $v);
          }
          return null;
      }
      
      return $session->get($key, $default);
  }
}