<?php
require_once("lib/nusoap.php");

class Client extends nusoap_client
{
    function __construct($wsdl = "http://localhost/status-server/server.php?wsdl")
    {
        parent::__construct($wsdl, 'wsdl');
    }


    private function cors()
    {
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
    }

    public function infoCPU()
    {
        return $this->call('Service.infoCPU', array());
    }

    public function usageCPU()
    {
        return $this->call('Service.usageCPU', array());
    }

    public function usageRAM()
    {
        return $this->call('Service.usageRAM', array());
    }

    public function usageHD()
    {
        return $this->call('Service.usageHD', array());
    }

    public function dispatcher()
    {
        if (isset($_GET['service'])) {
            $this->cors();

            switch ($_GET['service']) {
                case 'infoCPU':
                    return $this->infoCPU();
                case 'usageCPU':
                    return $this->usageCPU();
                case 'usageRAM':
                    return $this->usageRAM();
                case 'usageHD':
                    return $this->usageHD();
                default:
                    return 'Wrong request';
            }
        }

        return 'Wrong request';
    }
}

$client = new Client();
$response = $client->dispatcher();
echo json_encode($response);

?>
