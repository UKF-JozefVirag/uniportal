<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
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

        $result = [];
        foreach ($zaznamyPublikacii as $zaznam) {
            $remainingText = $zaznam['zaznam'];
            $publikacia = [];

            while (preg_match('/\[(Autor|Zostavovateľ),[^]]+\]/', $remainingText, $matches)) {
                $textBeforeAuthor = substr($remainingText, 0, strpos($remainingText, '[' . $matches[1]));
                $textBeforeAuthor = preg_replace('/\[[^\]]*\]/', '', $textBeforeAuthor);
                $autorPodiel = [
                    "meno" => trim(preg_replace('/\[.*\]\s*(\/)?\s*/', '', $textBeforeAuthor))
                ];

                // nadpis bol spojeny s prvym autorom, cize ho osekneme a vyčistíme od znakov
                if (strlen($autorPodiel['meno'] < 0)) break;
                else if (str_contains($autorPodiel['meno'], '/')) {
                    $split = explode('/', $autorPodiel['meno']);
                    $autorPodiel['meno'] = trim(end($split)); // Získa posledný prvok po rozdelení
                }
                else if (str_contains($autorPodiel['meno'], ':')) {
                    $split = explode(':', $autorPodiel['meno']);
                    $autorPodiel['meno'] = trim(end($split)); // Získa posledný prvok po rozdelení
                }
                else if (str_contains($autorPodiel['meno'], ';')) {
                    $autorPodiel['meno'] = str_replace(';', '', $autorPodiel['meno']);
                    $autorPodiel['meno'] = ltrim($autorPodiel['meno']);
                }

                // Vymena pozicie mena a priezviska
                $cele_meno_parts = explode(', ', $autorPodiel['meno']);
                if (count($cele_meno_parts) === 2) {
                    $meno = $cele_meno_parts[1];
                    $priezvisko = $cele_meno_parts[0];
                    $autorPodiel['meno'] = $meno . ' ' . $priezvisko;
                }

                // Extract podiel if available
                preg_match('/\d+%/', $matches[0], $podielMatches);
                if (!empty($podielMatches)) {
                    // Odstrániť znak percent
                    $podiel = str_replace('%', '', $podielMatches[0]);
                    $autorPodiel["podiel"] = $podiel;
                }

                if (!isset($publikacia['nazov_prace'])) {
                    $nazov_autor_split = explode('/', trim($textBeforeAuthor));
                    $publikacia['id_publikacie'] = $zaznam['id_publikacie'];
                    $publikacia['link'] = $zaznam['link'];
                    $publikacia['zaznam'] = $zaznam['zaznam'];
                    $publikacia['nazov_prace'] = rtrim($nazov_autor_split[0]);
                    $publikacia['autori'] = [$autorPodiel];
                } else {
                    $publikacia['autori'][] = $autorPodiel;
                }

                $remainingText = substr($remainingText, strpos($remainingText, '[' . $matches[1]) + strlen($matches[0]));
            }
            $result[] = $publikacia;
        }



        $result = $this->filterAuthorsWithoutShares($result);
//        echo json_encode(["publikacie" => $result], JSON_PRETTY_PRINT);

        foreach ($result as $zaznam) {
            // Kontrola, či kľúč 'id_publikacie' existuje v aktuálnom zázname
            if (isset($zaznam['id_publikacie'])) {
                // Ak existujú autori pre túto publikáciu a pole autorov nie je prázdne
                if (!empty($zaznam['autori'])) {
                    DB::table('publikacie')->updateOrInsert([
                        'id_publikacie' => $zaznam['id_publikacie'],
                        'nazov' => $zaznam['nazov_prace'],
                        'zaznam' => $zaznam['zaznam'],
                        'link' => $zaznam['link'],
                    ]);
                }

                // Prechádzame všetkých autorov publikácie
                foreach ($zaznam['autori'] as $autor) {
                    // Získame ID zamestnanca podľa mena a priezviska
                    $zamestnanecId = DB::table('zamestnanci')
                        ->where('cele_meno', $autor['meno'])
                        ->value('id');

                    // Predvolená hodnota pre nového zamestnanca
                    $newUserId = null;

                    // Ak sme nenašli zamestnanca, vložíme nového zamestnanca do tabuľky 'zamestnanci'
                    if (!$zamestnanecId) {
                        $maxId = DB::table('zamestnanci')->max('id');
                        $newUserId = $maxId + 1;

                        // Vložíme záznam do tabuľky 'zamestnanci'
                        $newUserId = DB::table('zamestnanci')->insertGetId([
                            'user_id' => $newUserId,
                            'meno' => null,
                            'priezvisko' => null,
                            'cele_meno' => $autor['meno'],
                            'rok' => null,
                            'fakulta_id' => null,
                            'email' => null,
                            'password' => null,
                            'validation_key' => null,
                            'rola' => null,
                            'updated_at' => Carbon::now()->toDateTimeString(),
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'verified' => 0
                        ]);
                    } else {
                        $newUserId = $zamestnanecId;
                    }

                    // Vložíme záznam do tabuľky 'publikacie_zamestnanci'
                    DB::table('publikacie_zamestnanci')->updateOrInsert([
                        'zamestnanci_id' => $newUserId,
                        'publikacie_id' => $zaznam['id_publikacie'],
                        'podiel' => $autor['podiel'] ?? null,
                    ]);
                }
            }
        }

        // pridanie to tabulky publikacie_zamestnanci
        // zobrať zaznam['autori'], rozseknuť ho na zaklade , .. prvu a druhu časť swapnuť a medzi to dať medzeru a na zaklade toho hladať
        // ak tam je taky človek, priradiť id, ak nie je, ziskať najvyššie id zamestnancov a priratať mu 1

        return response("Success", 200);
    }

    //vymaže zostavovatelov, kedže zostavovatelia nemaju % podiel ak je tam už autor
    function filterAuthorsWithoutShares($result) {
        foreach ($result as &$publikacia) {
            // Kontrola, či kľúč 'autori' existuje
            if (isset($publikacia['autori'])) {
                foreach ($publikacia['autori'] as $index => $autor) {
                    if (!isset($autor['podiel'])) {
                        unset($publikacia['autori'][$index]);
                    }
                }
                // Reindex the array after removing elements
                $publikacia['autori'] = array_values($publikacia['autori']);
            }
        }
        return $result;
    }

    public function getPublikacie() {
        $publikacie = DB::select('
        SELECT pb.id_publikacie AS "ID Publikácie",
           pb.nazov AS "Názov",
           pb.zaznam AS "Celý záznam",
           pb.link AS "Odkaz",
           zam.cele_meno AS "Meno",
           pbz.podiel AS "Podiel"
        FROM publikacie_zamestnanci pbz
        INNER JOIN publikacie pb ON pb.id_publikacie = pbz.publikacie_id
        INNER JOIN zamestnanci zam ON zam.id = pbz.zamestnanci_id;
        ');
        return $publikacie;
    }
}
