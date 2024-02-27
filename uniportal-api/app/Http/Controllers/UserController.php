<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class UserController extends Controller
{
    /**
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $keygen = random_int(100000, 999999);
        $name = $request->input('meno');
        $odoslat = "";

        try {
            $this->validate($request, [
                'email' => 'required|email|regex:/^[A-Za-z0-9._%+-]+@ukf\.sk$/i|unique:users',
                'password' => 'required',
                'rola_id' => 'required',
            ]);
        } catch (\Exception $e) {
            $odoslat = $e->getMessage();
    }

        $user = new User([
            'email' => $request->input('email'),
            'password' => password_hash($request->input('password'), PASSWORD_BCRYPT),
            'validation_key' => $keygen,
            'rola_id' => $request->input('rola_id'),
            'updated_at' => Carbon::now()->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString(),
            'verified' => 0
        ]);

        $content = "Dobrý deň $name.\n\nVitajte v našom systéme.\n\nPre dokončenie registrácie zadajte tento kľúč:\n\n
        $keygen \n S pozdravom,\n UKF.";

        Mail::raw($content, function ($message) {
            $message
                ->from('testmailukf@gmail.com')
                ->to('jozef.virag10@gmail.com')
                ->subject('Vitajte!');
        });

        $user->save();


        return response()->json($odoslat, 201);
//        return response()->json(['user' => $user], 201);
    }

    public function sendMail()
    {
        return $this->from('testmailukf@gmail.com')
            ->to('jozef.virag10@gmail.com')
            ->subject('Vitajte!')
            ->body('Dobrý deň,\n\nVitajte v našom systéme.\n\nS pozdravom,\nVáš tím');
    }

    public function ripUsers()
    {
//TODO: Dokoncit
        $fakulty = DB::table('fakulta')->get('*');

        $users = (new FastExcel)->import('../../excely/zoznam.xlsx');

        $zamestnanci = array_filter($users->map(function ($item) {
            if (is_numeric($item['Column22'])) {
                return "";
            } else {
                return [
                    'id' => $item['Column1'],
                    'email' => isset($item['Column22']) ? $item['Column22'] . "@ukf.sk" : null,
                ];
            }
        })->toArray());

        DB::table('users')->insert(array_map(function ($item) {
            return [
                'email' => $item['email'],
                'password' => password_hash('123456', PASSWORD_BCRYPT),
                'validation_key' => random_int(100000, 999999),
                'rola_id' => 1,
                'updated_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString(),
                'verified' => 0,
            ];
        }, $zamestnanci));
    }
}

//        return $users->get(1);

//        return $users;




