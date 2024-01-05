<?php

namespace App\Http\Controllers;

use Rap2hpoutre\FastExcel\FastExcel;

class TestController extends Controller
{
    /**
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Writer\Exception\WriterNotOpenedException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     * @throws \OpenSpout\Common\Exception\InvalidArgumentException
     */
    public function exportExample()
    {
        $list = collect([
            [ 'id' => 1, 'name' => 'Jane' ],
            [ 'id' => 2, 'name' => 'John' ],
        ]);

        (new FastExcel($list))->export('file.xlsx');
    }
}
