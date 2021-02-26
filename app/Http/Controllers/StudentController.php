<?php
/*
  Purpose: Perform CRUD operations on Student Model
*/

namespace App\Http\Controllers;

use App\Student;
use App\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller
{
  //Get All Students
  public function index()
  {
    try {
      $students = new Student;
      $result = ['status' => '400 (Bad Request)', 'message' => '', 'data' => ''];
      $students = Student::get();
      $result['status'] = '200 (Ok)';
      $result['message'] = 'Students retrieved successfully';
      $result['data'] = $students;
      return response($result, 200);
    } catch (QueryException $ex) {
      $result['message'] = $ex->getMessage();
      return response($result, 400);
    }
  }

  // Update Student 
  public function store(Request $request)
  {
    try {
      $student = new Student;
      $student->first_name = $request->first_name;
      $student->last_name = $request->last_name;
      $student->classroom_id = $request->classroom_id;
      $student->student_number = $request->student_number;
      $student->save();

      return response()->json([
        "result" => "Successfully created student record!",
        "student" => $student
      ], 201);
    } catch (QueryException $ex) {
      $result['message'] = $ex->getMessage();
      return response($result, 400);
    }
  }

  // Get Student by id
  public function show($id)
  {
    $student = new Student;
    $classroom = new Classroom;
    $result = ['status' => '400 (Bad Request)', 'message' => '', 'data' => ''];

    if (Student::where('id', $id)->exists()) {
      $student = Student::where('id', $id)->get();
      $result['status'] = '200 (Ok)';
      $result['message'] = 'Student retrieved successfully';
      $cid = $student[0]->classroom_id;
      $classroom = Classroom::find($cid);
      $data['student']['Student_Info'] = $student;
      $data['student']['classroom'] = $classroom;

      $result['data'] = $data;
      return response($result, 200);
    } else {
      $result['status'] = '404 (Ok)';
      $result['message'] = 'Student not found';
      return response($result, 404);
    }
  }

  //Update student
  // Assumption: only id will be provided
  public function update(Request $request, $id)
  {
    $student = new Student;
    $result = ['status' => '400 (Bad Request)', 'message' => '', 'data' => ''];

    try {
      if (Student::where('id', $id)->exists()) {
        $student = Student::find($id);
        if ($request->has('first_name')) {
          $student->first_name = $request->first_name;
        }
        if ($request->has('last_name')) {
          $student->last_name = $request->last_name;
        }
        if ($request->has('classroom_id')) {
          //Validate classroom 
          if (Classroom::where('id', $request->classroom_id)->exists()) {
            $student->classroom_id = $request->classroom_id;
          }
        }
        $student->save();

        $result['status'] = '200 (Ok)';
        $result['message'] = 'Student updated successfully';
        $result['data'] = $student;
        return response($result, 200);
      } else {
        $result['status'] = '404 (Ok)';
        $result['message'] = 'Student not found';
        return response($result, 404);
      }
    } catch (QueryException $ex) {
      $result['message'] = $ex->getMessage();
      return response($result, 400);
    }
  }

  // Soft Delete student record
  public function destroy($id)
  {

    try {
      if (Student::where('id', $id)->exists()) {

        $student = Student::destroy($id);
        $student = Student::onlyTrashed()->where('id', $id)->get();

        $result['status'] = '200 (Ok)';
        $result['message'] = 'Student deleted successfully';
        $result['data'] = $student;
        return response($result, 200);
      } else {
        $result['status'] = '404 (Ok)';
        $result['message'] = 'Student not found';
        return response($result, 404);
      }
    } catch (QueryException $ex) {
      $result['message'] = $ex->getMessage();
      return response($result, 400);
    }
  }
}
