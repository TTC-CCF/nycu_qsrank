<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Exception;

class Scholar_list extends Model
{
    private $column_alias = [
        '資料提供單位' => 'unit_name',
        '資料提供者Email' => 'provider_email',
        '資料提供者' => 'provider_name',
        'Title' => 'title',
        'First Name' => 'first_name',
        'Last Name' => 'last_name',
        'Chinese Name' => 'chinese_name',
        'Institution' => 'institution',
        'Job Title' => 'job_title',
        'Department' => 'department',
        'Broad Subject Area' => 'broad_subject_area',
        'Main Subject' => 'main_subject',
        'Country' => 'location',
        '寄送Email日期' => 'sent_email_date',
        'Email' => 'email',
        'Phone' => 'phone',
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    
    protected $table = 'Scholar_list';
    protected $primaryKey = 'SN';
    protected $fillable = array('*');
    public $timestamps = false;

    public function yearResults(): HasMany
    {
        return $this->hasMany(ScholarYearResult::class, 'scholar_id', 'SN');
    }

    public function updateYearResult(int $sn, int $year, bool $new_data)
    {
        $yearResult = ScholarYearResult::where('scholar_id', $sn)
            ->where('year', $year)
            ->first();
        $output = new \Symfony\Component\Console\Output\ConsoleOutput;
        DB::beginTransaction();
        try {
            if ($yearResult) {
                $yearResult->result = $new_data;
                $yearResult->save();

            } else {
                $new_result = new ScholarYearResult;
                $new_result->scholar_id = $sn;
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
                'unit_name' => $new_data,
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
            $data_key = $this->column_alias[$key];
            self::where('SN', $sn)->update([$data_key => $new_data]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getDuplicatePerson(&$originalData)
    {
        $duplicated = self::select("SN", "email", "unit_name")
            ->whereRaw('email IN (SELECT email FROM Scholar_list GROUP BY email HAVING COUNT(email) > 1)')
            ->get();

        $hashDuplicated = [];
        foreach ($duplicated as $row) {
            if (!isset($hashDuplicated[$row->email])) 
                $hashDuplicated[$row->email] = [];

            if (!in_array($row->unit_name, $hashDuplicated[$row->email])) {
                $hashDuplicated[$row->email][] = $row->unit_name;
            } 
        }

        foreach ($originalData as $row) {
            if (isset($hashDuplicated[$row->email])) {
                $row->dupUnits = array_filter(
                    $hashDuplicated[$row->email],
                    fn($value) => $value !== $row->unit_name
                );
                $row->dupUnits = implode("\n", $row->dupUnits);
            } else {
                $row->dupUnits = null;
            }
        }

    }

    public function getList(int $unitno) 
    {
        $maximumYear = date("Y") + 1;
        $minimumYear = ScholarYearResult::min("year");
        $minimumYear = $minimumYear > $maximumYear - 5 ? $minimumYear : $maximumYear - 5;
        $list = self::where("unitno", $unitno)->orderBy('unit_name')->orderBy('SN')->get();

        // Constructing year result dataframe
        for ($i = $maximumYear; $i >= $minimumYear; $i--) {
            $year_result[$i] = [];
        }

        foreach ($list as $person) {
            $results = $person->yearResults()->select("scholar_id", "year", "result")->get();
            foreach ($results as $result) {
                $year_result[$result->year][$result->scholar_id] = $result->result;
            }

            foreach ($year_result as $year => $_) {
                if (!isset($year_result[$year][$person->SN]))
                    $year_result[$year][$person->SN] = -1;
            }

        }
        
        return [$list, $year_result];
    }

    public function addRecord($unitno, $request, $admin)
    {
        $columns = Schema::getColumnListing('Scholar_list');
        $tmp_col = [];
        foreach ($columns as $col) {
            $tmp_col[$col] = $col;
        }
        $columns = $tmp_col;
        $columns = array_merge($columns, $this->column_alias);

        if ($unitno == 0) {
            $unitname = $request->input('資料提供單位');
            $unitno = Academy::where('Academy_Name', $unitname)->get(['Academy_No'])[0]['Academy_No'];
        }

        $row = new Scholar_list;
        DB::beginTransaction();
        try{
            foreach ($request->all() as $key => $value) {
                if (array_key_exists($key, $columns)) {
                    $data_key = $columns[$key];
                    if ($key === 'Title' && $value === '其他')
                        $row->$data_key = $request->input('OtherTitle');
                    else
                        $row->$data_key = $value;
                }
            }

            if ($admin && $request->exists('今年是否同意參與QS')) {
                $rec = new ScholarYearResult;
                $rec->scholar_id = $row->SN;
                $rec->year = $row->year;
                $rec->result = true;
                $rec->save();                
            }
            $row->unitno = $unitno;
            $row->save();

            DB::commit();
        }
        catch (Exception $err) {
            DB::rollback();
            throw $err;
        }
    }

    public function importData($unitno, $data, $mode)
    {
        $columns = $this->getAllColumns();

        if ($unitno !== 0 && $mode !== "data") {
            throw new Exception("Invalid mode");
        }

        $row_counts = count($data->Email);
        DB::beginTransaction();
        try {
            if ($mode === "data") {
                /** insert or update employer data */
                $emails = $data->Email;
                $maxsn = self::max('SN');
                $existingEmails = self::whereIn('Email', $emails)->get(['Email'])->pluck('Email')->toArray();

                for ($i = 0; $i < $row_counts; $i++) {
                    $data_unitno = intval(substr($data->資料提供單位[$i], 0, 2));
                    if ($unitno !== 0 && $data_unitno !== $unitno) {
                        throw new Exception("Unit number does not match");
                    }

                    $row = new Scholar_list;

                    if (in_array($data->Email[$i], $existingEmails)) {
                        $row = self::where('email', $data->Email[$i])->first();
                    }

                    foreach ($data as $key => $arr) {
                        if (array_key_exists($key, $columns)) {
                            $new_key = $columns[$key];
                            $row->$new_key = $arr[$i];
                        }
                    }

                    $row->unitno = $data_unitno;
                    $row->SN = $row->SN ? $row->SN : ++$maxsn;
                    $row->save();
                }
            } else if ($mode === "year result") {
                /** insert or update scholar year result  */
                
                $year_keys = array_filter(array_keys((array) $data), function ($key) {
                    return preg_match("/^20\d{2}同意參與QS$/", $key);
                });

                for ($i = 0; $i < $row_counts; $i++) {
                    $sn = self::where('email', $data->Email[$i])->get(['SN'])[0]['SN'];
                    if (!$sn) {
                        throw new Exception("Email not found in database");
                    }

                    foreach ($year_keys as $key) {

                        $year = intval(substr($key, 0, 4));
                        $value = $data->$key[$i] === "同意" ? 1 :
                            ($data->$key[$i] === "不同意" ? 0 : -1);
                        $this->updateYearResult($sn, $year, $value);
                    }
                }
            }
            DB::commit();
        } catch (Exception $err) {
            DB::rollback();
            throw $err;
        }
    }

    private function getAllColumns()
    {
        $columns = Schema::getColumnListing('Scholar_list');
        $tmp_col = [];
        foreach ($columns as $col) {
            $tmp_col[$col] = $col;
        }
        $columns = $tmp_col;
        $columns = array_merge($columns, $this->column_alias);
        return $columns;
    }
}
