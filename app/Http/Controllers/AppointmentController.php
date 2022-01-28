<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Formation;
use App\Models\Rank;
use App\Models\State;
use Illuminate\Http\Request;
use Milon\Barcode\DNS2D;
use Rap2hpoutre\FastExcel\FastExcel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class AppointmentController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    // DISPLAY APPOINTMENT CAT
    public function manage(){
        if(auth()->user()->service_number !== 66818){
            return redirect()->back();
        }
        return view('dashboard.appointment.manage');
    }

    // DISPLAY JNR PROMOTION LIST
    public function manage_appointment(Request $request, $year){
        // return Appointment::where('year', $year)->orderBy('updated_at', 'DESC')->get();
        if(auth()->user()->service_number !== 66818){
            return redirect()->back();
        }
        $formations = Formation::all();
        $ranks = Rank::get(['full_title', 'short_title']);
        $states = State::all();
        return view('dashboard.appointment.all', compact(['year','formations', 'states', 'ranks']));
    }

    // GET PROMOTION LIST
    public function get_all($year){

        $appointment = Appointment::where('year', $year)->orderBy('updated_at', 'DESC')->get();
        return DataTables::of($appointment)
        // ->editColumn('updated_at', function ($appointment) {
        //     return $appointment->updated_at->toFormattedDateString();
        //     // return $redeployment->created_at->toDateString();
        // })
        ->addColumn('view', function($appointment) {
            return '
                <a href="'.route('generate_single_appointment_letter', $appointment->id).'" style="margin-right:5px;" class="light-blue-text text-darken-3" title="Print promotion letter"><i class="fas fa-file-word fa-lg"></i></a>
            ';
        })
        ->addColumn('checkbox', function($redeployment) {
            return '<input type="checkbox" name="personnelCheckbox[]" class="personnelCheckbox browser-default" value="'.$redeployment->id.'" />';
        })
        ->rawColumns(['view', 'checkbox'])
        ->make();
    }

    // IMPORT STORE IMPORTED PROMOTION DATA
    public function store_imported_promotion(Request $request)
    {  
        $request->validate([
            'import_file' => 'required'
        ]);
        
        $path = $request->file('import_file')->getRealPath();
        $data = (new FastExcel)->import($path);
        // \dd($data[0]);
        if($data->count()){

            $candidates = (new FastExcel)->import($path, function ($line) {
                $line['tsa'] == '' ? $tsa = null : $tsa = $line['tsa'];
                $line['num'] == '' ? $num = null : $num = $line['num'];
                $line['application_code'] == '' ? $application_code = null : $application_code = $line['application_code'];
                $line['name'] == '' ? $name = null : $name = $line['name'];
                $line['email'] == '' ? $email = null : $email = $line['email'];
                $line['date_of_birth'] == '' ? $date_of_birth = null : $date_of_birth = $line['date_of_birth'];
                $line['mobile_number'] == '' ? $mobile_number = null : $mobile_number = $line['mobile_number'];
                $line['gender'] == '' ? $gender = null : $gender = $line['gender'];
                $line['position'] == '' ? $position = null : $position = $line['position'];
                $line['state'] == '' ? $state = null : $state = $line['state'];
                $line['lga'] == '' ? $lga = null : $lga = $line['lga'];
                $line['time'] == '' ? $time = null : $time = $line['time'];
                $line['date'] == '' ? $date = null : $date = $line['date'];
                $line['day'] == '' ? $day = null : $day = $line['day'];
                $line['amount'] == '' ? $amount = null : $amount = $line['amount'];
                $line['id_number'] == '' ? $id_number = null : $id_number = $line['id_number'];
                
                $effective_date = date('d-m-Y', strtotime($date));
                $year = explode('-', $effective_date)[2];

                $rank_applied = Rank::where('full_title', $position)->first();


                $dNS2D = new DNS2D();

                $candidate = Appointment::updateOrInsert(
                    ['id_number' => $id_number],
                    [
                    'tsa' => $tsa,
                    'num' => $num,
                    'application_code' => $application_code,
                    'name' => ucwords($name),
                    'email' => $email,
                    'date_of_birth' => $date_of_birth,
                    'mobile_number' => $mobile_number,
                    'gender' => $gender,
                    'position' => $position,
                    'state' => $state,
                    'lga' => $lga,
                    'year' => $year,
                    'time' => $time,
                    'date' => $date,
                    'day' => $day,
                    'amount' => $amount,
                    'id_number' => $id_number,
                    'barcode' => $dNS2D->getBarcodePNG("<b>Authentic!</b> find full details of <b>$name</b> here --> <br/>http://admindb.nscdc.gov.ng/verify/appointment/$year/$id_number", 'QRCODE', 10,10),
                    ]
                );
            });
            Alert::success('Promotion records imported successfully!', 'Success!')->autoclose(222500);
            return back();
        }
    }

    // GENERATE SINGLE PROMOTION LETTER
    public function generate_single_appointment_letter(Appointment $candidate){
        // return $candidate;
        // return "<img src=\"data:image/png;base64,$redeployment->barcode\" alt=\"barcode\" />";
        // if(strtolower($candidate->position) == 'corps assistant i'){
        //     $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template-cai.docx'));
        // }else if(strtolower($candidate->position) == 'corps assistant ii'){
        //     $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template-caii.docx'));
        // }else if(strtolower($candidate->position) == 'corps assistant iii'){
        //     $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template-caiii.docx'));
        // }else if(strtolower($candidate->position) == 'assistant inspector of corps'){
        //     $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template-aic.docx'));
        // }
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(resource_path('docs/template-universal.docx'));
        $gl = Rank::where('full_title', $candidate->position)->pluck('gl')->first();
        $templateProcessor->setValue('tsa', $candidate->tsa);
        $templateProcessor->setValue('name', strtoupper($candidate->name));
        $templateProcessor->setValue('state', strtoupper($candidate->state));
        $templateProcessor->setValue('position', strtoupper($candidate->position));
        $templateProcessor->setValue('gl', $gl);
        $templateProcessor->setValue('amount', number_format($candidate->amount, 2));
        $templateProcessor->setImageValue('barcode', "data:image/png;base64,$candidate->barcode");
        $templateProcessor->setValue('id_number', $candidate->id_number);
        
        // $templateProcessor->setImageValue('barcode', "data:image/png;base64,$candidate->barcode");
        $templateProcessor->saveAs(storage_path('app/docs/'.$candidate->name.'.docx'));
        return response()->download(storage_path('app/docs/'.$candidate->name.'.docx'));
    }

    // GENERATE BULK PROMOTION LETTERS
    public function generate_bulk_junior_promotion_letter(Request $request){

        // $candidates = Promotion::orderByRaw("FIELD(promotion_rank_full, 'Inspector of Corps', 'Assistant Inspector of Corps', 'Chief Corps Assistant', 'Senior Corps Assistant', 'Corps Assistant I', 'Corps Assistant II', 'Corps Assistant III')")->find($request->candidates);

        // $phpWord = new \PhpOffice\PhpWord\PhpWord();
        // $phpWord->setDefaultFontName('Times New Roman');
        // $phpWord->setDefaultFontSize(14);

        // foreach($candidates as $candidate){

        //     $current = Carbon::now();
        //     // $currentDate = $current->format('jS F, Y');
        //     $currentDate = '25th November, 2021';
        //     $image = DNS2DFacade::getBarcodePNG("<b>Authentic!</b> find full details of <b>$candidate->name</b> here --> <br/>http://admindb.nscdc.gov.ng/verify/promotion/jnr/$candidate->year/$candidate->ref_number", 'QRCODE', 50,50);
        //     $image = str_replace('data:image/png;base64,', '', $image);
        //     $image = str_replace(' ', '+', $image);
        //     $imageName = "Promotion QR Code_$candidate->svc_no.png";
        //     File::put(storage_path().'/app/docs/'.$imageName, base64_decode($image));

        //     // PAGE CONTENT WRAPPER
        //     $section = $phpWord->addSection([
        //         'orientation' => 'portrait', 
        //         'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.45), 
        //         'marginRight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.68), 
        //         'marginTop' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.70), 
        //         'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1),
        //         'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(0.1)
        //     ]);
        //         // $section->addTextBreak(4);

        //         // REFERENCE NUMBER AND DATE ////////////////////////////////////////////////
        //         $table = $section->addTable(['width' => 100 * 50, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);
        //         $table->addRow();

        //         $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(3), ['valign' => 'bottom'])->addText("NSCDC/NHQ/JP/".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year."/$candidate->svc_no", ['size' => 14, 'regular' => true, 'bold' => true]);

        //         $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(2.8))->addText("$currentDate", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
        //         $section->addTextBreak(1);

        //         // $section->addText("NSCDC/NHQ/JP/".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year."/$candidate->svc_no", null, [ 'spaceAfter' => 0, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
                
        //         // $section->addText("$currentDate", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::END ]);
        //         // $section->addTextBreak(1);
                
        //         $section->addText("$candidate->name ".strtoupper("($candidate->present_rank_short)"), ['bold' => true]);
        //             $state = $candidate->command;
        //             if($candidate->command == 'Zone A Headquarters'){
        //                 $state = 'Lagos';
        //             }else if($candidate->command == 'Zone B Headquarters'){
        //                 $state = 'Kaduna';
        //             }else if($candidate->command == 'Zone C Headquarters'){
        //                 $state = 'Bauchi';
        //             }else if($candidate->command == 'Zone D Headquarters'){
        //                 $state = 'Minna, Niger';
        //             }else if($candidate->command == 'Zone E Headquarters'){
        //                 $state = 'Owerri, Imo';
        //             }else if($candidate->command == 'Zone F Headquarters'){
        //                 $state = 'Abeokuta';
        //             }else if($candidate->command == 'Zone G Headquarters'){
        //                 $state = 'Benin City, Edo';
        //             }else if($candidate->command == 'Zone H Headquarters'){
        //                 $state = 'Makurdi, Benue';
        //             }else if($candidate->command == 'Zone I Headquarters'){
        //                 $state = 'Damaturu, Yobe';
        //             }else if($candidate->command == 'Zone J Headquarters'){
        //                 $state = 'Osun';
        //             }else if($candidate->command == 'Zone K Headquarters'){
        //                 $state = 'Awka, Anambra';
        //             }else if($candidate->command == 'Zone L Headquarters'){
        //                 $state = 'Port Harcourt, Rivers';
        //             }else if($candidate->command == 'Zone M Headquarters'){
        //                 $state = 'Sokoto';
        //             }else if($candidate->command == 'Zone N Headquarters'){
        //                 $state = 'Kano';
        //             }else if($candidate->command == 'Zone O'){
        //                 $state = 'FCT, Abuja';
        //             }
                    
        //         if($candidate->command_type == 'state'){
        //             $section->addText("Ufs:  The State Commandant,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         ".ucwords($candidate->command)." State Command,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         ".ucwords($state)." state.");
        //         }
        //         elseif($candidate->command_type == 'zone'){
        //             $section->addText("Ufs:  The Zonal Commander,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         ".ucwords($candidate->command).",", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         ".ucwords($state)." state.");
        //         }
        //         elseif($candidate->command_type == 'sa'){
                    
        //             $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         FCT, Abuja.");
        //         }
        //         elseif($candidate->command_type == 'kc'){
        //             $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         ".ucwords($candidate->command).",", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Katsina state.");
        //         }
        //         elseif($candidate->command_type == 'oc'){
        //             $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Ogun state.");
        //         }
        //         elseif($candidate->command_type == 'll'){
        //             $section->addText("Ufs:  The Provost,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         $candidate->command,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Lagos state.");
        //         }
        //         elseif($candidate->command_type == 'nhq'){
        //             $section->addText("Ufs:  Deputy Commandant General Administration,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         National Headquarters,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Sauka, Fct Abuja.");
        //         }elseif($candidate->command_type == 'fct'){
        //             $section->addText("Ufs:  The Commandant,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         ".ucfirst($candidate->command)." Command,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Zone 5, Fct Abuja.");
        //         }
        //         elseif($candidate->command_type == 'elo'){
        //             $section->addText("Ufs:  The Commandant,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Nigeria Security and Civil Defence Corps,", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         ".ucfirst($candidate->command).",", null, [ 'spaceAfter' => 0]);
        //             $section->addText("         Zone 5, Fct Abuja.");
        //         }
        //         $section->addTextBreak(1, ['size' => 8]);

        //         // TITLE HERE ////////////////////////////////////////////////
        //         $section->addText("LETTER OF PROMOTION", ['bold' => true, 'underline' => 'single'], [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);

        //         // BODY OF LETTER //////////////////////////////////////////////////////////////////
        //         $fisrtPara = $section->addTextRun(['lineHeight' => 1.5, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
        //         $fisrtPara->addText("I am pleased to inform you that sequel to your performance at the ".Carbon::createFromFormat('d-m-Y', $candidate->effective_date)->year." promotion examination, the Commandant General has approved your promotion from the rank of", null, [ 'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH ]);
        //         $fisrtPara->addText(" $candidate->present_rank_full ($candidate->present_rank_short)", ['bold' => true]);
        //         $fisrtPara->addText(" on");
        //         $fisrtPara->addText(" (CONPASS 0$candidate->present_gl)", ['bold' => true]);
        //         $fisrtPara->addText(" to the rank of");
        //         $fisrtPara->addText(" $candidate->promotion_rank_full ($candidate->promotion_rank_short)", ['bold' => true]);
        //         $fisrtPara->addText(" on");
        //         $fisrtPara->addText(" (CONPASS 0$candidate->promotion_gl)", ['bold' => true]);
        //         $fisrtPara->addText(" with effect from");
        //         $fisrtPara->addText(" ".date('d/m/Y', strtotime($candidate->effective_date)).".", ['bold' => true]);

        //         $section->addText('2.       Notice of the promotion will be published in the official gazette soon.', 
        //         null, 
        //         [
        //              'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH 
        //         ]);

        //         $section->addText('3.       Please accept my hearty congratulations.', 
        //         null, 
        //         [ 
        //             'align' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH 
        //         ]);
        //         $section->addTextBreak(1, ['size' => 14]);
        //         $section->addTextBreak(1, ['size' => 14]);
                
        //         $section->addImage(storage_path().'/app/docs/SVG/cc_admin_sign.png', [
        //             'width' => 240,
        //             'wrappingStyle' => 'infront',
        //             'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
        //             'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
        //             'posVertical'    => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_CENTER,
        //             'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_MARGIN,
        //             'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
        //             'margin-top' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(-12.85)
        //         ]);
                
        //         // FOOTER SIGNATURE //////////////////////////////////////////////////////////////////
        //         $section->addText('ADAMU SALIHU', ['bold' => true], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
        //         $section->addText('Commandant Administration', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
        //         $section->addText('For: Commandant General', [], [ 'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::AUTO, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER ]);
        //         $section->addTextBreak(1, ['size' => 4]);

        //         $section->addImage(storage_path()."/app/docs/Promotion QR Code_$candidate->svc_no.png", ['width' => 80, 'height' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);

                
        //         $footer = $section->addFooter();
        //         $footer->addText("Please ensure QR Code scanning to authenticate the genuineness of this letter.", ['name' => 'calibri', 'size' => 12, 'italic' => true, 'bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        //         $section->addTextBreak(1, ['size' => 8]);

        // }

        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        // $objWriter->save(storage_path('app/docs/junior_promotion_letter.docx'));
        // return response()->download(storage_path('app/docs/junior_promotion_letter.docx'));
    }



}
