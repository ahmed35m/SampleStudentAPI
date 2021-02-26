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
    }
}
