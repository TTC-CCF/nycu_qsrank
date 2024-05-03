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
    function __construct()
    {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
    }
    public function edit(Request $request)
    {
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
        } elseif ($key === '資料提供學院') {
            $table->updateUnitno($sn, $new_data);
        } else {
            $table->updateTextData($sn, $key, $new_data);
        }
    }

    public function edit_bsa_ms(Request $request)
    {
        $mode = Session::get('list_mode');
        $new_bsa = $request->input('new_bsa');
        $new_ms = $request->input('new_ms');
        $sn = $request->input('SN');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;

        try {
            $table->updateTextData($sn, 'Broad Subject Area', $new_bsa);
            $table->updateTextData($sn, 'Main Subject', $new_ms);
        } catch (Exception $err) {
            $this->output->writeln($err);
        }
    }
    public function add(Request $request)
    {
        $mode = Session::get('list_mode');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $unitno = Session::get('id');
        $admin = $unitno == 0;
        $this->output->writeln($request);

        try {
            $table->addRecord($unitno, $request, $admin);
            return redirect()->route('list')->with('status', 'Successfully add ' . $mode . ' data');

        } catch (Exception $err) {
            $this->output->writeln($err);
            return redirect()->route('list')->with('status', 'Failed to add ' . $mode . ' data');
        }
    }
    public function import(Request $request)
    {
        $mode = Session::get('list_mode');
        $unitno = Session::get('id');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;

        // get the body of request

        $body = json_decode($request->getContent());
        $data = $body->data;

        try {
            $table->importData($unitno, $data);
            return response()->json(['status' => 'success', 'message' => 'Successfully import data'], 200);

        } catch (Exception $err) {
            $this->output->writeln($err);
            return response()->json(['status' => 'fail', 'message' => $err->getMessage()], 200);

        }
    }
    public function delete(Request $request)
    {
        try {
            $mode = Session::get('list_mode');
            $sn_list = $request->input('sn_list');
            $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
            $table->deleteMultipleRecords($sn_list);
            return response()->json(['status' => 'success', 'message' => 'Successfully delete data'], 200);
        } catch (Exception $err) {
            $this->output->writeln($err);
            return response()->json(['status' => 'fail', 'message' => 'Failed to delete, please try again'], 200);
        }
    }
    public function showAdd($mode)
    {
        Session::put('list_mode', $mode);
        $unitno = Session::get('id');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $view_name = ($mode == 'scholar') ? 'addScholarData' : 'addEmployerData';
        $new_sn = $table->query()->max('SN');
        $academy_list = null;
        $bsa_list = Subject::pluck('BroadSubjectArea')->unique();

        if ($unitno == 0) {
            $unit_name = 'Admin';
            $unit_email = 'Admin';
            $academy_list = Academy::pluck('Academy_Name');
        } else {
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
                'maxsn' => $new_sn + 1,
                'unit' => $unit_name,
                'unitemail' => $unit_email,
                'academy_list' => $academy_list,
                'bsa_list' => $bsa_list,
                'ms_dict' => $ms_dict,
                'titles' => $titles,
                'industries' => $industries,
                'countries' => $countries
            ]
        );
    }
    public function showImport($mode)
    {
        Session::put('list_mode', $mode);
        $unitno = Session::get('id');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $view_name = ($mode == 'scholar') ? 'importScholarData' : 'importEmployerData';
        $academy_list = null;
        $bsa_list = Subject::pluck('BroadSubjectArea')->unique();

        if ($unitno == 0) {
            $unit_name = 'Admin';
            $unit_email = 'Admin';
            $academy_list = Academy::pluck('Academy_Name');
        } else {
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
                'unitno' => $unitno,
                'unit_name' => $unit_name,
                'unitemail' => $unit_email,
                'academy_list' => $academy_list,
                'bsa_list' => $bsa_list,
                'ms_dict' => $ms_dict
            ]
        );
    }
}
