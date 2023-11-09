<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
use App\Models\ProjectEmployeePayment;
use League\Csv\Reader;
use League\Csv\Statement;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('project.index', compact('projects'));
    }

    public function create()
    {
        return view('project.create');
    }

    public function store(Request $request)
    {

        Project::create([
            'name' => $request->name,
            'retail_price' => $request->retail_price,
            'driver_price' => $request->driver_price,
        ]);

        return redirect()->route('project.');
    }

    public function edit($id)
    {
        $project = Project::find($id);

        return view('project.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        $project->name = $request->name;
        $project->retail_price = $request->retail_price;
        $project->driver_price = $request->driver_price;
        $project->save();

        return redirect()->route('project.');
    }

    public function delete($id)
    {
        try{
            $project = Project::find($id);

            $project->delete();

            return redirect()->route('project.');
        }catch (\Exception $e){
            return redirect()->route('project.')->with('alert', 'シフトに登録されている案件は、現在削除できない仕様にしてあります。ご了承ください。');
        }
    }

    public function csvImport(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row){
            Project::create([
                'name' => $row['name'],
                'retail_price' => $row['retail_rpice'],
                'driver_price' => $row['driver_price'],
            ]);
        }

        return redirect()->route('project.');
    }

    public function employeePayment($id)
    {
        $project = Project::find($id);
        $employees = Employee::all();

        return view('project.employeePayment', compact('project','employees'));
    }

    public function employeePaymentShow($id)
    {
        $project = Project::find($id);
        $payments = ProjectEmployeePayment::with('employee')
        ->where('project_id', $id)
        ->get();

        return view('project.employeePaymentShow', compact('project','payments'));
    }

    public function employeePaymentStore(Request $request, $id)
    {
        $types = $request->input('type');
        $prices = $request->input('price');

        $datas = ProjectEmployeePayment::where('project_id',$id)->get();
        $isCheck = false;
        foreach($datas as $data){
            foreach($types as $employee_id => $type){
                if($data->employee_id == $employee_id){
                    $isCheck = true;
                }
            }
        }
        if($isCheck){
            return redirect()->route('project.employeePaymentShow', ['id' => $id])->with('alert', '登録済みの従業員がいるため登録できません。登録済みの従業員は編集画面から修正してください。');
        }

        // 3. 従業員ごとの支払い情報を保存
        foreach ($types as $employee_id => $type) {
            $payment = new ProjectEmployeePayment();
            $payment->project_id = $id;         // プロジェクトIDを設定
            $payment->employee_id = $employee_id;        // 従業員IDを設定
            $payment->payment_type = $type;                      // 支払いタイプを設定
            $payment->amount = $prices[$employee_id] ?? null; // 価格を設定 (存在しない場合はnullを設定)
            $payment->save();                            // データベースに保存
        }

        return redirect()->route('project.employeePaymentShow', ['id' => $id]);
    }

    public function employeePaymentEdit($id,$employeeId)
    {
        $project = Project::find($id);
        $employees = Employee::all();
        $payments = ProjectEmployeePayment::with('employee')
        ->where('project_id', $id)
        ->where('employee_id', $employeeId)
        ->get();

        return view('project.employeePaymentEdit', compact('employees','project','payments'));
    }

    public function employeePaymentUpdate(Request $request,$id)
    {
        $payment = ProjectEmployeePayment::find($request->paymentId);

        $payment->payment_type = $request->type;
        $payment->amount = $request->amount;
        $payment->save();

        return redirect()->route('project.employeePaymentShow', ['id' => $id]);
    }
}
