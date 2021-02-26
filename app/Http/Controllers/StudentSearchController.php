<?php
/*
Purpose: Return Student records based on first name, last name, classroom id or school id
Assumption: Either first name or last name will be provided at a time
            Avoided using 3rd Party Packages for filters.

*/

namespace App\Http\Controllers;

use App\Student;
use App\Classroom;
use Illuminate\Http\Request;

class StudentSearchController extends Controller
{
    public function search(Request $request)
    {
        $students = new student;
        try {
            // Construnt Eloquent Query based on query parms. Not using Dynamic SQL to prevent SQL injection

            if ($request->has('first_name')) {
                $students = Student::where('first_name', $request->first_name)->get();
            }

            if ($request->has('last_name')) {
                $students = Student::where('last_name', $request->last_name)->get();
            }

            if ($request->has('classroom_id')) {

                //Get  all students by classroom only
                if (!$request->has('last_name') && !$request->has('first_name')) {
                    $students = Student::where('classroom_id', $request->classroom_id)->get();
                } else // filter collection for pre-selected students
                    $students = $students->where('classroom_id', $request->classroom_id);
            }

            if ($request->has('school_id')) {
                $sid = $request->school_id;

                /*  SQL Statement - Fet All Students by School Id
                    select * from students
                     where classroom_id in ( select classroom_id from classrooms
                                             where school_id = :school_id)
                
                //col reference issues with this approach
                $students= Student::whereIn(
                    'classroom_id',
                    function ($query) use ($sid) {
                        $query->select('classroom_id')->from('classrooms')->whereColumn('school_id',$sid );
                    })->get();

                */
                // Divide and Conquer - query classrooms first 
                $cids =  Classroom::select('id')->where('school_id', $sid)->get();
                $students = Student::whereIn('classroom_id', $cids)->get();
                
                if ($request->has('first_name')) {
                    $students = $students->where('first_name', $request->first_name);
                }
                if ($request->has('last_name')) {
                    $students = $students->where('last_name', $request->last_name);
                }
                if ($request->has('classroom_id')) {
                    $students = $students->where('classroom_id', $request->classroom_id);
                }
            }

            // Add full name property
            if (count($students) > 0) {
                foreach ($students as $student) {
                    $full_name = $student['first_name'] . ' ' . $student['last_name'];
                    $student->full_name = $full_name;
                }
            }
            // Return response
            $result = ['status' => '200 (Ok)', 'message' => 'Students retrieved successfully', 'data' => ''];
            $result['data'] = $students;
            return response($result, 200);
        } catch (QueryException $ex) {
            $result['message'] = $ex->getMessage();
            return response($result, 400);
        }
    }
}
