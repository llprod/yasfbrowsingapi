<?php

namespace YaSfBrowsing;

Class Match
{
    public $threatType;
    public $platformType;
    public $threatEntryType;
    public $threat;
    public $cacheDuration;

    /**
     * Match constructor.
     * @param $threatType
     * @param $platformType
     * @param $threatEntryType
     * @param $threat
     * @param $cacheDuration
     */
    public function __construct($threatType, $platformType, $threatEntryType, $threat, $cacheDuration)
    {
        $this->threatType = $threatType;
        $this->platformType = $platformType;
        $this->threatEntryType = $threatEntryType;
        $this->threat = $threat;
        $this->cacheDuration = $cacheDuration;
    }
}
