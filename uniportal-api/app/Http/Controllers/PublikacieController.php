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


        // Parsovanie zaznamu publikacie, ziskavanie nazvu prace, autora a podiel
        foreach ($zaznamyPublikacii as $zaznam) {
            // Nájdeme index prvého výskytu značky '[Autor, xx%]' alebo '[Zostavovateľ, xx%]'
            preg_match('/^.*?(?=\[Autor|Zostavovateľ)/', $zaznam['zaznam'], $matches);

            // Ak sme našli prvý výskyt
            if (!empty($matches)) {
                // Získať text pred prvým výskytom autora alebo zostavovateľa
                $textBeforeAuthor = $matches[0];

                // Odstráň všetky výskyty ako [elektronický dokument], [textový dokument], [iný] atď. z $textBeforeAuthor
                $textBeforeAuthor = preg_replace('/\[(elektronický dokument|textový dokument|iný)[^\]]*\]/', '', $textBeforeAuthor);

                // Nájdeme index prvého výskytu znaku '/' alebo ';'
                $slashIndex = strpos($zaznam['zaznam'], '/');
                $semicolonIndex = strpos($zaznam['zaznam'], ';');

                // Nájdeme menší index z týchto dvoch
                if ($slashIndex === false) {
                    $index = $semicolonIndex;
                } elseif ($semicolonIndex === false) {
                    $index = $slashIndex;
                } else {
                    $index = min($slashIndex, $semicolonIndex);
                }

                // Ak sme našli '/' alebo ';'
                if ($index !== false) {
                    // Odstránime časť za '/' alebo ';'
                    $textBeforeSlashOrSemicolon = substr($zaznam['zaznam'], 0, $index);

                    // Odstránime časť pred '/'
                    $textAfterSlash = substr($zaznam['zaznam'], $slashIndex + 1);

                    // Nájdeme všetky výskyty "[Autor, ..." alebo "[Zostavovateľ, ..." v texte
                    preg_match_all('/\[(Autor|Zostavovateľ),[^]]+\]/', $textAfterSlash, $matches);

                    // Ak sme našli nejaké zhody
                    if (!empty($matches[0])) {
                        // Vypíšeme všetky nájdené zhody spolu s časťou zľava
                        foreach ($matches[0] as $match) {
                            echo $textBeforeAuthor . ' ' . $match . "\n";
                        }
                    }
                }
            }
        }


//        foreach ($zaznamyPublikacii as $zaznam) {
//            DB::table('publikacie')->updateOrInsert([
//                'id_publikacie' => $zaznam['id_publikacie'],
//                'nazov' => $zaznam['nazov'],
//                'zaznam' => $zaznam['zaznam'],
//                'link' => $zaznam['link'],
//            ]);
//        }
//
//        return response("Success", 200);
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
