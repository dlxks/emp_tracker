<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Banner;
use App\Models\Branch;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RecordController extends Controller
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

        $data = Record::with('branch');
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

        return Inertia::render('Admin/Record/Index', [
            'records' => $data->paginate($perpage)->withQueryString(),
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
            'year' => ['required'],
            'total_graduates' => ['required', 'integer'],
        ]);

        if ($val->fails()) {
            $this->flash($val->errors()->first(), 'danger');
            return back();
        }

        Record::create([
            'branch_id' => $request['branch']['id'],
            'year' => $request['year'],
            'total_graduates' => $request['total_graduates'],
        ]);

        $this->flash('New record added.', 'success');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function show(Record $record)
    {
        return Inertia::render('Admin/Record/Show', [
            'exam' => [
                'id' => $record->id,
                'subject' => $record->subject,
                'exam_code' => $record->exam_code,
                // 'quarterly' => $record->questions()->get()->map->only('exam_id', 'id', 'question'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function edit(Record $record)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record $record)
    {
        $validator = Validator::make($request->all(), [
            'branch' => ['required'],
            'year' => ['required'],
            'total_graduates' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            $this->flash($validator->errors()->first(), 'danger');
            return back();
        }

        $record->update([
            'branch_id' => $request['branch']['id'],
            'year' => $request['year'],
            'total_graduates' => $request['total_graduates'],
        ]);

        $this->flash('Record updated.', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Record::find($id);
        $data->delete();

        $this->flash('Record removed.', 'success');

        return redirect()->back();
    }
}
