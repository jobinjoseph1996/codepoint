<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Student;
use App\Models\Term;
use Illuminate\Http\Request;

class markController extends Controller
{
    public function index(){
        $studentMarks = Mark::all();
        return view('marks.list', compact('studentMarks'));
    }

    public function create(){
        $students = Student::pluck('id', 'name');
        $terms = Term::pluck('id', 'term');
        return view('marks.create', compact('students', 'terms'));
    }

    public function store(Request $request){
        $marks = request()->validate([
            'student_id' => ['required'],
            'term_id' => ['required'],
            'maths' => ['required', 'integer'],
            'science' => ['required', 'integer'],
            'computer' => ['required', 'integer'],
        ]); 
        Mark::create($marks);
        return redirect()->route('mark.index')
                         ->with('message', 'Marks added successfully');
    }

    public function edit($id){
        $studentsMark = Mark::find($id);
        $students = Student::pluck('id', 'name');
        $terms = Term::pluck('id', 'term');
        return view('marks.edit', compact('studentsMark', 'students', 'terms'));
    }

    public function update(Request $request, Mark $mark){
        $marks = request()->validate([
            'student_id' => ['required'],
            'term_id' => ['required'],
            'maths' => ['required', 'integer'],
            'science' => ['required', 'integer'],
            'computer' => ['required', 'integer'],
        ]); 
        $mark->update($marks);
        return redirect()->route('mark.index')
                         ->with('message', 'Marks Updated successfully');
    }

    public function destroy($id){
        $studentMark = Mark::find($id);
        $studentMark->delete();
        return redirect()->route('mark.index')->with('message', 'Marks deleted successfully');
    }
}
