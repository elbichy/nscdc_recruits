<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Rank;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SyncController extends Controller
{
    // CONSTRUCTOR
    // public function __construct()
    // {
    //     $this->middleware(['auth']);
    // }

    // GET ALL UNSYNCHED PERSONNEL
    public function store(Request $request){

        try {
            $personnel = User::updateOrCreate(
                ['service_number' => $request->user['service_number']],
                [
                'username' => $request->user['service_number'],
                'name' => $request->user['name'],
                'dob' => $request->user['dob'],
                'sex' => $request->user['sex'],
                'marital_status' => $request->user['marital_status'],
                'date_of_marriage' => $request->user['date_of_marriage'],
                'name_of_spouse' => $request->user['name_of_spouse'],
                'religion' => $request->user['religion'],
                'blood_group' => $request->user['blood_group'],
                'genotype' => $request->user['genotype'],
                'height' => $request->user['height'],
                'weight' => $request->user['weight'],
                'soo' => $request->user['soo'],
                'lgoo' => $request->user['lgoo'],
                'place_of_birth' => $request->user['place_of_birth'],
                'residential_address' => $request->user['residential_address'],
                'permanent_address' => $request->user['permanent_address'],
                'phone_number' => $request->user['phone_number'],
                'email' => $request->user['email'],
                'cadre' => $request->user['cadre'],
                'gl' => $request->user['gl'],
                'step' => $request->user['step'],
                'rank_full' => $request->user['rank_full'],
                'rank_short' => $request->user['rank_short'],
                'service_number' => $request->user['service_number'],
                'password' => Hash::make($request->user['service_number'].$request->user['phone_number']),
                'dofa' => $request->user['dofa'],
                'doc' => $request->user['doc'],
                'dopa' => $request->user['dopa'],
                'bank' => $request->user['bank'],
                'account_number' => $request->user['account_number'],
                'bvn' => $request->user['bvn'],
                'paypoint' => $request->user['paypoint'],
                'salary_structure' => $request->user['salary_structure'],
                'nin_number' => $request->user['nin_number'],
                'nhis_number' => $request->user['nhis_number'],
                'ippis_number' => $request->user['ippis_number'],
                'nhf' => $request->user['nhf'],
                'pfa' => $request->user['pfa'],
                'pen_number' => $request->user['pen_number'],
                'current_formation' => $request->user['current_formation'],
                'passport' => $request->user['passport']
            ]);

            $formation = Formation::where('formation', $request->user['current_formation'])->first();

            if ($personnel) {
                if (count($request->user['children']) > 0) {
                    foreach ($request->user['children'] as $key => $child) {
                        $personnel->children()->create([
                            'name' => $child['name'],
                            'sex' => $child['sex'],
                            'dob' => $child['dob']
                        ]);
                    }
                }

                if (count($request->user['noks']) > 0) {
                    foreach ($request->user['noks'] as $key => $nok) {
                        $personnel->noks()->create([
                            'name' => $nok['name'],
                            'relationship' => $nok['relationship'],
                            'address' => $nok['address'],
                            'phone' => $nok['phone'],
                        ]);
                    }
                }

                if (count($request->user['progressions']) > 0) {
                    foreach ($request->user['progressions'] as $key => $progressions) {
                        $personnel->progressions()->create([
                            'type' => 'Entry Rank',
                            'cadre' => $progressions['cadre'],
                            'gl' => $progressions['gl'],
                            'rank_full' => $progressions['rank_full'],
                            'rank_short' => $progressions['rank_short'],
                            'effective_date' => $progressions['effective_date']
                        ]);
                    }
                }

                if (count($request->user['qualifications']) > 0) {
                    foreach ($request->user['qualifications'] as $key => $qualification) {
                        $personnel->qualifications()->create([
                            'qualification' => $qualification['qualification'],
                            'course' => $qualification['course'],
                            'institution' => $qualification['institution'],
                            'grade' => $qualification['grade'],
                            'year_commenced' => $qualification['year_commenced'],
                            'year_obtained' => $qualification['year_obtained'],
                        ]);
                    }
                }

                $personnel->formations()->attach($formation->id, [
                    'command' => $formation->formation
                ]);
            }
            return response()->json(['status'=> true, 'user'=> $personnel]);

        }
        catch(Exception $e){
            return response()->json(['status'=> false, 'message'=> $e->getMessage()]);
        }
    }
    // MARK USER SYNCHED COLUMN AS 1
    public function synched(Request $request){
        try {

            $user = User::where('service_number', $request->service_number)->first();
            $synched = $user->update(['synched' => 1]);
            return $synched;

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
