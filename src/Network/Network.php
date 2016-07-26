<?php

namespace Work\Network;

class Network
{
    /**
     * Fetch the current SSID the laptop is connected to.
     *
     * @return string
     */
    public static function ssid()
    {
        return exec("/System/Library/PrivateFrameworks/Apple80211.framework/Versions/Current/Resources/airport -I | awk '/ SSID/ {print substr($0, index($0, $2))}'");
    }
}
