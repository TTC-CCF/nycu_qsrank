<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use App\Models\Permission;
use App\Models\Scholar_list;
use App\Models\Employer_list;
use App\Models\ScholarYearResult;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class ListController extends Controller
{
    public $output;
    function __construct()
    {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();

    }

    private function getStaticData($mode)
    {
        $bsa_list = Subject::pluck('BroadSubjectArea')->unique();
        
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

        $academy = Academy::all();
        foreach($academy as $row){
            $academy_list[$row->Academy_No] = $row->Academy_Name;
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
        return [
            'bsa_list' => $bsa_list,
            'ms_dict' => $ms_dict,
            'academy_list' => $academy_list,
            'titles' => $titles,
            'industries' => $industries,
            'countries' => $countries,
        ];
    }
    public function showList(Request $request)
    {
        $id = Session::get("id");
        $mode = Session::get('list_mode');
        $add_status = Session::get('status');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $view_name = ($mode == 'scholar') ? 'scholarList' : 'employerList';

        $unit = intval($request->query("unit"));
        $unit = $unit === 0 ? 1 : $unit;

        $static_data = $this->getStaticData($mode);
        
        [$list, $year_result] = $table->getList($unit);

        $permit = Permission::getPermission($id);

        // highlight the row that has duplicated email address
        $table->getDuplicatePerson($list);

        return view(
            $view_name,
            [
                'permit' => $permit,
                'unit' => $unit,
                'list' => $list,
                'admin' => $id == 0,
                'add_status' => $add_status,
                'year_results' => $year_result,
                ...$static_data,
            ]
        );

    }
    public function changeMode(Request $request)
    {
        $chmod = $request->input('mode');
        Session::put('list_mode', $chmod);
    }
}
