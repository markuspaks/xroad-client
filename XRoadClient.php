<?php

namespace XRoadClient;

use XRoadClient\Soap\Client;

class XRoadClient
{
    /**
     * @var Client
     */
    protected $soapClient;

    public function addAttachment($file): string
    {
        return $this->soapClient->addAttachment($file);
    }

    public function getAttachments()
    {
        return $this->soapClient->getAttachments();
    }

    /**
     * Call soap function as callable
     *
     * @param string $func
     * @param mixed $args
     * @return mixed
     */
    public function __call($func, $args)
    {
        $args = [$args]; //This was done in soapCall, but caused issues with wsdl that uses parts
        array_unshift($args, $func);
        return call_user_func_array([$this->soapClient, '__call'], $args);
    }

    public function setSoap(string $wsdl, ?array $options = null): void
    {
        $soapClient = new \XRoadClient\Soap\Client($wsdl, $options);
        $this->soapClient = $soapClient;
    }

    public function setService(Subsystem $service): void
    {
        $this->soapClient->setService($service);
    }

    public function setClient(Subsystem $client): void
    {
        $this->soapClient->setClient($client);
    }

    public function getLastRequest()
    {
        return $this->soapClient->__getLastRequest();
    }

    public function getLastResponse()
    {
        return $this->soapClient->__getLastResponse();
    }

}