<?php
namespace App\Http\Controllers;
use Rap2hpoutre\FastExcel\FastExcel;

class PublikacieController extends Controller {

    public function getPublikacie()
    {
        $allPublikacie = (new FastExcel)->startRow(6)->import('../../excely/zostava1 CREPČ UKF za roky 2018-2022.xlsx');
        $data = json_decode($allPublikacie, true);
        $zaznamyPublikacii = [];

        foreach ($data as $record) {
            $keys = array_keys($record);

            // Filter na prazdne alebo na chybne zaznamy
            if (isset($keys[2]) && strlen($record[$keys[2]]) > 20) {
                $nazov = $record[$keys[2]];

                // Extrahovanie nazvu, regex zoberie zaznam po prvy vyskyt znaku [ alebo /
                preg_match('/^(.*?)[\/\[]/', $nazov, $matches);
                $extrahovanyNazov = isset($matches[1]) ? trim($matches[1]) : '';

                $zaznamyPublikacii[] = [
                    'id' => $record[$keys[1]],
                    'nazov' => $extrahovanyNazov,
                    'zaznam' => $record[$keys[2]],
                    'link' => $record[$keys[3]]
                ];
            }
        }
        return $zaznamyPublikacii;
    }
}
