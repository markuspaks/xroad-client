<?php

use DependableSoapClient\DependableSoapClient;
use DependableSoapClient\SoapAttachment;
use XRoadClient\Soap\Client;
use XRoadClient\Subsystem;
use XRoadClient\XRoadClient;

require_once __DIR__.'/vendor/autoload.php';

Client::setLogCallback(
    function ($log, $level) {
        echo "$level: $log\n";
    }
);

$client = new XRoadClient();

$client->setSoap(
    __DIR__.'/../x7/Modules/EstonianCustoms/Resources/wsdls/impulss-xroad.wsdl',
    [
        'location' => 'http://xroad.ospentos.test.qstep.eu:80/cgi-bin/uriproxy?producer=emta',
        'debug_level' => DependableSoapClient::DEBUG_ALL
    ]
);

$client->setService(new Subsystem('ee-test/GOV/70000349/impulss_koolitus'));
$client->setClient(new Subsystem('ee-test/COM/11306955/qstepxtee6eesub'));

// Send IE415 message
$cid = $client->addAttachment(new SoapAttachment(__DIR__.'/IE415.zip'));

/** @noinspection PhpUndefinedMethodInspection */
$client->sendAsyncMessage('v1', [
    'operation' => 'IE415',
    'messageIdentification' => 23123123,
    'LRN' => 2312312,
    'xmlFile' => $cid
]);

// Read messages list
/** @noinspection PhpUndefinedMethodInspection */
$messageList = $client->readMessageListBetweenDates('v1', ['startDatetime' => '2021-03-01']);

// Read one message content
/** @noinspection PhpUndefinedMethodInspection */
$client->readMessages('v1', ['systemMessageId' => $messageList->items[count($messageList->items) - 1]->systemMessageId]);

$message = $client->getAttachments()[0];
file_put_contents('message.zip', $message->getContent());

//
//
//$cid = $client->addAttachment(new SoapAttachment(__DIR__.'/IE415.zip'));
//
//$soapenvelopeXml = <<<EOS
//<soapenv:Envelope
//	xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
//	xmlns:xro="http://x-road.eu/xsd/xroad.xsd"
//	xmlns:iden="http://x-road.eu/xsd/identifiers"
//	xmlns:emta="http://emta-v6.x-road.eu"
//	xmlns:xro1="http://impulss.emta.ee/xroad">
//   <soapenv:Header>
//      <xro:userId>EE11306955</xro:userId>
//      <xro:protocolVersion>4.0</xro:protocolVersion>
//      <xro:id>TEST3123</xro:id>
//      <xro:service iden:objectType="SERVICE">
//         <iden:xRoadInstance>ee-test</iden:xRoadInstance>
//         <iden:memberClass>GOV</iden:memberClass>
//         <iden:memberCode>70000349</iden:memberCode>
//         <iden:subsystemCode>impulss_koolitus</iden:subsystemCode>
//         <iden:serviceCode>sendAsyncMessage</iden:serviceCode>
//         <iden:serviceVersion>v1</iden:serviceVersion>
//      </xro:service>
//      <xro:client iden:objectType="SUBSYSTEM">
//         <iden:xRoadInstance>ee-test</iden:xRoadInstance>
//         <iden:memberClass>COM</iden:memberClass>
//         <iden:memberCode>11306955</iden:memberCode>
//         <iden:subsystemCode>qstepxtee6eesub</iden:subsystemCode>
//      </xro:client>
//   </soapenv:Header>
//   <soapenv:Body>
//        <xro1:sendAsyncMessage>
//            <operation>IE415</operation>
//            <messageIdentification>23123123</messageIdentification>
//            <LRN>2312312</LRN>
//            <xmlFile>cid:$cid</xmlFile>
//        </xro1:sendAsyncMessage>
//   </soapenv:Body>
//</soapenv:Envelope>
//EOS;
//
//$client->__doRequest($soapenvelopeXml, $location, null, SOAP_1_1, false);
//
//$soapenvelopeXml = <<<EOS
//<soapenv:Envelope
//	xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
//	xmlns:xro="http://x-road.eu/xsd/xroad.xsd"
//	xmlns:iden="http://x-road.eu/xsd/identifiers"
//	xmlns:emta="http://emta-v6.x-road.eu"
//	xmlns:xro1="http://impulss.emta.ee/xroad">
//   <soapenv:Header>
//      <xro:userId>EE11306955</xro:userId>
//      <xro:protocolVersion>4.0</xro:protocolVersion>
//      <xro:id>TEST3123</xro:id>
//      <xro:service iden:objectType="SERVICE">
//         <iden:xRoadInstance>ee-test</iden:xRoadInstance>
//         <iden:memberClass>GOV</iden:memberClass>
//         <iden:memberCode>70000349</iden:memberCode>
//         <iden:subsystemCode>impulss_koolitus</iden:subsystemCode>
//         <iden:serviceCode>readMessageListBetweenDates</iden:serviceCode>
//         <iden:serviceVersion>v1</iden:serviceVersion>
//      </xro:service>
//      <xro:client iden:objectType="SUBSYSTEM">
//         <iden:xRoadInstance>ee-test</iden:xRoadInstance>
//         <iden:memberClass>COM</iden:memberClass>
//         <iden:memberCode>11306955</iden:memberCode>
//         <iden:subsystemCode>qstepxtee6eesub</iden:subsystemCode>
//      </xro:client>
//   </soapenv:Header>
//   <soapenv:Body>
//        <xro1:readMessageListBetweenDates>
//            <startDatetime>2021-03-01</startDatetime>
//        </xro1:readMessageListBetweenDates>
//   </soapenv:Body>
//</soapenv:Envelope>
//EOS;
//
//$soapenvelopeXml = <<<EOS
//<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://impulss.emta.ee/xroad" xmlns:ns2="http://x-road.eu/xsd/xroad.xsd" xmlns:ns3="http://x-road.eu/xsd/identifiers"><SOAP-ENV:Header><ns2:userId>EE11306955</ns2:userId><ns2:protocolVersion>4.0</ns2:protocolVersion><ns2:id>dasda</ns2:id><ns2:service ns3:objectType="SERVICE"><ns3:xRoadInstance>ee-test</ns3:xRoadInstance><ns3:memberClass>GOV</ns3:memberClass><ns3:memberCode>70000349</ns3:memberCode><ns3:subsystemCode>impulss_koolitus</ns3:subsystemCode><ns3:serviceCode>readMessageListBetweenDates</ns3:serviceCode><ns3:serviceVersion>v1</ns3:serviceVersion></ns2:service><ns2:client ns3:objectType="SUBSYSTEM"><ns3:xRoadInstance>ee-test</ns3:xRoadInstance><ns3:memberClass>COM</ns3:memberClass><ns3:memberCode>11306955</ns3:memberCode><ns3:subsystemCode>qstepxtee6eesub</ns3:subsystemCode></ns2:client></SOAP-ENV:Header><SOAP-ENV:Body><ns1:readMessageListBetweenDates><startDatetime>2021-03-01</startDatetime></ns1:readMessageListBetweenDates></SOAP-ENV:Body></SOAP-ENV:Envelope>
//EOS;
//
//
//$client->__doRequest($soapenvelopeXml, $location, null, SOAP_1_1, false);
