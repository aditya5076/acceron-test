<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        return  DB::transaction(function () use ($request) {
            return
                DB::table('employees')
                ->where('employee_id', 'LIKE', '%' . $request->search . '%')
                ->where('title',  'LIKE', '%' . $request->job_title . '%')
                ->where('department', 'LIKE', '%' . $request->department . '%')
                ->where('gender',  'LIKE', '%' . $request->gender . '%')
                ->where('country', 'LIKE', '%' . $request->country . '%')
                ->where('city', 'LIKE', '%' . $request->city . '%')
                ->whereBetween('hire_date', [$request->from_hiring_date, $request->to_hiring_date])
                ->orderBy('hire_date', 'asc')
                ->get();
        });
    }

    public function import(Request $request)
    {
        $fields = Validator::make($request->all(), [
            'sheet' => 'required'
        ]);

        if ($fields->fails()) return response()->json(['error' => $fields->errors()], 422);

        if ($request->hasFile('sheet')) {
            DB::transaction(function () use ($request) {
                $sheet = $request->file('sheet');

                $employees = (new FastExcel)->import($sheet, function ($line) {
                    return Employee::create([
                        'name' => $line['Full Name'],
                        'employee_id' => $line['Employee ID'],
                        'title' => $line['Job Title'],
                        'department' => $line['Department'],
                        'business_unit' => $line['Business Unit'],
                        'gender' => $line['Gender'],
                        'ethnicity' => $line['Ethnicity'],
                        'hire_date' => Carbon::parse($line['Hire Date'])->format('Y-m-d'),
                        'age' => $line['Age'],
                        'annual_salary' => $line['Annual Salary'],
                        'bonus' => $line['Bonus %'],
                        'country' => $line['Country'],
                        'city' => $line['City'],
                        'exit_date' => $line['Exit Date'],

                    ]);
                });
            });

            return response()->json(['success' => 'employees added!'], 201);
        }

        return response()->json(['error' => 'Incorrect file type'], 422);
    }
}
