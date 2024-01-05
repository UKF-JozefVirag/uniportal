<?php

namespace App\Http\Controllers;

use Rap2hpoutre\FastExcel\FastExcel;

class TestController extends Controller
{
    public function exportExample()
    {
        // nacita udaje z csv a nastavi im headers (csv nema headers)
        $headers = ['zamestnanec_id', 'podiel', 'rok', 'projekt_id', 'nazov', 'grant_program_id', 'acronym', 'priezvisko', 'katedra', 'fakulta', 'full_expenditures'];
        $file = fopen('../../excely/employees_projects2021.csv', 'r');
        $data = [];
        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $data[] = array_combine($headers, $row);
        }

        fclose($file);
        return $data;
    }
}
