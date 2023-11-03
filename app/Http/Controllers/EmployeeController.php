<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use League\Csv\Reader;
use League\Csv\Statement;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = Employee::all();

        return view('employee.index', compact('employees'));
    }

    public function create()
    {
        return view('employee.create');
    }

    public function store(Request $request)
    {

        Employee::create([
            'name' => $request->name,
        ]);

        return redirect()->route('employee.');
    }

    public function edit($id)
    {
        $employee = Employee::find($id);

        return view('employee.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        $employee->name = $request->name;
        $employee->save();

        return redirect()->route('employee.');
    }

    public function delete($id)
    {
        $employee = Employee::find($id);

        $employee->delete();

        return redirect()->route('employee.');
    }


    public function csvImport(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row){
            Employee::create([
                'name' => $row['name'],
            ]);
        }

        return redirect()->route('employee.');
    }

}
