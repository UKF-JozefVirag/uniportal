<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class PublikacieController extends Controller {

    public function importPublikacie()
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
                    'id_publikacie' => $record[$keys[1]],
                    'nazov' => $extrahovanyNazov,
                    'zaznam' => $record[$keys[2]],
                    'link' => $record[$keys[3]]
                ];
            }
        }

        foreach ($zaznamyPublikacii as $zaznam) {
            DB::table('publikacie')->updateOrInsert([
                'id_publikacie' => $zaznam['id_publikacie'],
                'nazov' => $zaznam['nazov'],
                'zaznam' => $zaznam['zaznam'],
                'link' => $zaznam['link'],
            ]);
        }

        return response("Success", 200);
    }

    public function getPublikacie() {
        $publikacie = DB::select('
        SELECT
            pb.id_publikacie AS "ID Publikácie",
            pb.nazov AS "Názov",
            pb.zaznam AS "Celý záznam",
            pb.link AS "Odkaz"
        FROM publikacie pb
        ');
        return $publikacie;
    }
}
