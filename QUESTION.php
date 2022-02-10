I have a laravel webApp that I built to deployed in the following ways.
The 1st version of the app is setup locally on xampp on multiple computers, this is to make it easy for us to collect data and fill a personnel form without any need for internet connection.

an almost exact copy of the webApp is deployed on a cloud server, and in this version I opened up an api route that receives post requests from the various local installations.

This way I am able to push all records from the various local setups to the cloud server through the api route I opened.

So on click of a button on from the local webApp, and axios get request is fired, that fetches all users from the User's Model along with all relationships and is stored in an array variable, then I will loop through the array of users and foreach I will send a post request to my cloud webApp, then on the cloud controller, i will store the user in the cloud User's model along with all of its relationships, then I return a json response with a status of 1 along with the inserted user record.

Then back on my local setup, when I get the response from my cloud server, I check the response status if it is 1 then I will send another another axios put request to update my local user's record and change the 'synched' column of that user to 1.

for the most part it works as I planned, but I noticed some errors on the final axios request to update the 'synched' column, for most it goes fine while some return **419 error** with the message **'CSRF token mismatch'** and a few **500 error** with the message **'Server Error'**.

Find below my code implementation.

**Local Server Routes**

    Route::get('/unsynched', [PersonnelController::class, 'unsynched'])->name('personnel_unsynched');
	Route::put('/synched', [SyncController::class, 'synched'])->name('synched_personnel');

**Local server Controller Methods**

    // GET ALL UNSYNCHED USERS
    public function unsynched(){
        $users = User::whereDate('dofa', '2019-01-01')->where('synched', 0)->with(['noks', 'children', 'progressions', 'qualifications'])->get();
        return response()->json($users);
    }
    // MARK USER SYNCHED COLUMN AS 1
    public function synched(Request $request, User $user){
        return $user->update(['synched' => 1]);
    }

**Local Server Blade Javascript code**

    // LOADS UP ALL UNSYNCHED USERS
        async function get_unsynched(){
            const response = await axios.get(`{!! route('personnel_unsynched') !!}`)
            return response.data
        }
    // PUSH UNSYNCHED RECORDS TO CLOUD
            $('#sync').click(async () => {
               
                let users = []
                await get_unsynched().then((resp_users) => {
                    users = resp_users
                })

                $('#modal1 > .modal-content > .progress > .determinate').attr('class', 'indeterminate')
           
                users.forEach(async function (value, index, array) {
                   
                    await axios.post('http://admin.nscdc.gov.ng/api/personnel/sync', {

                        user: {
                            ...value,
                            _token: `{!! csrf_token() !!}`
                        }

                    }).then(async (res) => {

                        // CHECKS IF RECORD IS STORED IN THE CLOUD
                        if(res.data.status){
                            console.log(value);
                            // MARKS LOCAL RECORD AS SYNCHED
                            await axios.put(`{!! route('synched_personnel') !!}`, {
                                user: {
                                    ...res.data.user,
                                    _token: `{!! csrf_token() !!}`
                                }
                                    
                            }).then((value) => {
                                // CHECKS IF LOCAL RECORD IS MARKED SUCCESSFULLY
                                console.log(value);
                                if(value){
                                    $('#modal1 > .modal-content > .count').html(`${index+1}/${users.length}`)
                                    if(users.length == index+1){
                                        $('#modal1 > .modal-content > .progress > .indeterminate').attr('class', 'determinate')
                                        $('#modal1 > .modal-content > .progress > .determinate').attr('style', 'width:100%')
                                        $('#sync').attr('disabled', true)
                                    }
                                }
                            })

                        }else{
                            $('#modal1 > .modal-content > .progress > .indeterminate').attr('class', 'determinate')
                            $('#modal1 > .modal-content > .progress > .determinate').attr('style', 'width:0%')
                        }
                        
                    })
                })
               
            })

**Cloud server api route**

    Route::post('/personnel/sync', [SyncController::class, 'store'])->name('user_sync');

**Cloud server Controller store function**

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

**Below is the result**

[![enter image description here][1]][1]
 [![enter image description here][2]][2]
[![enter image description here][3]][3]
[![enter image description here][4]][4]


  [1]: https://i.stack.imgur.com/44udr.png
  [2]: https://i.stack.imgur.com/jlt5Q.png
  [3]: https://i.stack.imgur.com/md4tA.png
  [4]: https://i.stack.imgur.com/wNSUT.png