<?php

namespace App\Http\Controllers;

use App\Models\country;
use App\Models\State;
use App\Models\Student;
use Illuminate\Http\Request;


class StudentController extends Controller
{

    public function index(){
        $studentData = Student::all();
        return view('students.list', compact('studentData'));
    }

    public function create(){
        $countries = country::pluck('name', 'id');
        return view('students.create', compact('countries'));
    }

    public function store(Request $request){       

        $student = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'dob' => ['before:today','required'],
            'gender' => ['required'],
            'mobile' => ['required','integer', 'digits:10'],
            'email' => ['email', 'nullable', 'unique:students'],
            'country' => ['required'],
            'state' => ['required'],
        ]); 
       
        Student::create($student);
        return redirect()->route('student.index')
                         ->with('message', 'Student added successfully');
    }

    public function edit(Student $student){
        $countries = country::pluck('name', 'id');
        $country = Country::find($student->country);
        $states = $country->States;
        return view('students.edit', compact('countries', 'student', 'states'));
    }

    public function update(Request $request, Student $student){
        $studentData = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'dob' => ['before:today','required'],
            'gender' => ['required'],
            'mobile' => ['required','integer', 'digits:10'],
            'email' => ['email', 'nullable', 'unique:students'],
            'country' => ['required'],
            'state' => ['required'],
        ]); 
        $student->update($studentData);
        return redirect()->route('student.index')
                         ->with('message', 'Student Updated successfully');
    }

    public function destroy($id){
        $student = Student::find($id);
        $student->studentMarks()->delete();
        $student->delete();
        return redirect()->route('student.index')
                         ->with('message', 'Student deleted successfully');
    }

    public function getStates(Request $request) {        
        $states = State::where('country_id', $request->country)->get();
        return $states;
    }
}
