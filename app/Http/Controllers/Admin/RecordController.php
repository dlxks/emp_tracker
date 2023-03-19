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
            'field' => ['in:branch_name,year,total_graduates,total_employed,total_percentage'],
        ]);

        $data = Record::with('branch');
        $perpage = $request->input('perpage') ?: 25;

        $branch_names = Branch::latest()->orderBy('branch_name', 'asc')->get();

        // Search
        $search_keyword = request('search');
        if (request('search')) {
            $data->where(function ($data) use ($search_keyword) {
                $data->where('year', 'like', '%' . request('search') . '%')
                    ->orWhere('total_graduates', 'like', '%' . request('search') . '%')
                    ->orWhere('total_employed', 'like', '%' . request('search') . '%')
                    ->orWhere('total_percentage', 'like', '%' . request('search') . '%')
                    ->orwhereHas('branch', function ($subq) use ($search_keyword) {
                        $subq->where(function ($subq2) use ($search_keyword) {
                            $subq2->orWhere('branch_name', 'like', '%' . request('search') . '%');
                        });
                    });
            });
        }

        if (request()->has(['field', 'direction'])) {
            // $data->orderBy(request('field'), request('direction'))->get();
            $sortDirection = $request['direction'];

            $data->orderBy(request('field'), request('direction'))
                ->get();
        }

        if (request()->has(['branch_filter'])) {
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
        $branch = Branch::where('id', $record->branch_id)
            ->first();

        $querterly_records = $record->quarterlies()->orderBy('quarter')->get();

        return Inertia::render('Admin/Record/Show', [
            'record' => $record,
            'branch' => $branch,
            'quarterlies' => $querterly_records,
        ]);


        // return Inertia::render('Task/Chart', ['data' => $this->getData()]);
    }

    

    // Get data for chart
    public function getData()
    {
        // $rows = $this->tasks->join('users', 'tasks.created_by', '=', 'users.id')
        //     ->select(\DB::raw('users.name as label, count(tasks.status) as data'))
        //     ->where('tasks.status', 'Complete')
        //     ->groupBy('users.name')
        //     ->get();

        // $data = [];
        // $labels = [];
        // $colors = [];

        // foreach ($rows as $row) {
        //     array_push($data, $row->data);
        //     array_push($labels, $row->label);
        //     if (!in_array($this->pickColor(), $colors)) {
        //         array_push($colors, $this->pickColor());
        //     }
        // }

        // $filtered = [
        //     'labels' => $labels,
        //     'data' => $data,
        //     'colors' => $colors
        // ];

        // return $filtered;
    }

    // Random color
    public function pickColor()
    {
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $color = '#' . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)];
        return $color;
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
