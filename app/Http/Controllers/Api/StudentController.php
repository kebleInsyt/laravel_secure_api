<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Student::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "gender" => "required|in:male,female",
            "email" => "required|email|unique:students,email"
        ]);
        
        Student::create($data);

        return response()->json([
            "status" => true,
            "message" => "Student created successfully"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return response()->json([
            "status" => true,
            "data" => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {

        $request->validate([
            "name" => "sometimes|string",
            "gender" => "sometimes|in:male,female",
            "email" => "sometimes|email|unique:students,email," . $student->id
        ]);

        $student->update($request->all());

        return response()->json([
            "status" => true,
            "message" => "Student updated successfully",
            "data" => $student
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            "status" => true,
            "message" => "Student info deleted successfully",
        ]);
    }
}
