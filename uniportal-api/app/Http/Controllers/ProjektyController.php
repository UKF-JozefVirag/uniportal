<?php

namespace App\Http\Controllers;

use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class ProjektyController extends Controller
{
    public function importProjekty()
    {
//        // import tabulky ako json
//        $allProjects = (new FastExcel)->import('../../excely/projekty-2020-2021-2022.xlsx');
//
//        // ziskame unikatne nazvy fakult
//        $fakulty = $allProjects->unique('fakulta')->map(function ($item) {
//            return [
//                'fakulta' => $item['fakulta'],
//            ];
//        });
//
//        foreach ($fakulty as $fakulta) {
//            DB::table('fakulta')->updateOrInsert(['nazov' => $fakulta['fakulta']]);
//        }
//
//        // ziskame unikatne nazvy katedier
//        $katedry = $allProjects->unique('katedra')->map(function ($item) use ($fakulty) {
//            $fakultaId = null;
//
//            // Prejdeme všetky fakulty a nájdeme príslušné fakulta_id
//            foreach ($fakulty as $fakulta) {
//                if ($fakulta['fakulta'] == $item['fakulta']) {
//                    $fakultaId = DB::table('fakulta')->where('nazov', $fakulta['fakulta'])->value('id');
//                    break;
//                }
//            }
//
//            DB::table('katedra')->updateOrInsert(
//                ['nazov' => $item['katedra']],
//                ['fakulta_id' => $fakultaId]
//            );
//
//            return [
//                'nazov' => $item['katedra'],
//                'fakulta_id' => $fakultaId
//            ];
//        });
//
//
//        foreach ($katedry as $katedra) {
//            DB::table('katedra')->updateOrInsert(
//                ['nazov' => $katedra['nazov']],
//                $katedra
//            );
//        }
//
//
//
//        // ziskame z jsonu unikatne zaznamy zamestnancov podla id (id, meno, priezvisko)
//        $zamestnanci = $allProjects->unique('employee_id')->map(function ($item)  use ($fakulty) {
//            $fakultaId = null;
//
//            // Prejdeme všetky katedry a nájdeme príslušné katedra_id
//            foreach ($fakulty as $fakulta) {
//                if ($fakulta['fakulta'] == $item['fakulta']) {
//                    $fakultaId = DB::table('fakulta')->value('id');
//                    break;
//                }
//            }
//            return [
//                'id' => $item['employee_id'],
//                'meno' => $item['meno'],
//                'priezvisko' => $item['priezvisko'],
//                'cele_meno' => $item['meno'] . " " . $item['priezvisko'],
//                'rok' => $item['rok'],
//                'users_id' => 1,
//                'fakulta_id' => $fakultaId
//            ];
//        });
//
//        foreach ($zamestnanci as $zamestnanec) {
//            DB::table('zamestnanci')->updateOrInsert(
//                ['id' => $zamestnanec['id']],
//                $zamestnanec
//            );
//        }
//
//        // ziskame unikatne programy
//        $programy = $allProjects->unique('grant_program_id')->map(function ($item) {
//            return [
//                'id' => $item['grant_program_id'],
//                'acronym' => $item['acronym'],
//            ];
//        });
//
//        foreach ($programy as $program) { // prida programy, ak este neexistuju
//            DB::table('grant_programs')->updateOrInsert(
//                ['id' => $program['id']],
//                $program
//            );
//        }
//
//        DB::transaction(function () use ($allProjects) {
//            foreach ($allProjects as $item) {
//                $proProjektId = DB::table('pro_projekt')->updateOrInsert([
//                    'id_projektu' => $item['project_id'],
//                    'nazov' => $item['title'],
//                    'typ' => $item['acronym'],
//                    'grant_programs_id' => $item['grant_program_id'],
//                    'pro_rozpocet_id' => 1,
//                ]);
//
//                $podiel = ($item['podiel'] !== "NULL") ? $item['podiel'] : 0;
//
//                DB::table('pro_podiely')->updateOrInsert([
//                    'podiel' => $podiel,
//                    'rok' => $item['rok'],
//                    'projekt_id' => $item['project_id'],
//                    'pro_projekt_id' => $proProjektId,
//                    'zamestnanci_id' => $item['employee_id']
//                ]);
//            }
//        });

        //TODO: Treba nejak porovnavat tieto projekty so subormi VEGA KEGA a podobne, ak sa tam nazvy projektov zhoduju, zapisat ich asi do tabulky roz_pro_lat_sumy, kde stlpec kategoria je napr T1 a podobne

        // ziska vsetky zaznamy a vyfiltruje iba tie, kde sa nachadza pracovisko ukf
        $vegaProjects = collect((new FastExcel)->import('../../excely/VEGA.xlsx'))->filter(function ($record) {
            return str_contains($record['Pracovisko'], 'UKF');
        })->toArray();

        foreach ($vegaProjects as $item) {
            DB::table('roz_pro_lat_sumy')->updateOrInsert([
                'kategoria' => "VEGA", // sem neviem co doplnit
                'suma' => $item['Pridelená dotácia v kategórii BV(€)'],
                'rok_zac' => $item['Rok začiatku riešenia projektu'],
                'rok_kon' => $item['Rok skončenia riešenia projektu'],
            ]);
        }
        return $vegaProjects;
    }

    public function getProjects() {
        $projekty = DB::select('
        SELECT
            ppd.id,
            pp.id_projektu AS "ID Projektu",
            pp.nazov AS "Názov",
            pp.typ AS "Typ",
            zam.cele_meno AS "Celé meno zamestnanca",
            ppd.podiel AS "Podiel na projekte"
        FROM pro_podiely ppd
        LEFT JOIN pro_projekt pp ON ppd.projekt_id = pp.id_projektu
        LEFT JOIN zamestnanci zam ON ppd.zamestnanci_id = zam.id;
        ');
        return $projekty;
    }

}
