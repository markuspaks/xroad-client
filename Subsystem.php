<?php

namespace XRoadClient;

class Subsystem
{

    public $xRoadInstance;

    public $memberClass;

    public $memberCode;

    public $subsystemCode;

    public function __construct($string)
    {
        $parts = explode('/', $string);

        $this->xRoadInstance = $parts[0];
        $this->memberClass = $parts[1];
        $this->memberCode = $parts[2];
        $this->subsystemCode = $parts[3];
    }

}