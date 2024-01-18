<?php

namespace App\Http\Controllers;

use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class ProjektyController extends Controller
{
    public function importProjekty()
    {
        // import tabulky ako json
        $collection = (new FastExcel)->import('../../excely/projekty-2020-2021-2022.xlsx');

        // ziskame unikatne nazvy fakult
        $fakulty = $collection->unique('fakulta')->map(function ($item) {
            return [
                'fakulta' => $item['fakulta'],
            ];
        });

        foreach ($fakulty as $fakulta) {
            DB::table('fakulta')->updateOrInsert(['nazov' => $fakulta['fakulta']]);
        }

        // ziskame unikatne nazvy katedier
        $katedry = $collection->unique('katedra')->map(function ($item) use ($fakulty) {
            $fakultaId = null;

            // Prejdeme všetky fakulty a nájdeme príslušné fakulta_id
            foreach ($fakulty as $fakulta) {
                if ($fakulta['fakulta'] == $item['fakulta']) {
                    $fakultaId = DB::table('fakulta')->where('nazov', $fakulta['fakulta'])->value('id');
                    break;
                }
            }

            DB::table('katedra')->updateOrInsert(
                ['nazov' => $item['katedra']],
                ['fakulta_id' => $fakultaId]
            );

            return [
                'nazov' => $item['katedra'],
                'fakulta_id' => $fakultaId
            ];
        });


        foreach ($katedry as $katedra) {
            DB::table('katedra')->updateOrInsert(
                ['nazov' => $katedra['nazov']],
                $katedra
            );
        }



        // ziskame z jsonu unikatne zaznamy zamestnancov podla id (id, meno, priezvisko)
        $zamestnanci = $collection->unique('employee_id')->map(function ($item)  use ($fakulty) {
            $fakultaId = null;

            // Prejdeme všetky katedry a nájdeme príslušné katedra_id
            foreach ($fakulty as $fakulta) {
                if ($fakulta['fakulta'] == $item['fakulta']) {
                    $fakultaId = DB::table('fakulta')->value('id');
                    break;
                }
            }
            return [
                'id' => $item['employee_id'],
                'meno' => $item['meno'],
                'priezvisko' => $item['priezvisko'],
                'cele_meno' => $item['meno'] . " " . $item['priezvisko'],
                'rok' => $item['rok'],
                'users_id' => 1,
                'fakulta_id' => $fakultaId
            ];
        });

        foreach ($zamestnanci as $zamestnanec) {
            DB::table('zamestnanci')->updateOrInsert(
                ['id' => $zamestnanec['id']],
                $zamestnanec
            );
        }

        // ziskame unikatne programy
        $programy = $collection->unique('grant_program_id')->map(function ($item) {
            return [
                'id' => $item['grant_program_id'],
                'acronym' => $item['acronym'],
            ];
        });

        foreach ($programy as $program) { // prida programy, ak este neexistuju
            DB::table('grant_programs')->updateOrInsert(
                ['id' => $program['id']],
                $program
            );
        }

        DB::transaction(function () use ($collection) {
            foreach ($collection as $item) {
                $proProjektId = DB::table('pro_projekt')->updateOrInsert([
                    'id_projektu' => $item['project_id'],
                    'nazov' => $item['title'],
                    'typ' => $item['acronym'],
                    'grant_programs_id' => $item['grant_program_id'],
                    'pro_rozpocet_id' => 1,
                ]);

                $podiel = ($item['podiel'] !== "NULL") ? $item['podiel'] : 0;

                DB::table('pro_podiely')->updateOrInsert([
                    'podiel' => $podiel,
                    'rok' => $item['rok'],
                    'projekt_id' => $item['project_id'],
                    'pro_projekt_id' => $proProjektId,
                    'zamestnanci_id' => $item['employee_id']
                ]);
            }
        });

        return response()->json();
    }
}
