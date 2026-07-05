<?php

namespace App\Traits;

trait DetectsMobile
{
    /**
     * Detect if the current request is from a mobile device using User-Agent.
     *
     * @return bool
     */
    protected function isMobile(): bool
    {
        $userAgent = request()->userAgent() ?? '';
        
        // Comprehensive regex for mobile devices
        $mobileRegex = '/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos|iphone|ipad|ipod)/i';
        
        return preg_match($mobileRegex, $userAgent) === 1;
    }
}
