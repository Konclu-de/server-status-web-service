<?php

class Service
{
    public function infoCPU()
    {
        $modelCPU = shell_exec("cat /proc/cpuinfo | grep 'model name' | uniq ");

        $infoCPU = array(
            'modelCPU'   => split(':', $modelCPU, 2)[1],
            'numCoreCPU' => shell_exec("grep -c processor /proc/cpuinfo")
        );

        return $infoCPU;
    }

    public function usageCPU()
    {
        $stat1 = file('/proc/stat');
        sleep(1);
        $stat2 = file('/proc/stat');

        $info1 = explode(' ', preg_replace("!cpu +!", "", $stat1[0]));
        $info2 = explode(' ', preg_replace("!cpu +!", "", $stat2[0]));

        $dif = array();
        $dif['user'] = $info2[0] - $info1[0];
        $dif['nice'] = $info2[1] - $info1[1];
        $dif['sys'] = $info2[2] - $info1[2];
        $dif['idle'] = $info2[3] - $info1[3];
        $total = array_sum($dif);

        $usageCPU = array();
        foreach($dif as $x=>$y) $usageCPU[$x] = round($y / $total * 100, 1);

        return $usageCPU;
    }

    public function usageRAM()
    {
        $infoRAM = array(
            'size'     => preg_replace('!\s+!', ' ', shell_exec("free -m | grep 'Mem:'")),
            'usedFree' => preg_replace('!\s+!', ' ', shell_exec("free -m | grep 'cache:'"))
        );

        $usageRAM = array(
            'sizeRAM'       => split(' ', $infoRAM['size'])[1],
            'usedRAM'       => split(' ', $infoRAM['usedFree'])[2],
            'freeRAM'       => split(' ', $infoRAM['usedFree'])[3],
            'percentageRAM' => round(
                100 / split(' ', $infoRAM['size'])[1] * split(' ', $infoRAM['usedFree'])[2], 2
            ).'%'
        );

        return $usageRAM;
    }

    public function usageHD()
    {
        $totalSDA = preg_replace('!\s+!', ' ', shell_exec("df -m | grep '/dev/sda'"));
        $totalSDA = split(' ', $totalSDA);

        // considero solo la partizione nella quale
        // è installato il sistema operativo
        for ($i=0; $i < count($totalSDA)/7 ; $i++) {
            if ($totalSDA[(7*$i)+5] == "/") {
                $osSDA =array(
                    'pathSDA'       => $totalSDA[(7*$i)+0],
                    'totalSDA'      => $totalSDA[(7*$i)+1],
                    'usedSDA'       => $totalSDA[(7*$i)+2],
                    'freeSDA'       => $totalSDA[(7*$i)+3],
                    'percentageSDA' => $totalSDA[(7*$i)+4]
                );
                break;
            }
        }

        return $osSDA;
    }
}

?>
