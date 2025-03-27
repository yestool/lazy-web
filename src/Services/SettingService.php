<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    private Setting $setting;

    public function __construct(Setting $setting)
    {
      $this->setting = $setting;
    }


    public function getSettingByKey($key)
    {
      return $this->setting->getSettingByKey($key);
    }

    

    public function updateSetting($key, $data)
    {
      return $this->setting->updateSetting($key, $data);
    }

    
}