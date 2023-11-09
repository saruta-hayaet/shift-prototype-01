<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Shift;
use App\Models\ShiftProject;
use App\Models\ProjectEmployeePayment;
use Illuminate\Database\Console\DumpCommand;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\VarDumper\VarDumper;

use function PHPUnit\Framework\isEmpty;

class ShiftController extends Controller
{
    public function index()
    {
        $allShift = Shift::with('project')->get();
        $allShiftProject = ShiftProject::all();
        $employees = Employee::all();
        $payments = ProjectEmployeePayment::all();
        $vehicles = Vehicle::all();

        $employeeList = [];
        foreach($employees as $employee){
            array_push($employeeList,$employee->name);
        }

        // 日にちごとにシフトをグループ分け
        $shifts = $allShift->groupBy(function($item) {
            return $item->date;
        });

        // 中間テーブルを日付ごと格納
        $tmpShift = [];
        foreach($allShiftProject as $shift){
            if (!isset($tmpShift[$shift->shift_id])) {
                $tmpShift[$shift->shift_id] = [];
            }

            if (!isset($tmpShift[$shift->shift_id][$shift->time_of_day])) {
                $tmpShift[$shift->shift_id][$shift->time_of_day] = [];
            }
            array_push($tmpShift[$shift->shift_id][$shift->time_of_day], $shift->project->name);
        }

        // 上代
        $tmpPrice = [];
        foreach($allShiftProject as $shift){
            if (!isset($tmpPrice[$shift->shift_id])) {
                $tmpPrice[$shift->shift_id] = [];
            }

            if (!isset($tmpPrice[$shift->shift_id][$shift->time_of_day])) {
                $tmpPrice[$shift->shift_id][$shift->time_of_day] = [];
            }
            array_push($tmpPrice[$shift->shift_id][$shift->time_of_day], $shift->project->retail_price);
        }

        // 各従業員の上代合計
        $totalPrice = [];
        foreach($allShiftProject as $shift){
            // echo $shift->shift->employee_id.',';
            // echo $shift->project->retail_price.',';
            if (!isset($totalPrice[$shift->shift->employee_id])) {
                $totalPrice[$shift->shift->employee_id] = 0;
            }
            $totalPrice[$shift->shift->employee_id] += $shift->project->retail_price;
        }


        // 給与
        $tmpEmployeePrice = [];
        foreach($allShiftProject as $shift){
            if (!isset($tmpEmployeePrice[$shift->shift_id])) {
                $tmpEmployeePrice[$shift->shift_id] = [];
            }

            if (!isset($tmpEmployeePrice[$shift->shift_id][$shift->time_of_day])) {
                $tmpEmployeePrice[$shift->shift_id][$shift->time_of_day] = [];
            }

            $matched = false;
            foreach($payments as $payment){
                if($shift->shift->employee_id == $payment->employee_id && $shift->project_id == $payment->project_id){
                    array_push($tmpEmployeePrice[$shift->shift_id][$shift->time_of_day], $payment->amount);
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                array_push($tmpEmployeePrice[$shift->shift_id][$shift->time_of_day], $shift->project->driver_price);
            }

        }

        // 各従業員の上代合計
        $totalEmployeePrice = [];
        foreach($allShiftProject as $shift){
            if (!isset($totalEmployeePrice[$shift->shift->employee_id])) {
                $totalEmployeePrice[$shift->shift->employee_id] = 0;
            }
            $matched = false;
            foreach($payments as $payment){
                if($shift->shift->employee_id == $payment->employee_id && $shift->project_id == $payment->project_id){
                    $totalEmployeePrice[$shift->shift->employee_id] += $payment->amount;
                    $matched = true;
                    break;
                }
            }
            if(!$matched){
                $totalEmployeePrice[$shift->shift->employee_id] += $shift->project->driver_price;
            }
        }


        return view('shift.index', compact('employees','shifts','allShift','allShiftProject','tmpShift','tmpPrice','employeeList','totalPrice','tmpEmployeePrice','totalEmployeePrice','vehicles'));
    }
    public function projectPriceShift()
    {
        $allShift = Shift::with('project')->get();
        $allShiftProject = ShiftProject::all();
        $employees = Employee::all();

        $employeeList = [];
        foreach($employees as $employee){
            array_push($employeeList,$employee->name);
        }

        // 日にちごとにシフトをグループ分け
        $shifts = $allShift->groupBy(function($item) {
            return $item->date;
        });

        // 中間テーブルを日付ごと格納
        $tmpShift = [];
        foreach($allShiftProject as $shift){
            if (!isset($tmpShift[$shift->shift_id])) {
                $tmpShift[$shift->shift_id] = [];
            }

            if (!isset($tmpShift[$shift->shift_id][$shift->time_of_day])) {
                $tmpShift[$shift->shift_id][$shift->time_of_day] = [];
            }
            array_push($tmpShift[$shift->shift_id][$shift->time_of_day], $shift->project->name);
        }

        // 上代
        $tmpPrice = [];
        foreach($allShiftProject as $shift){
            if (!isset($tmpPrice[$shift->shift_id])) {
                $tmpPrice[$shift->shift_id] = [];
            }

            if (!isset($tmpPrice[$shift->shift_id][$shift->time_of_day])) {
                $tmpPrice[$shift->shift_id][$shift->time_of_day] = [];
            }
            array_push($tmpPrice[$shift->shift_id][$shift->time_of_day], $shift->project->retail_price);
        }

        // 各従業員の上代合計
        $totalPrice = [];
        foreach($allShiftProject as $shift){
            // echo $shift->shift->employee_id.',';
            // echo $shift->project->retail_price.',';
            if (!isset($totalPrice[$shift->shift->employee_id])) {
                $totalPrice[$shift->shift->employee_id] = 0;
            }
            $totalPrice[$shift->shift->employee_id] += $shift->project->retail_price;
        }
        // dd($totalPrice);


        return view('shift.projectPriceShift', compact('employees','shifts','allShift','allShiftProject','tmpShift','tmpPrice','employeeList','totalPrice'));
    }

    public function employeePriceShift()
    {
        $allShift = Shift::with('project')->get();
        $allShiftProject = ShiftProject::all();
        $employees = Employee::all();
        $payments = ProjectEmployeePayment::all();

        $employeeList = [];
        foreach($employees as $employee){
            array_push($employeeList,$employee->name);
        }

        // 日にちごとにシフトをグループ分け
        $shifts = $allShift->groupBy(function($item) {
            return $item->date;
        });

        // 中間テーブルを日付ごと格納
        $tmpShift = [];
        foreach($allShiftProject as $shift){
            if (!isset($tmpShift[$shift->shift_id])) {
                $tmpShift[$shift->shift_id] = [];
            }

            if (!isset($tmpShift[$shift->shift_id][$shift->time_of_day])) {
                $tmpShift[$shift->shift_id][$shift->time_of_day] = [];
            }
            array_push($tmpShift[$shift->shift_id][$shift->time_of_day], $shift->project->name);
        }

        // 給与
        $tmpPrice = [];
        foreach($allShiftProject as $shift){
            if (!isset($tmpPrice[$shift->shift_id])) {
                $tmpPrice[$shift->shift_id] = [];
            }

            if (!isset($tmpPrice[$shift->shift_id][$shift->time_of_day])) {
                $tmpPrice[$shift->shift_id][$shift->time_of_day] = [];
            }

            $matched = false;
            foreach($payments as $payment){
                if($shift->shift->employee_id == $payment->employee_id && $shift->project_id == $payment->project_id){
                    array_push($tmpPrice[$shift->shift_id][$shift->time_of_day], $payment->amount);
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                array_push($tmpPrice[$shift->shift_id][$shift->time_of_day], $shift->project->driver_price);
            }

        }

        // 各従業員の上代合計
        $totalPrice = [];
        foreach($allShiftProject as $shift){
            if (!isset($totalPrice[$shift->shift->employee_id])) {
                $totalPrice[$shift->shift->employee_id] = 0;
            }
            $matched = false;
            foreach($payments as $payment){
                if($shift->shift->employee_id == $payment->employee_id && $shift->project_id == $payment->project_id){
                    $totalPrice[$shift->shift->employee_id] += $payment->amount;
                    $matched = true;
                    break;
                }
            }
            if(!$matched){
                $totalPrice[$shift->shift->employee_id] += $shift->project->driver_price;
            }
        }
        // dd($totalPrice);


        return view('shift.employeePriceShift', compact('employees','shifts','allShift','allShiftProject','tmpShift','tmpPrice','employeeList','totalPrice'));
    }

    public function employeeShowShift()
    {
        $allShift = Shift::all();
        $allShiftProject = ShiftProject::all();
        $employees = Employee::all();
        $vehicles = Vehicle::all();

        $employeeList = [];
        foreach($employees as $employee){
            array_push($employeeList,$employee->name);
        }

        // 日にちごとにシフトをグループ分け
        $shifts = $allShift->groupBy(function($item) {
            return $item->date;
        });

        // 日付ごとのシフトのidを格納
        $tmpId = [];
        foreach($shifts as $date => $shift){
            foreach($shift as $row){
                // dd($row->am_vehicle->number);
                // $tmpId[$date][] = $row->id;
            }
        }

        // 中間テーブルを日付ごと格納
        $tmpShift = [];
        foreach($allShiftProject as $shift){
            if (!isset($tmpShift[$shift->shift_id])) {
                $tmpShift[$shift->shift_id] = [];
            }

            if (!isset($tmpShift[$shift->shift_id][$shift->time_of_day])) {
                $tmpShift[$shift->shift_id][$shift->time_of_day] = [];
            }

            array_push($tmpShift[$shift->shift_id][$shift->time_of_day], $shift->project->name);
        }

        return view('shift.employeeShowShift', compact('employees','shifts','allShift','allShiftProject','tmpShift','tmpId','vehicles','employeeList'));
    }

    public function create()
    {
        $employees = Employee::all();
        $projects = Project::all();
        $vehicles = Vehicle::all();

        return view('shift.create', compact('employees','projects','vehicles'));
    }

    public function store(Request $request)
    {
        $calendarDate = $request->input('calendar');
        $shift = Shift::where('date', $calendarDate)->get();
        if($shift->isEmpty()){
            foreach ($request->input('employee_id') as $employeeId) {
                // Shiftテーブルにデータを保存
                $shift = Shift::create([
                    'employee_id' => $employeeId,
                    'am_vehicle_id' => $request->input("am_vehicle.{$employeeId}"),
                    'pm_vehicle_id' => $request->input("pm_vehicle.{$employeeId}"),
                    'date' => $calendarDate,
                ]);

                // ShiftProjectテーブルにデータを保存 (午前)
                if ($amProject1 = $request->input("am_project1.{$employeeId}")) {
                    ShiftProject::create([
                        'shift_id' => $shift->id,
                        'project_id' => $amProject1,
                        'time_of_day' => '0', // 0:am
                    ]);
                }
                if ($amProject2 = $request->input("am_project2.{$employeeId}")) {
                    ShiftProject::create([
                        'shift_id' => $shift->id,
                        'project_id' => $amProject2,
                        'time_of_day' => '0', // 0:am
                    ]);
                }

                // ShiftProjectテーブルにデータを保存 (午後)
                if ($pmProject1 = $request->input("pm_project1.{$employeeId}")) {
                    ShiftProject::create([
                        'shift_id' => $shift->id,
                        'project_id' => $pmProject1,
                        'time_of_day' => '1', // 1:pm
                    ]);
                }
                if ($pmProject2 = $request->input("pm_project2.{$employeeId}")) {
                    ShiftProject::create([
                        'shift_id' => $shift->id,
                        'project_id' => $pmProject2,
                        'time_of_day' => '1', // 1:pm
                    ]);
                }
            }
        }else{
            return redirect()->route('shift.create')->with('alert', '指定されている日付は登録済みです。編集画面から変更してください');
        }

        return redirect()->route('shift.');
    }

    public function edit()
    {
        $allShift = Shift::all();
        $allShiftProject = ShiftProject::all();
        $employees = Employee::all();
        $projects = Project::all();
        $vehicles = Vehicle::all();

        $employeeList = [];
        foreach($employees as $employee){
            array_push($employeeList,$employee->name);
        }

        // 日にちごとにシフトをグループ分け
        $shifts = $allShift->groupBy(function($item) {
            return $item->date;
        });

        // 日付ごとのシフトのidを格納
        $tmpId = [];
        foreach($shifts as $date => $shift){
            foreach($shift as $row){
                // dd($row->am_vehicle->number);
                // $tmpId[$date][] = $row->id;
            }
        }

        // 中間テーブルを日付ごと格納
        $tmpShift = [];
        $tmpProjectId = [];
        foreach($allShiftProject as $shift){
            if (!isset($tmpShift[$shift->shift_id])) {
                $tmpShift[$shift->shift_id] = [];
            }

            if (!isset($tmpShift[$shift->shift_id][$shift->time_of_day])) {
                $tmpShift[$shift->shift_id][$shift->time_of_day] = [];
            }

            array_push($tmpShift[$shift->shift_id][$shift->time_of_day], $shift->project->name);

            if (!isset($tmpProjectId[$shift->shift_id])) {
                $tmpProjectId[$shift->shift_id] = [];
            }

            if (!isset($tmpProjectId[$shift->shift_id][$shift->time_of_day])) {
                $tmpProjectId[$shift->shift_id][$shift->time_of_day] = [];
            }

            array_push($tmpProjectId[$shift->shift_id][$shift->time_of_day], $shift->project->id);
        }

        return view('shift.edit', compact('employees','shifts','allShift','allShiftProject','tmpShift','tmpId','vehicles','employeeList','projects','tmpProjectId'));
    }

    // public function update(Request $request)
    // {
    //     // 1. 送信されるデータを取得
    //     $am_project1 = $request->input('am_project1');
    //     $am_project2 = $request->input('am_project2');
    //     $pm_project1 = $request->input('pm_project1');
    //     $pm_project2 = $request->input('pm_project2');
    //     $am_vehicle = $request->input('am_vehicle');
    //     $pm_vehicle = $request->input('pm_vehicle');


    //     // 2. それぞれのシフトIDに対して、午前と午後のプロジェクトと車両の情報を更新
    //     foreach ($am_project1 as $shift_id => $project_id) {
    //         $shift = Shift::find($shift_id);
    //         $shiftProject = ShiftProject::where('shift_id', $shift_id)->first();

    //         foreach ($request->input('am_project1') as $shift_id => $project_id) {
    //             $shiftProject = ShiftProject::where('shift_id', $shift_id)->where('time_of_day', 0)->first();
    //             if (!$shiftProject) {
    //                 $shiftProject = new ShiftProject();
    //                 $shiftProject->shift_id = $shift_id;
    //                 $shiftProject->time_of_day = 0;
    //             }
    //             $shiftProject->project_id = $project_id;
    //             $shiftProject->save();
    //         }

    //         foreach ($request->input('pm_project1') as $shift_id => $project_id) {
    //             $shiftProject = ShiftProject::where('shift_id', $shift_id)->where('time_of_day', 1)->first();
    //             if (!$shiftProject) {
    //                 $shiftProject = new ShiftProject();
    //                 $shiftProject->shift_id = $shift_id;
    //                 $shiftProject->time_of_day = 1;
    //             }
    //             $shiftProject->project_id = $project_id;
    //             $shiftProject->save();
    //         }

    //         foreach ($request->input('am_project2') as $shift_id => $project_id) {
    //             $shiftProject = ShiftProject::where('shift_id', $shift_id)->where('time_of_day', 0)->skip(1)->first();
    //             if (!$shiftProject) {
    //                 $shiftProject = new ShiftProject();
    //                 $shiftProject->shift_id = $shift_id;
    //                 $shiftProject->time_of_day = 0;
    //             }
    //             $shiftProject->project_id = $project_id;
    //             $shiftProject->save();
    //         }

    //         foreach ($request->input('pm_project2') as $shift_id => $project_id) {
    //             $shiftProject = ShiftProject::where('shift_id', $shift_id)->where('time_of_day', 1)->skip(1)->first();
    //             if (!$shiftProject) {
    //                 $shiftProject = new ShiftProject();
    //                 $shiftProject->shift_id = $shift_id;
    //                 $shiftProject->time_of_day = 1;
    //             }
    //             $shiftProject->project_id = $project_id;
    //             $shiftProject->save();
    //         }


    //         // 午前の車両情報を更新
    //         $shift->am_vehicle_id = $am_vehicle[$shift_id];
    //         // 午後の車両情報を更新

    //         $shift->pm_vehicle_id = $pm_vehicle[$shift_id];
    //         $shift->save();
    //     }

    //         // 2. それぞれのシフトIDに対して、午前と午後のプロジェクトと車両の情報を更新
    //     foreach ($am_project1 as $shift_id => $project_id) {
    //         $shift = Shift::find($shift_id);
    //         // ここで関連する中間テーブルや他のテーブルを更新するロジックを追加する必要があるかもしれません。
    //         // 例えば、ShiftProjectモデルがある場合、それを更新するロジックを追加します。

    //         // 午前の車両情報を更新
    //         $shift->am_vehicle_id = $am_vehicle[$shift_id];
    //         // 午後の車両情報を更新
    //         $shift->pm_vehicle_id = $pm_vehicle[$shift_id];
    //         $shift->save();
    //     }

    //     return redirect()->route('shift.');

    // }

    public function update(Request $request)
    {
        // 1. 送信されるデータを取得
        $am_project1 = $request->input('am_project1');
        $am_project2 = $request->input('am_project2');
        $pm_project1 = $request->input('pm_project1');
        $pm_project2 = $request->input('pm_project2');
        $am_vehicle = $request->input('am_vehicle');
        $pm_vehicle = $request->input('pm_vehicle');

        // 2. それぞれのシフトIDに対して、午前と午後のプロジェクトと車両の情報を更新
        foreach ($am_project1 as $shift_id => $project_id) {
            $shift = Shift::find($shift_id);
            if (!$shift) {
                continue; // シフトが見つからない場合はスキップ
            }

            // 午前のプロジェクト情報を更新
            $this->updateShiftProject($shift_id, $am_project1[$shift_id], 0);
            $this->updateShiftProject($shift_id, $am_project2[$shift_id] ?? null, 0, true);

            // 午後のプロジェクト情報を更新
            $this->updateShiftProject($shift_id, $pm_project1[$shift_id], 1);
            $this->updateShiftProject($shift_id, $pm_project2[$shift_id] ?? null, 1, true);

            // 車両情報を更新
            $shift->am_vehicle_id = $am_vehicle[$shift_id] ?? null;
            $shift->pm_vehicle_id = $pm_vehicle[$shift_id] ?? null;
            $shift->save();
        }

        return redirect()->route('shift.');
    }

    private function updateShiftProject($shift_id, $project_id, $time_of_day, $second = false)
    {
        if (is_null($project_id)) {
            return;
        }

        $query = ShiftProject::where('shift_id', $shift_id)->where('time_of_day', $time_of_day);
        if ($second) {
            $query->skip(1);
        }
        $shiftProject = $query->first();

        if (!$shiftProject) {
            $shiftProject = new ShiftProject();
            $shiftProject->shift_id = $shift_id;
            $shiftProject->time_of_day = $time_of_day;
        }
        $shiftProject->project_id = $project_id;
        $shiftProject->save();
    }

    public function project()
    {
        $projects = Project::all();

        // 日付順でシフトを取得
        $allShifts = Shift::orderBy('date')->get();
        $shiftProjects = ShiftProject::all();

        // 日にちごとにシフトをグループ分け
        $groupedByDate = $shiftProjects->groupBy(function($item) {
            return $item->shift->date;
        });

        $projectCounts = [];
        // 日にちごとに案件を格納
        $projectsForDate = [];

        foreach ($groupedByDate as $date => $shiftProjectsForDate) {

            foreach($shiftProjectsForDate as $project){
                if (!isset($projectsForDate[$date][$project->project_id])) {
                    $projectsForDate[$date][$project->project_id] = [];
                }

                if (!in_array($project->shift->employee_id, $projectsForDate[$date][$project->project_id])) {
                    $projectsForDate[$date][$project->project_id][] = $project->shift->employee_id;
                }
            }
        }

        return view('shift.shiftProject', compact('projects','projectsForDate'));

        // 日にちを取得
        // $dates = array();
        // foreach($shifts as $shift){
        //     if(!in_array($shift->date, $dates)){
        //         array_push($dates,$shift->date);
        //     }
        // }
        // // ソート実行
        // usort($dates, function($a, $b) {
        //     return strtotime($a) - strtotime($b);
        // });
    }

    public function employeeShift()
    {

        return view('shift.employeeShift');
    }

    public function csvImport(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        foreach ($csv as $record){
            print_r($record);
        }
    }
}
