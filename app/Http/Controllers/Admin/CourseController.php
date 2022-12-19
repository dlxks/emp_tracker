<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Banner;
use App\Models\Branch;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class CourseController extends Controller
{
    use Banner;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        request()->validate([
            'direction' => ['in:asc,desc'],
            'field' => ['in:course_name,course_desc'],
        ]);

        $data = Course::with('branch');
        $perpage = $request->input('perpage') ?: 25;

        $branch_names = Branch::latest()->orderBy('branch_name', 'asc')->get();

        if (request('search')) {
            $data->where('course_name', 'like', '%' . request('search') . '%')
                ->orWhere('course_desc', 'like', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $data->orderBy(request('field'), request('direction'))->get();
        }

        if (request()->has(['branch_filter'])) {
            // $data->where('courses.branches.branch_name',  request('branch_filter'));

            $searchTxt = request('branch_filter');

            $data->whereHas('branch', function ($data) use ($searchTxt) {
                $data->where('branch_name', 'like', '%' . $searchTxt . '%');
            });
        }

        return Inertia::render('Admin/Course/Index', [
            'courses' => $data->paginate($perpage)->withQueryString(),
            'filters' => request()->all(['search', 'field', 'direction', 'perpage', 'branch_filter']),
            'branches' => $branch_names,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $val = Validator::make($request->all(), [
            'branch' => ['required'],
            'course_name' => ['required', 'string', 'max:255', 'unique:courses'],
            'course_desc' => ['required', 'string', 'max:255'],
        ]);

        if ($val->fails()) {
            $this->flash($val->errors()->first(), 'danger');
            return back();
        }

        Course::create([
            'branch_id' => $request['branch']['id'],
            'course_name' => $request['course_name'],
            'course_desc' => $request['course_desc'],
        ]);

        $this->flash('New course added.', 'success');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'course_name' => ['required', 'string', 'max:255'],
            'course_desc' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            $this->flash($validator->errors()->first(), 'danger');
            return back();
        }

        $course->update([
            'branch_id' => $request['branch']['id'],
            'course_name' => $request['course_name'],
            'course_desc' => $request['course_desc'],
        ]);

        $this->flash('Course data updated.', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Course::find($id);
        $data->delete();

        $this->flash('Course removed.', 'success');

        return redirect()->back();
    }
}
