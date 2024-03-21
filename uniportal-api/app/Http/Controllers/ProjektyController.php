<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class ProjektyController extends Controller
{

    public function importProjekty(Request $request)
    {
//        $file = request()->file('file');
        // Kontrola typu súboru
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






        $users = (new FastExcel)->import('../../excely/zoznam.xlsx');

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
                'meno' => $item['meno'],
                'priezvisko' => $item['priezvisko'],
                'cele_meno' => $item['meno'] . " " . $item['priezvisko'],
                'rok' => $item['rok'],
                'user_id' => $item['employee_id'],
                'fakulta_id' => $fakultaId
            ];
        });

        foreach ($zamestnanci as $zamestnanec) {
            DB::table('zamestnanci')->updateOrInsert(
                $zamestnanec
            );
        }

        //TODO: dokoncit // pseudokod:
        //        // pridame vsetkych zamestnancov zo zoznam.xlsx a koniec cyklu,
        //        // potom prejdeme po zamestnancoch z projektov a zistime, či sa nachadza v
        //        // tabulke niekto s podobnym id, ak ano, updatneme zaznam o meno a priezvisko
        //        // ak nie, tak pridame novy zaznam bez emailu a hesla a podobne, bude tam null
        $userMails = array_filter($users->map(function ($item) {
            if (is_numeric($item['Column22'])) {
                return "";
            } else {
                return [
                    'id' => $item['Column1'],
                    'email' => isset($item['Column22']) ? $item['Column22'] . "@ukf.sk" : null,
                ];
            }
        })->toArray());

        DB::transaction(function () use ($userMails, $zamestnanci) {
            foreach ($userMails as $user) {
                $zamestnanecFound = false;
                foreach ($zamestnanci as $zamestnanec) {
                    if ($user['id'] == $zamestnanec['user_id']) {
                        // Ak sa ID zhoduje, aktualizujeme záznam
                        DB::table('zamestnanci')->where('user_id', $zamestnanec['user_id'])
                            ->update(['email' => $user['email']]);

                        $zamestnanecFound = true;
                        break;
                    }
                }

                if (!$zamestnanecFound) {
                    DB::table('zamestnanci')->insert([
                        'email' => $user['email'],
                        'password' => password_hash('123456', PASSWORD_BCRYPT),
                        'validation_key' => random_int(100000, 999999),
                        'rola' => 1,
                        'updated_at' => Carbon::now()->toDateTimeString(),
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'verified' => 0,
                    ]);
                }
            }
        });


        DB::transaction(function () use ($allProjects) {
            foreach ($allProjects as $item) {
                $proProjektId = DB::table('pro_projekt')->updateOrInsert([
                    'id_projektu' => $item['project_id'],
                    'nazov' => $item['title'],
                    'typ' => $item['acronym'],
                    'pro_rozpocet_id' => 1,
                    'id_program_projekt'
                ]);

                $podiel = ($item['podiel'] !== "NULL") ? $item['podiel'] : 0;

                DB::table('pro_podiely')->updateOrInsert([
                    'podiel' => $podiel,
                    'rok' => $item['rok'],
                    'projekt_id' => $item['project_id'],
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

        $this->synchronizeProjects();
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

        $this->synchronizeProjects();

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
        $this->synchronizeProjects();
        return response("Success", 200);
    }

    public function getProjectsSynced() {
        $projekty = DB::select('
        SELECT
            pp.id_projektu AS "ID Projektu",
            pp.nazov AS "Názov",
            pp.typ AS "Typ",
            zam.cele_meno AS "Meno",
            ppd.podiel AS "Podiel",
            ppd.rok AS "Rok"
        FROM pro_projekt pp
        LEFT JOIN pro_podiely ppd ON pp.id_projektu = ppd.projekt_id
        LEFT JOIN zamestnanci zam ON ppd.zamestnanci_id = zam.user_id
        WHERE pp.id_program_projekt != 0
        GROUP BY pp.id_projektu, pp.nazov, pp.typ, zam.cele_meno, ppd.podiel, ppd.rok;
        ');
        return $projekty;
    }

    public function getProjects() {
        $projekty = DB::select('
        SELECT
            pp.id_projektu AS "ID Projektu",
            pp.nazov AS "Názov",
            pp.typ AS "Typ",
            zam.cele_meno AS "Meno",
            ppd.podiel AS "Podiel",
            ppd.rok AS "Rok"
        FROM pro_projekt pp
        LEFT JOIN pro_podiely ppd ON pp.id_projektu = ppd.projekt_id
        LEFT JOIN zamestnanci zam ON ppd.zamestnanci_id = zam.user_id
        WHERE pp.id_program_projekt = 0
        GROUP BY pp.id_projektu, pp.nazov, pp.typ, zam.cele_meno, ppd.podiel, ppd.rok;
        ');
        return $projekty;
    }

    public function getProjectsVega(): array
    {
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
        LEFT JOIN zamestnanci zam ON ppd.zamestnanci_id = zam.id
        WHERE pp.typ = "VEGA"
        ');
        return $projekty;
    }

    public function getProjectsKega() {
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
        LEFT JOIN zamestnanci zam ON ppd.zamestnanci_id = zam.id
        WHERE pp.typ = "KEGA"
        ');
        return $projekty;
    }

    public function getProjectsApvv() {
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
        LEFT JOIN zamestnanci zam ON ppd.zamestnanci_id = zam.id
        WHERE pp.typ = "APVV"
        ');
        return $projekty;
    }

    public function getVega() {
        $vega = DB::select('SELECT * FROM vega');
        return $vega;
    }

    public function getKega() {
        $kega = DB::select('SELECT * FROM kega');
        return $kega;
    }

    public function getApvv() {
        $apvv = DB::select('SELECT * FROM apvv');
        return $apvv;
    }

    public function synchronizeProjects() {

        $vegaResults = DB::table('pro_projekt')
            ->join('vega', 'vega.nazov_projektu', '=', 'pro_projekt.nazov')
            ->update([
                'pro_projekt.id_program_projekt' => DB::raw('vega.evidencne_cislo'),
            ]);

        $kegaResults = DB::table('pro_projekt')
            ->join('kega', 'kega.nazov_projektu', '=', 'pro_projekt.nazov')
            ->update([
                'pro_projekt.id_program_projekt' => DB::raw('kega.evidencne_cislo'),
            ]);

        $apvvResults = DB::table('pro_projekt')
            ->join('apvv', 'apvv.nazov', '=', 'pro_projekt.nazov')
            ->update([
                'pro_projekt.id_program_projekt' => DB::raw('apvv.id_projektu'),
            ]);

        return [$vegaResults, $kegaResults, $apvvResults];
    }

    public function manualSynchronizationProjects(Request $request) {
        $projectId = $request->get('project')[0]['id'];
        $projectProgramId = $request->get('projectProgram');
        // ziskanie hodnoty prveho kluca
        $firstKeyProgram = array_key_first($projectProgramId[0]);
        $firstKeyValueProgram = $projectProgramId[0][$firstKeyProgram];
        // ziska nazov prveho kluca a vymaze z neho prve dve chars, ktore su vzdy "id".. aby sme vedeli, do ktoreho typu projektu zapisat id
        $projectType = substr(array_key_first($projectProgramId[0]),2);


        $affected = DB::table('pro_projekt')
            ->where('typ', '=', $projectType)
            ->update([
                'pro_projekt.id_program_projekt' => $firstKeyValueProgram
            ]);

        return response("Success", 200);
    }

    public function getAllStatInfo()
    {
        $sql = DB::select("
            SELECT
                pp.nazov,
                pp.id_program_projekt,
                ppd.podiel,
                ppd.rok,
                zam.cele_meno,
                CASE
                    WHEN pp.id_program_projekt = vega.evidencne_cislo THEN 'vega'
                    WHEN pp.id_program_projekt = kega.evidencne_cislo THEN 'kega'
                    WHEN pp.id_program_projekt = apvv.id_projektu THEN 'apvv'
                    ELSE NULL
                END AS typ,
                COALESCE(vega.pridelena_dotacia_bv, kega.pridelena_dotacia_bv, apvv.pridelena_dotacia_bv) AS pridelena_dotacia_bv,
                CASE
                    WHEN pp.id_program_projekt = apvv.id_projektu THEN apvv.fakulta
                    WHEN pp.id_program_projekt = vega.evidencne_cislo THEN vega.skratka
                    WHEN pp.id_program_projekt = kega.evidencne_cislo THEN kega.pracovisko
                    ELSE NULL
                END AS fakulta,
                ROUND((COALESCE(vega.pridelena_dotacia_bv, kega.pridelena_dotacia_bv, apvv.pridelena_dotacia_bv) / 100) * ppd.podiel, 2) AS pomer_pridelena_dotacia_bv_podiel
            FROM
                pro_projekt pp
            LEFT JOIN
                vega ON pp.id_program_projekt = vega.evidencne_cislo
            LEFT JOIN
                kega ON pp.id_program_projekt = kega.evidencne_cislo
            LEFT JOIN
                apvv ON pp.id_program_projekt = apvv.id_projektu
            INNER JOIN
                pro_podiely ppd ON ppd.projekt_id = pp.id_projektu
            INNER JOIN
                zamestnanci zam ON ppd.zamestnanci_id = zam.user_id
            WHERE
                pp.id_program_projekt != 0;
        ");

        // upravime nazvy fakult do normalizovaneho tvaru
        // vynechali sme tu fakultu "Vyberte, prosím" kedže nevieme pod aku fakultu to spadá a sumy v nej boli príliš vysoké
        // robilo to velké odchýlky v grafoch
        $filteredRecords = [];
        foreach ($sql as $record) {
            if ($record->fakulta !== "vyberte, prosím") {
                $record->fakulta = str_replace("UKF", "", $record->fakulta);
                $record->fakulta = rtrim($record->fakulta);
                switch ($record->fakulta) {
                    case "FSVZ":
                        $record->fakulta = "Fakulta sociálnych vied a zdravotníctva";
                        break;
                    case "FF":
                        $record->fakulta = "Filozofická fakulta";
                        break;
                    case "FPVaI":
                        $record->fakulta = "Fakulta prírodných vied a informatiky";
                        break;
                    case "PdF":
                        $record->fakulta = "Pedagogická fakulta";
                        break;
                    case "FSŠ":
                        $record->fakulta = "Fakulta stredoeurópskych štúdií";
                        break;
                }
                $filteredRecords[] = $record;
            }
        }

        return $filteredRecords;


    }

    public function getShareByFaculty()
    {
        /*
         * Táto metóda vypíše štatistiky podielov fakúlt za roky 2020, 2021,2022
         * */

        $records = $this->getAllStatInfo();
//        $vegaRecords = [];
//        $kegaRecords = [];
//        $apvvRecords = [];
//        foreach ($sql as $record) {
//            if ($record->typ == "vega") {
//                $vegaRecords[] = $record;
//            }
//            else if ($record->typ == "kega") {
//                $kegaRecords[] = $record;
//            }
//            else $apvvRecords[] = $record;
//        }

        $sumy_podielov_fakult = [
            '2020' => [],
            '2021' => [],
            '2022' => [],
        ];

        foreach ($records as $record) {
            $fakulta = $record->fakulta;
            $rok = $record->rok;
            $podiel = $record->pomer_pridelena_dotacia_bv_podiel;

            // Inicializácia sumy pre fakultu v danom roku, ak ešte neexistuje
            if (!isset($sumy_podielov_fakult[$rok][$fakulta])) {
                $sumy_podielov_fakult[$rok][$fakulta] = 0;
            }

            // Pridanie hodnoty do sumy pre danú fakultu a rok
            $sumy_podielov_fakult[$rok][$fakulta] += $podiel;
        }
        foreach ($sumy_podielov_fakult as &$sumy) {
            foreach ($sumy as &$suma) {
                $suma = round($suma, 2);
            }
        }
        return $sumy_podielov_fakult;
    }

    public function getShareByAuthors()
    {
        $records = $this->getAllStatInfo();

        $authorShares = []; // Inicializujeme prázdne pole pre uchovávanie informácií o zdieľaní autorov

        // Prechádzame všetky záznamy
        foreach ($records as $record) {
            $authorName = $record->cele_meno; // Získame meno autora
            $shareAmount = $record->pomer_pridelena_dotacia_bv_podiel; // Získame pridelenej sumy autora
            $year = $record->rok; // Získame rok

            // Ak už máme informácie o tomto autorovi, pridáme pridelenej sumy k existujúcemu záznamu
            if (array_key_exists($authorName, $authorShares)) {
                // Ak už máme informácie o tomto roku pre daného autora, pridáme pridelenej sumy k existujúcemu záznamu
                if (array_key_exists($year, $authorShares[$authorName])) {
                    $authorShares[$authorName][$year] += $shareAmount;
                } else { // Ak nemáme ešte informácie o tomto roku pre daného autora, vytvoríme nový záznam pre rok
                    $authorShares[$authorName][$year] = $shareAmount;
                }
            } else { // Ak nemáme ešte informácie o tomto autorovi, vytvoríme nový záznam pre autora aj rok
                $authorShares[$authorName] = [$year => $shareAmount];
            }
        }

        // Vrátime zdieľané sumy autorov
        return $authorShares;
    }




}
