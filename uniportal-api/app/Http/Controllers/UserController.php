<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
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

    public function sendMail(){
        return $this->from('testmailukf@gmail.com')
            ->to('jozef.virag10@gmail.com')
            ->subject('Vitajte!')
            ->body('Dobrý deň,\n\nVitajte v našom systéme.\n\nS pozdravom,\nVáš tím');
    }

}
