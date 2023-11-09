<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Project;
use App\Models\Shift;
use App\Models\ShiftProject;

class CsvIssueController extends Controller
{

    public function index()
    {
        $projects = Project::all();

        return view('csv-issue.index', compact('projects'));
    }

    public function show(Request $request)
    {
        $projectId = $request->project;
        $month = $request->month;

        $getProject = Project::find($projectId);
        // 取得した月でフィルター
        $shifts = Shift::whereMonth('date', $month)
            ->get();

        // 月の全日付を取得
        Carbon::setLocale('ja');

        $dates = [];
        $year = Carbon::now()->year; // 現在の年を取得
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $dates[] = [
                'display' => $date->format('m月d日') . '(' . $date->isoFormat('ddd') . ')',
                'compare' => $date->format('Y-m-d')
            ];
        }


        // foreach($shifts as $shift)
        // {
        //     foreach($shift->projects as $project)
        //     {
        //         // echo $project->name;
                // echo $project->pivot->time_of_day;
        //     }
        // }



        return view('csv-issue.show', compact('shifts','getProject','dates','projectId','month'));
    }

    public function csvExport($projectId,$month)
    {

        // 案件名を取得
        $getProject = Project::find($projectId);
        $projectName = $getProject->name;

        // 月に一致するシフトを取得
        $shifts = Shift::whereMonth('date', $month)
            ->get();

        // 月の全日付を取得
        Carbon::setLocale('ja');

        $dates = [];
        $year = Carbon::now()->year; // 現在の年を取得
        $startDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startDate->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $dates[] = [
                'display' => $date->format('m月d日') . '(' . $date->isoFormat('ddd') . ')',
                'compare' => $date->format('Y-m-d')
            ];
        }

        // StreamedResponseを使用してCSVを出力
        $response = new StreamedResponse(function() use ($dates, $shifts, $projectId) {
            // 出力バッファを開く
            $handle = fopen('php://output', 'w');

            // CSVのヘッダーを書き込む
            fputcsv($handle, ['日付', '午前', '午後']);

            // 日付ごとにデータをループ
            foreach ($dates as $date) {
                $amName = $pmName = ''; // 午前と午後の名前を初期化

                // 午前のシフトを探す
                foreach ($shifts as $shift) {
                    if ($shift->date == $date['compare']) {
                        foreach ($shift->projects as $project) {
                            if ($project->id == $projectId && $project->pivot->time_of_day == 0) {
                                $amName = $shift->employee->name;
                                break;
                            }
                        }
                    }
                }

                // 午後のシフトを探す
                foreach ($shifts as $shift) {
                    if ($shift->date == $date['compare']) {
                        foreach ($shift->projects as $project) {
                            if ($project->id == $projectId && $project->pivot->time_of_day == 1) {
                                $pmName = $shift->employee->name;
                                break;
                            }
                        }
                    }
                }

                // CSVに1行書き込む
                fputcsv($handle, [$date['display'], $amName, $pmName]);
            }

            // 出力バッファを閉じる
            fclose($handle);
        });

        // ファイル名
        $filename = $month.'月'.$projectName.'.csv';

        // レスポンスのヘッダーを設定
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
$response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        // レスポンスを返す
        return $response;

    }
}
