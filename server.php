<?php
require_once("lib/nusoap.php");
require_once("service.php");

class Server extends soap_server
{
    function __construct($ns = "http://localhost/status-server/")
    {
        $this->configureWSDL('ServerStatus', $ns);
        $this->wsdl->schemaTargetNamespace = $ns;

        $this->wsdl->addComplexType(
            'InfoCPU',
            'complexType',
            'array',
            'all',
            '',
            array(
                'modelCPU'   => array('name' => 'modelCPU', 'type' => 'xsd:string'),
                'numCoreCPU' => array('name' => 'numCoreCPU', 'type' => 'xsd:int')
            )
        );
        $this->wsdl->addComplexType(
            'UsageCPU',
            'complexType',
            'array',
            'all',
            '',
            array(
                'userCPU'   => array('name' => 'userCPU', 'type' => 'xsd:double'),
                'niceCPU'   => array('name' => 'niceCPU', 'type' => 'xsd:double'),
                'sysCPU'    => array('name' => 'sysCPU',  'type' => 'xsd:double'),
                'idleCPU'   => array('name' => 'idleCPU', 'type' => 'xsd:double')
            )
        );
        $this->wsdl->addComplexType(
            'UsageRAM',
            'complexType',
            'array',
            'all',
            '',
            array(
                'sizeRAM'       => array('name' => 'sizeRAM', 'type' => 'xsd:integer'),
                'usedRAM'       => array('name' => 'usedRAM', 'type' => 'xsd:integer'),
                'freeRAM'       => array('name' => 'freeRAM',  'type' => 'xsd:integer'),
                'percentageRAM' => array('name' => 'percentageRAM', 'type' => 'xsd:string')
            )
        );
        $this->wsdl->addComplexType(
            'UsageHD',
            'complexType',
            'array',
            'all',
            '',
            array(
                'pathSDA'       => array('name' => 'pathSDA', 'type' => 'xsd:string'),
                'totalSDA'      => array('name' => 'totalSDA', 'type' => 'xsd:int'),
                'usedSDA'       => array('name' => 'usedSDA',  'type' => 'xsd:int'),
                'freeSDA'       => array('name' => 'freeSDA', 'type' => 'xsd:int'),
                'percentageSDA' => array('name' => 'percentageSDA', 'type' => 'xsd:string')
            )
        );

        $this->register('Service.usageCPU', array(), array('return' => 'tns:UsageCPU'), $ns);
        $this->register('Service.infoCPU', array(), array('return' => 'tns:InfoCPU'), $ns);
        $this->register('Service.usageRAM', array(), array('return' => 'tns:UsageRAM'), $ns);
        $this->register('Service.usageHD', array(), array('return' => 'tns:UsageHD'), $ns);

        if (!isset($HTTP_RAW_POST_DATA))
            $HTTP_RAW_POST_DATA =file_get_contents('php://input');

        $this->service($HTTP_RAW_POST_DATA);
    }
}

new Server();

?>
