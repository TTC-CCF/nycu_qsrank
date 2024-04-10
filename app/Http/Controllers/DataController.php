<?php

namespace App\Http\Controllers;

use App\Models\Employer_list;
use App\Models\Scholar_list;
use App\Models\Subject;
use App\Models\User;
use App\Models\Academy;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DataController extends Controller
{
    public $output;
    function __construct(){
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }
    public function edit(Request $request){
        $mode = Session::get('list_mode');
        $key = $request->input('key');
        $new_data = $request->input('new_data');
        $sn = $request->input('SN');
        $isCheckbox = $request->input('isCheckbox');

        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $this->output->writeln($request);
        if ($isCheckbox) {
            $year = substr($key, 0, 4);
            $table->updateYearResult($sn, $year, $new_data);
        } elseif ($key === '資料提供單位') {
            $table->updateUnitno($sn, $new_data);
        } else {
            $table->updateTextData($sn, $key, $new_data);
        }
    }

    public function edit_bsa_ms(Request $request){
        $mode = Session::get('list_mode');
        $new_bsa = $request->input('new_bsa');
        $new_ms = $request->input('new_ms');
        $sn = $request->input('SN');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $this->output->writeln($request);
        try{
            $table::where('SN', $sn)->update(['BroadSubjectArea' => $new_bsa, 'MainSubject' => $new_ms]);
        } catch(Exception $err){
            $this->output->writeln($err);
        }
    }
    public function add(Request $request){
        $mode = Session::get('list_mode');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $unitno = Session::get('id');
        $admin = $unitno == 0;
        $this->output->writeln($request);

        try {
            $table->addRecord($unitno, $request, $admin);
            return redirect()->route('list')->with('status', 'Successfully add '.$mode.' data');

        } catch (Exception $err) {
            return redirect()->route('list')->with('status', 'Failed to add '.$mode.' data');
        }
    }
    public function import(Request $request){
        $mode = Session::get('list_mode');
        $unitno = Session::get('id');
        $unit = Academy::where('Academy_No', $unitno)->value('Academy_Name');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        
        //get the body of request
        $body = $request->getContent();
        $this->output->writeln($body);
        $data = json_decode($body)->data;
        $import_mode = json_decode($body)->mode;

        if ($import_mode == 'cover'){
            try {
                if ($unitno == 0){
                    $table::truncate(); // truncate() will commit current transaction
                }
                else{
                    DB::beginTransaction();
                    $table::where('unitno', $unitno)->delete();
                    DB::commit();
                }
            }
            catch (Exception $err) {
                $this->output->writeln($err);
                DB::rollback();
                return response()->json(['status' => 'fail', 'message' => 'Failed to import, please try again'], 200);
            }
        }

        try{
            DB::beginTransaction();
            $max_sn = $table::query()->max('SN')+1;
            foreach($data as $row){
                $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;

                $table->SN = $max_sn;
                $table->year = date('Y');
                if ($unitno == 0){
                    $no = Academy::where('Academy_Name', $row[1])->value('Academy_No');
                    $table->unitno = $no;
                }
                else if ($row[1] != $unit)
                    throw new Exception('Data is not belong to this unit');
                else
                    $table->unitno = $unitno;
                
                $table->資料提供單位 = $row[1];
                $table->資料提供者 = $row[2];
                $table->資料提供者Email = $row[3];
                $table->Title = $row[4];
                $table->First_name = $row[5];
                $table->Last_name = $row[6];
                $table->Chinese_name = $row[7];
                if ($mode == 'scholar'){
                    $table->Job_title = $row[8];
                    $table->Department = $row[9];
                    $table->Institution = $row[10];
                    $table->Country = $row[13];
                }
                else{
                    $table->Position = $row[8];
                    $table->Industry = $row[9];
                    $table->CompanyName = $row[10];
                    $table->Location = $row[13];
                }
                $table->BroadSubjectArea = $row[11];
                $table->MainSubject = $row[12];
                $table->Email = $row[14];
                $table->Phone = $row[15];
                $table->寄送Email日期 = $row[16];
                $table->今年是否同意參與QS = null;
                $table->去年是否同意參與QS = $row[17] == null ? null : true;
            
                $table->save();
                $max_sn += 1;
            }
            DB::commit();
        }
        catch (Exception $err){
            $this->output->writeln($err);
            DB::rollback();
            return response()->json(['status' => 'fail', 'message' => 'Failed to import, please try again'], 200);
        }
        return response()->json(['status' => 'success', 'message' => 'Successfully import data'], 200);
    }
    public function delete(Request $request){
        try {
            DB::beginTransaction();
            $sn_list = $request->input('sn_list');
            $mode = Session::get('list_mode');
            $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
            $deleted = $table::whereIn('SN', $sn_list)->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Successfully delete data'], 200);
        } 
        catch (Exception $err){
            $this->output->writeln($err);
            return response()->json(['status' => 'fail', 'message' => 'Failed to delete, please try again'], 200);
        }
    }
    public function showAdd($mode){
        Session::put('list_mode', $mode);
        $unitno = Session::get('id');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $view_name = ($mode == 'scholar') ? 'addScholarData' : 'addEmployerData';
        $new_sn = $table->query()->max('SN');
        $academy_list = null;
        $bsa_list = Subject::pluck('BroadSubjectArea')->unique();

        if ($unitno == 0){
            $unit_name = 'Admin';
            $unit_email = 'Admin';
            $academy_list = Academy::pluck('Academy_Name');
        }
        else{
            $unit_name = Academy::where('Academy_No', $unitno)->get(['Academy_Name'])[0]['Academy_Name'];
            $unit_email = User::where('unitno', $unitno)->get(['email'])[0]['email'];
        }

        // retrieve subject data
        $subjects = Subject::all();
        $ms_dict = [];

        foreach ($subjects as $subject) {
            $broadSubjectArea = $subject->BroadSubjectArea;
            $mainSubject = $subject->MainSubject;

            if (!isset($ms_dict[$broadSubjectArea])) {
                $ms_dict[$broadSubjectArea] = [];
            }
            $ms_dict[$broadSubjectArea][] = $mainSubject;
        }

        // retrieve title data
        $titles = DB::table('Title')->where('belongs_to', $mode)->get(['name'])->toArray();

        // retrieve industry data
        $industries = DB::table('Industry')->get(['name'])->toArray();

        // retieve country data
        $countries = DB::table('Country')->get(['name'])->toArray();

        $titles = array_map(function ($title) {
            return $title->name;
        }, $titles);
        $industries = array_map(function ($industry) {
            return $industry->name;
        }, $industries);
        $countries = array_map(function ($country) {
            return $country->name;
        }, $countries);

        return view(
            $view_name,
            [
                'maxsn' => $new_sn+1,
                'unit' => $unit_name,
                'unitemail' => $unit_email,
                'academy_list' => $academy_list,
                'bsa_list' => $bsa_list,
                'ms_dict' => $ms_dict,
                'titles' => $titles,
                'industries' => $industries,
                'countries' => $countries
            ]);
    }
    public function showImport ($mode) {
        Session::put('list_mode', $mode);
        $unitno = Session::get('id');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $view_name = ($mode == 'scholar') ? 'importScholarData' : 'importEmployerData';
        $new_sn = $table::query()->max('SN');
        $academy_list = null;
        $bsa_list = Subject::pluck('BroadSubjectArea')->unique();

        if ($unitno == 0){
            $unit_name = 'Admin';
            $unit_email = 'Admin';
            $academy_list = Academy::pluck('Academy_Name');
        }
        else{
            $unit_name = Academy::where('Academy_No', $unitno)->get(['Academy_Name'])[0]['Academy_Name'];
            $unit_email = User::where('unitno', $unitno)->get(['email'])[0]['email'];
        }

        $subjects = Subject::all();
        $ms_dict = [];

        foreach ($subjects as $subject) {
            $broadSubjectArea = $subject->BroadSubjectArea;
            $mainSubject = $subject->MainSubject;

            if (!isset($ms_dict[$broadSubjectArea])) {
                $ms_dict[$broadSubjectArea] = [];
            }
            $ms_dict[$broadSubjectArea][] = $mainSubject;
        }

        return view(
            $view_name,
            [
                'maxsn' => $new_sn+1,
                'unit' => $unit_name,
                'unitemail' => $unit_email,
                'academy_list' => $academy_list,
                'bsa_list' => $bsa_list,
                'ms_dict' => $ms_dict
            ]);
    }
}
