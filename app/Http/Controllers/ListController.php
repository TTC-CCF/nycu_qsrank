<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use App\Models\Scholar_list;
use App\Models\Employer_list;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class ListController extends Controller
{
    public $output;
    function __construct() {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();

      }
    public function showList(){
        $unitno = Session::get('id');
        $mode = Session::get('list_mode');
        $add_status = Session::get('status');
        $table = ($mode == 'scholar') ? new Scholar_list : new Employer_list;
        $view_name = ($mode == 'scholar') ? 'scholarList' : 'employerList';
        $bsa_list = Subject::pluck('BroadSubjectArea')->unique();
        $academy_list = Academy::pluck('Academy_Name');

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
        
        if ($unitno != null){
            if ($unitno === '0'){
                $_list = $table::where('year', date('Y'))->orderBy('資料提供單位')->orderBy('SN')->get();
            }
            else{
                $_list = $table::where('資料提供單位編號', $unitno)->where('year', date('Y'))->orderBy('SN')->get();
            } 
            
            // highlight the row that has duplicated email address
            $email_list = [];
            foreach ($_list as $row) {
                $email = $row->Email;
            
                if (in_array($email, $email_list)) {
                    $duplicate_rows = array_keys($email_list, $email);
                    foreach($duplicate_rows as $duplicate_row) {
                        $_list[$duplicate_row]->is_duplicated = true;
                    }
                    $row->is_duplicated = true;
                } else {
                    $row->is_duplicated = false;
                }
                $email_list[] = $email;
            }
            return view(
                $view_name, 
                [
                    'list' => $_list,
                    'admin'=> $unitno == 0,
                    'bsa_list' => $bsa_list,
                    'ms_dict' => $ms_dict,
                    'academy_list' => $academy_list,
                    'titles' => $titles,
                    'industries' => $industries,
                    'countries' => $countries,
                    'add_status' => $add_status
                ]);
        }
        else {
            return redirect()->route('login')->with('error', '尚未登入');
        }
    }
    public function changeMode(Request $request){
        $chmod = $request->input('mode');
        Session::put('list_mode', $chmod);
    }
}
