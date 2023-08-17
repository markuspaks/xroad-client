<?php

namespace XRoadClient\Soap;

use DependableSoapClient\DependableSoapClient;
use SoapHeader;
use SoapVar;
use XRoadClient\Subsystem;

class Client extends DependableSoapClient
{

    protected Subsystem $service;

    protected Subsystem $client;

    protected string $version;

    public function setClient(Subsystem $client): void
    {
        $this->client = $client;
    }

    public function setService(Subsystem $service): void
    {
        $this->service = $service;
    }

    /**
     * Call soap function as callable
     *
     * @param  string  $name
     * @param  mixed  $args
     * @return mixed
     */
    public function __call(string $name, $args): mixed
    {
        $this->version = array_shift($args);
        $args = [$args]; //This was done in soapCall, but caused issues with wsdl that uses parts
        array_unshift($args, $name);
        return call_user_func_array([$this, '__soapCall'], $args);
    }

    public function __doRequest($request, $location, $action, $version, $oneWay = 0): ?string
    {
        $request = str_replace(
            '<ns2:service><ns3:xRoadInstance>',
            '<ns2:service ns3:objectType="SERVICE"><ns3:xRoadInstance>',
            $request
        );
        $request = str_replace(
            '<ns2:client><ns3:xRoadInstance>',
            '<ns2:client ns3:objectType="SUBSYSTEM"><ns3:xRoadInstance>',
            $request
        );

        return parent::__doRequest(
            $request,
            $location,
            $action,
            $version,
            $oneWay
        );
    }

    public function __soapCall(
        string $name,
        array $args,
        ?array $options = null,
        $inputHeaders = null,
        &$outputHeaders = null
    ): mixed {
        $this->__setSoapHeaders(
            [
                new SoapHeader('http://x-road.eu/xsd/xroad.xsd', 'userId', 'EE11306955'),
                new SoapHeader('http://x-road.eu/xsd/xroad.xsd', 'protocolVersion', '4.0'),
                new SoapHeader('http://x-road.eu/xsd/xroad.xsd', 'id', 'dasda'),
                new SoapHeader(
                    'http://x-road.eu/xsd/xroad.xsd',
                    'service',
                    new SoapVar(
                        new HeaderService($this->service, $name, $this->version),
                        SOAP_ENC_OBJECT,
                        null,
                        null,
                        null,
                        'http://x-road.eu/xsd/xroad.xsd'
                    )
                ),
                new SoapHeader(
                    'http://x-road.eu/xsd/xroad.xsd',
                    'client',
                    new SoapVar(
                        new HeaderClient($this->client),
                        SOAP_ENC_OBJECT,
                        null,
                        null,
                        null,
                        'http://x-road.eu/xsd/xroad.xsd'
                    )
                ),
            ]
        );

        return parent::__soapCall(
            $name,
            $args,
            $options,
            $inputHeaders,
            $outputHeaders
        );
    }

}