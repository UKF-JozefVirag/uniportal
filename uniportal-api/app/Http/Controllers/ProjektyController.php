<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class ProjektyController extends Controller
{

    public function importProjekty()
    {
//        $file = request()->file('excel');
//        // Kontrola typu súboru
//        if (!$file || $file->getExtension() !== 'xlsx' || $file->getExtension() !== 'xls'  || $file->getExtension() !== 'csv' ) {
//            return response()->json('Neplatný typ súboru', 400);
//        }
//        return $file;

        $allProjects = (new FastExcel)->import('../../excely/projekty-2020-2021-2022.xlsx');

        // ziskame unikatne nazvy fakult
        $fakulty = $allProjects->unique('fakulta')->map(function ($item) {
            return [
                'fakulta' => $item['fakulta'],
            ];
        });

        foreach ($fakulty as $fakulta) {
            DB::table('fakulta')->updateOrInsert(['nazov' => $fakulta['fakulta']]);
        }

        // ziskame unikatne nazvy katedier
        $katedry = $allProjects->unique('katedra')->map(function ($item) use ($fakulty) {
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
        $zamestnanci = $allProjects->unique('employee_id')->map(function ($item)  use ($fakulty) {
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
        $programy = $allProjects->unique('grant_program_id')->map(function ($item) {
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

        DB::transaction(function () use ($allProjects) {
            foreach ($allProjects as $item) {
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



    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    public function importVega() {
        //TODO: Treba nejak porovnavat tieto projekty so subormi VEGA KEGA a podobne, ak sa tam nazvy projektov zhoduju, zapisat ich asi do tabulky roz_pro_lat_sumy, kde stlpec kategoria je napr T1 a podobne

        // ziska vsetky zaznamy a vyfiltruje iba tie, kde sa nachadza pracovisko ukf
        $vegaProjects = ((new FastExcel)->import('../../excely/VEGA.xlsx'))->filter(function ($record) {
            // vyfiltrujeme iba pracoviska, ktore obsahuju 'UKF'
            return str_contains($record['Pracovisko'], 'UKF');
        })->toArray();
        foreach ($vegaProjects as $item) {
            $keys = array_keys($item);
            DB::table('vega')->updateOrInsert([
                'rok_zaciatku_riesenia_projektu' => $item[$keys[0]],
                'rok_skoncenia_riesenia_projektu' => $item[$keys[1]],
                'cislo_komisie_vega' => $item[$keys[2]],
                'evidencne_cislo' => $item[$keys[3]],
                'nazov_projektu' => $item[$keys[4]],
                'veduci_projektu_zastupca' => $item[$keys[5]],
                'skratka' => $item[$keys[7]],
                'bodove_hodnotenie' => $item[$keys[8]] !== "" ? $item[$keys[8]] : 0, // ocakavalo double hodnotu, no pri niektorych zaznamoch bol prazdny string
                'poradie' => $item[$keys[9]],
                'pozadovana_dotacia' => $item[$keys[10]],
                'pridelena_dotacia_bv' => $item[$keys[11]],
            ]);
        }
        return response("Success", 200);
    }

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    public function importKega() {
        $kegaProjects = collect((new FastExcel)->import('../../excely/KEGA.xlsx'))->filter(function ($record) {
            // vyfiltrujeme iba pracoviska iba univerzity konstantina filozofa v nitre
            return str_contains($record['Vysoká škola'], 'Univerzita Konštantína Filozofa v Nitre');
        })->toArray();

        foreach ($kegaProjects as $item) {
            $keys = array_keys($item);
            DB::table('kega')->updateOrInsert([
                'rok_zaciatku_riesenia_projektu' => $item[$keys[0]],
                'rok_skoncenia_riesenia_projektu' => $item[$keys[1]],
                'cislo_komisie_kega' => $item[$keys[2]],
                'evidencne_cislo' => $item[$keys[3]],
                'nazov_projektu' => $item[$keys[4]],
                'veduci_projektu_zastupca' => $item[$keys[5]],
                'pracovisko' => $item[$keys[6]],
                'bodove_hodnotenie' => $item[$keys[8]] !== "" ? $item[$keys[8]] : 0, // ocakavalo double hodnotu, no pri niektorych zaznamoch bol prazdny string
                'poradie' => $item[$keys[9]],
                'podiel_skoly_per' => $item[$keys[10]],
                'pozadovana_dotacia' => $item[$keys[12]],
                'pridelena_dotacia_bv' => $item[$keys[13]],
            ]);
        }
        return response("Success", 200);

    }

    /**
     * @throws IOException
     * @throws ReaderNotOpenedException
     * @throws UnsupportedTypeException
     */
    public function importApvv() {
        // startrow musi byt 2, pretoze v exceli je defaultne prvy riadok zlozeny z mergenutych cells, co nam pokazi cele formatovanie array, cize zacneme az od druheho
        $apvvT1 = collect((new FastExcel)->startRow(2)->import('../../excely/Prehlad_VVSprojekty_2021.xlsx'))->filter(function ($record){
            return str_contains($record['Vysoká škola'], 'UKF');
        })->toArray();
        // posledny row je prazdny a je v nom iba sucet vsetkych BV dokopy, cize ho vymazeme z array, aby sa nam lahsie importovalo do db

        // v prvom riadku apvv suboru sa na konci nachadza rok, za ktory bola dotacia uvedena, tak ziskane tento riadok a substringom ziskame rok
        $apvvY = collect((new FastExcel())->import('../../excely/Prehlad_VVSprojekty_2021.xlsx'))->first();
        $apvvYear = Str::substr(key($apvvY), -4);

        foreach ($apvvT1 as $item) {
            $keys = array_keys($item);
            DB::table('apvv')->updateOrInsert([
                'kategoria' => "T1",
                'rok_dotacie' => $apvvYear,
                'fakulta' => $item[$keys[1]],
                'nazov' => $item[$keys[2]],
                'meno' => $item[$keys[3]],
                'id_projektu' => $item[$keys[4]],
                'skupina_odborov' => $item[$keys[5]],
                'podskupina_odborov' => $item[$keys[6]],
                'odbor_vedy_a_techniky' => $item[$keys[7]],
                'oblast_vyskumu' => $item[$keys[8]],
                'podnet_na_podavanie_navrhov' => $item[$keys[9]],
                'nazov_programu_podpory' => $item[$keys[10]],
                'nazov_institucie_podpory' => $item[$keys[11]],
                'ico' => $item[$keys[12]],
                'datum_podpisu_zmluvy_o_podpore' => $item[$keys[13]],
                'rok_zaciatku_riesenia_projektu' => $item[$keys[14]],
                'rok_skoncenia_riesenia_projektu' => $item[$keys[15]],
                'pridelena_dotacia_BV' => $item[$keys[16]],
                'doplnujuce_info' => $item[$keys[17]],
                'anotacia' => $item[$keys[18]],
                'zdovodnenie_charakteru_prace' => $item[$keys[19]],
                'an' => $item[$keys[20]],
                'komentar' => $item[$keys[21]],
            ]);
        }

        $apvvT2 = collect((new FastExcel)->startRow(2)->sheet(2)->import('../../excely/Prehlad_VVSprojekty_2021.xlsx'))->filter(function ($record){
            return str_contains($record['Vysoká škola'], 'UKF');
        })->toArray();

        foreach ($apvvT2 as $item) {
            $keys = array_keys($item);
            DB::table('apvv')->updateOrInsert([
                'kategoria' => "T2",
                'rok_dotacie' => $apvvYear,
                'fakulta' => $item[$keys[1]],
                'nazov' => $item[$keys[2]],
                'meno' => $item[$keys[3]],
                'id_projektu' => $item[$keys[4]],
                'skupina_odborov' => $item[$keys[5]],
                'podskupina_odborov' => $item[$keys[6]],
                'odbor_vedy_a_techniky' => $item[$keys[7]],
                'oblast_vyskumu' => $item[$keys[8]],
                'podnet_na_podavanie_navrhov' => $item[$keys[9]],
                'nazov_programu_podpory' => $item[$keys[10]],
                'nazov_institucie_podpory' => $item[$keys[11]],
                'ico' => $item[$keys[12]],
                'datum_podpisu_zmluvy_o_podpore' => $item[$keys[13]],
                'rok_zaciatku_riesenia_projektu' => $item[$keys[14]],
                'rok_skoncenia_riesenia_projektu' => $item[$keys[15]],
                'pridelena_dotacia_BV' => $item[$keys[16]],
                'doplnujuce_info' => $item[$keys[17]],
                'anotacia' => $item[$keys[18]],
                'zdovodnenie_charakteru_prace' => $item[$keys[19]],
                'an' => $item[$keys[20]],
                'komentar' => $item[$keys[21]],
            ]);
        }

        $apvvT3 = collect((new FastExcel)->startRow(2)->sheet(3)->import('../../excely/Prehlad_VVSprojekty_2021.xlsx'))->filter(function ($record){
            return str_contains($record['Vysoká škola'], 'UKF');
        })->toArray();

        foreach ($apvvT3 as $item) {
            $keys = array_keys($item);

            DB::table('apvv')->updateOrInsert([
                'kategoria' => "T3",
                'rok_dotacie' => $apvvYear,
                'fakulta' => $item[$keys[1]],
                'nazov' => $item[$keys[2]],
                'meno' => $item[$keys[3]],
                'id_projektu' => $item[$keys[4]],
                'skupina_odborov' => $item[$keys[5]],
                'podskupina_odborov' => $item[$keys[6]],
                'odbor_vedy_a_techniky' => $item[$keys[7]],
                'oblast_vyskumu' => $item[$keys[8]],
                'podnet_na_podavanie_navrhov' => $item[$keys[9]],
                'nazov_programu_podpory' => $item[$keys[10]],
                'nazov_institucie_podpory' => $item[$keys[11]],
                'ico' => $item[$keys[12]],
                'datum_podpisu_zmluvy_o_podpore' => $item[$keys[13]],
                'rok_zaciatku_riesenia_projektu' => $item[$keys[14]],
                'rok_skoncenia_riesenia_projektu' => $item[$keys[15]],
                'pridelena_dotacia_BV' => $item[$keys[16]],
                'doplnujuce_info' => $item[$keys[17]],
                'anotacia' => $item[$keys[18]],
                'zdovodnenie_charakteru_prace' => $item[$keys[19]],
                'an' => $item[$keys[20]],
                'komentar' => $item[$keys[21]],
            ]);
        }

        $apvvT4 = collect((new FastExcel)->startRow(2)->sheet(4)->import('../../excely/Prehlad_VVSprojekty_2021.xlsx'))->filter(function ($record){
            return str_contains($record['Vysoká škola'], 'UKF');
        })->toArray();

        foreach ($apvvT4 as $item) {
            $keys = array_keys($item);

            DB::table('apvv')->updateOrInsert([
                'kategoria' => "T4",
                'rok_dotacie' => $apvvYear,
                'fakulta' => $item[$keys[1]],
                'nazov' => $item[$keys[2]],
                'meno' => $item[$keys[3]],
                'id_projektu' => $item[$keys[4]],
                'skupina_odborov' => $item[$keys[5]],
                'podskupina_odborov' => $item[$keys[6]],
                'odbor_vedy_a_techniky' => $item[$keys[7]],
                'oblast_vyskumu' => $item[$keys[8]],
                'podnet_na_podavanie_navrhov' => $item[$keys[9]],
                'nazov_programu_podpory' => $item[$keys[10]],
                'nazov_institucie_podpory' => $item[$keys[11]],
                'ico' => $item[$keys[12]],
                'datum_podpisu_zmluvy_o_podpore' => $item[$keys[13]],
                'rok_zaciatku_riesenia_projektu' => $item[$keys[14]],
                'rok_skoncenia_riesenia_projektu' => $item[$keys[15]],
                'pridelena_dotacia_BV' => $item[$keys[16]],
                'doplnujuce_info' => $item[$keys[17]],
                'anotacia' => $item[$keys[18]],
                'an' => $item[$keys[19]],
                'komentar' => $item[$keys[20]],
            ]);
        }

        $apvvT5 = collect((new FastExcel)->startRow(2)->sheet(5)->import('../../excely/Prehlad_VVSprojekty_2021.xlsx'))->filter(function ($record){
            return str_contains($record['Vysoká škola'], 'UKF');
        })->toArray();

        foreach ($apvvT5 as $item) {
            $keys = array_keys($item);

            DB::table('apvv')->updateOrInsert([
                'kategoria' => "T5",
                'rok_dotacie' => $apvvYear,
                'fakulta' => $item[$keys[1]],
                'nazov' => $item[$keys[2]],
                'meno' => $item[$keys[3]],
                'id_projektu' => $item[$keys[4]],
                'skupina_odborov' => $item[$keys[5]],
                'podskupina_odborov' => $item[$keys[6]],
                'odbor_vedy_a_techniky' => $item[$keys[7]],
                'oblast_vyskumu' => $item[$keys[8]],
                'podnet_na_podavanie_navrhov' => $item[$keys[9]],
                'nazov_programu_podpory' => $item[$keys[10]],
                'nazov_institucie_podpory' => $item[$keys[11]],
                'ico' => $item[$keys[12]],
                'datum_podpisu_zmluvy_o_podpore' => $item[$keys[13]],
                'rok_zaciatku_riesenia_projektu' => $item[$keys[14]],
                'rok_skoncenia_riesenia_projektu' => $item[$keys[15]],
                'pridelena_dotacia_BV' => $item[$keys[16]],
                'doplnujuce_info' => $item[$keys[17]],
                'anotacia' => $item[$keys[18]],
                'an' => $item[$keys[19]],
                'komentar' => $item[$keys[20]],
            ]);
        }

        return response("Success", 200);
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

    public function synchronizeProjects() {
        $apvvResults = DB::table('apvv')
            ->join('pro_projekt', 'apvv.nazov', '=', 'pro_projekt.nazov')
            ->select('*')
            ->get();

        $kegaResults = DB::table('kega')
            ->join('pro_projekt', 'kega.nazov_projektu', '=', 'pro_projekt.nazov')
            ->select('*')
            ->get();

        $vegaResults = DB::table('vega')
            ->join('pro_projekt', 'vega.nazov_projektu', '=', 'pro_projekt.nazov')
            ->select('*')
            ->get();

        return [$apvvResults, $kegaResults, $vegaResults];

    }

}
