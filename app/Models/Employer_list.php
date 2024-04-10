<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Exception;

class Employer_list extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Employer_list';
    protected $primaryKey = 'SN';
    protected $fillable = array('*');
    public $timestamps = false;

    public function yearResults(): HasMany
    {
        return $this->hasMany(EmployerYearResult::class, 'employer_id', 'SN');
    }

    public function updateYearResult(int $sn, int $year, bool $new_data)
    {
        $yearResult = EmployerYearResult::where('employer_id', $sn)
            ->where('year', $year)
            ->first();
        $output = new \Symfony\Component\Console\Output\ConsoleOutput;
        DB::beginTransaction();
        try {
            if ($yearResult) {
                $yearResult->result = $new_data;
                $yearResult->save();

            } else {
                $new_result = new EmployerYearResult;
                $new_result->employer_id = $sn;
                $new_result->year = $year;
                $new_result->result = $new_data;

                $new_result->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            $output->write($e);
            DB::rollback();
            throw $e;
        }
    }

    public function updateUnitno(int $sn, $new_data)
    {
        DB::beginTransaction();
        try {
            $unitno = Academy::where('Academy_Name', $new_data)->get(['Academy_No'])[0]['Academy_No'];
            self::where('SN', $sn)->update([
                'unitno' => $unitno,
                '資料提供單位' => $new_data,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateTextData(int $sn, $key, $new_data)
    {
        DB::beginTransaction();
        try {
            self::where('SN', $sn)->update([$key => $new_data]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getDuplicatePerson(&$originalData)
    {
        $duplicated = self::select("SN", "Email", "資料提供單位")
            ->whereRaw('Email IN (SELECT Email FROM Employer_list GROUP BY Email HAVING COUNT(Email) > 1)')
            ->get();

        $hashDuplicated = [];
        foreach ($duplicated as $row) {
            if (!isset($hashDuplicated[$row->Email])) 
                $hashDuplicated[$row->Email] = [];

            if (!in_array($row->資料提供單位, $hashDuplicated[$row->Email])) {
                $hashDuplicated[$row->Email][] = $row->資料提供單位;
            } 
        }

        foreach ($originalData as $row) {
            if (isset($hashDuplicated[$row->Email])) {
                $row->dupUnits = array_filter(
                    $hashDuplicated[$row->Email],
                    fn($value) => $value !== $row->資料提供單位
                );
                $row->dupUnits = implode("\n", $row->dupUnits);
            } else {
                $row->dupUnits = null;
            }
        }

    }

    public function getList(int $unitno) 
    {
        $minimumYear = EmployerYearResult::min("year");
        $list = self::where("unitno", $unitno)->orderBy('資料提供單位')->orderBy('SN')->get();

        // Constructing year result dataframe
        for ($i = $minimumYear; $i <= date("Y"); $i++) {
            $year_result[$i] = [];
        }

        foreach ($list as $person) {
            $results = $person->yearResults()->select("employer_id", "year", "result")->get();
            foreach ($results as $result) {
                $year_result[$result->year][$result->employer_id] = $result->result;
            }
            foreach ($year_result as $year => $_) {
                if (!isset($year_result[$year][$person->SN]))
                    $year_result[$year][$person->SN] = 0;
            }

        }
        
        return [$list, $year_result];
    }

    public function addRecord($unitno, $request, $admin) 
    {
        if ($unitno == 0) {
            $unitname = $request->input('UnitName');
            $unitno = Academy::where('Academy_Name', $unitname)->get(['Academy_No'])[0]['Academy_No'];
        }

        $row = new Employer_list;
        DB::beginTransaction();
        try{
            $row->SN = $request->input('SN');
            $row->year = $request->input('year');
            $row->unitno = $unitno;
            $row->資料提供單位 = $request->input('UnitName');
            $row->資料提供者 = $request->input('Provider');
            $row->資料提供者Email = $request->input('UnitEmail');
            $row->Title = $request->input('Title') == '其他' ? $request->input('OtherTitle') : $request->input('Title');
            $row->First_name = $request->input('FirstName');
            $row->Last_name = $request->input('LastName');
            $row->Chinese_name = $request->input('ChineseName');
            $row->Position = $request->input('Position');
            $row->Industry = $request->input('Industry');
            $row->CompanyName = $request->input('CompanyName');
            $row->Location = $request->input('Location');
            $row->BroadSubjectArea = $request->input('BroadSubjectArea');
            $row->MainSubject = $request->input('MainSubject');
            $row->Email = $request->input('Email');
            $row->Phone = $request->input('Phone');
            if ($admin && $request->exists('今年是否同意參與QS')){
                $rec = new EmployerYearResult;
                $rec->employer_id = $row->SN;
                $rec->year = $row->year;
                $rec->result = true;
                $rec->save();     
            }
            $row->save();

            DB::commit();
        }
        catch (Exception $err) {
            $this->output->writeln($err);
            DB::rollback();
            throw $err;
        }
    }
}
