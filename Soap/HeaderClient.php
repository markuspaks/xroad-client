<?php

namespace XRoadClient\Soap;

use SoapVar;
use XRoadClient\Subsystem;

class HeaderClient
{
    public $xRoadInstance;

    public $memberClass;

    public $memberCode;

    public $subsystemCode;

    public function __construct(Subsystem $subsystem)
    {
        $this->xRoadInstance = new SoapVar($subsystem->xRoadInstance, XSD_STRING, null, null, null, 'http://x-road.eu/xsd/identifiers');
        $this->memberClass = new SoapVar($subsystem->memberClass, XSD_STRING, null, null, null, 'http://x-road.eu/xsd/identifiers');
        $this->memberCode = new SoapVar($subsystem->memberCode, XSD_STRING, null, null, null, 'http://x-road.eu/xsd/identifiers');
        $this->subsystemCode = new SoapVar(
            $subsystem->subsystemCode,
            XSD_STRING,
            null,
            null,
            null,
            'http://x-road.eu/xsd/identifiers'
        );
    }
}
