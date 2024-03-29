<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Banner;
use App\Models\Branch;
use App\Models\Quarterly;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RecordController extends Controller
{
    use Banner;

    // protected $quarterlies;

    // public function __construct(Quarterly $quarterlies)
    // {
    //     $this->quarterlies = $quarterlies;
    // }

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

        $chart_data = Record::with('branch')
            ->orderBy('year', 'asc')
            ->get();

        // Line Chart
        $d_employed = [];
        $d_percentage = [];
        $d_graduates = [];
        $labels = [];
        $colors = [];
        $colors2 = [];

        foreach ($chart_data as $row) {
            array_push($d_employed, $row->total_employed);
            array_push($d_percentage, $row->total_percentage . '');
            array_push($d_graduates, $row->total_graduates);
            array_push($labels, $row->branch->branch_name . ': ' . $row->year);
            if (!in_array($this->pickColor(), $colors)) {
                array_push($colors, $this->pickColor());
            }
            if (!in_array($this->pickColor2(), $colors2)) {
                array_push($colors2, $this->pickColor());
            }
        }

        $filtered = [
            'labels' => $labels,
            'employed' => $d_employed,
            'percentage' => $d_percentage,
            'graduates' => $d_graduates,
            'colors' => $colors,
            'colors2' => $colors2
        ];
        // End Line Chart

        // Doughnut Chart
        $t_years = [];

        foreach ($chart_data as $row) {

            if (in_array($row->year, $t_years)) {
                // 
            } else {
                array_push($t_years, $row->year);
            }

            // array_push($d_employed, $row->total_employed);
            // array_push($d_percentage, $row->total_percentage . '');
            // array_push($d_graduates, $row->total_graduates);
            // array_push($labels, $row->branch->branch_name . ': ' . $row->year);
            if (!in_array($this->pickColor(), $colors)) {
                array_push($colors, $this->pickColor());
            }
        }
        // End Doughnut Chart

        $perpage = $request->input('perpage') ?: 10;

        $branch_names = Branch::latest()
            ->where('branch_name', '!=', 'MAIN')
            ->orderBy('branch_name', 'asc')
            ->get();

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
            'chartdata' => $filtered,
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

        $quarterly_records = $record->quarterlies()->orderBy('quarter')->get();

        // For chart
        $n_employed = [];
        $n_percentage = [];
        $labels = [];
        $colors = [];

        foreach ($quarterly_records as $row) {
            array_push($n_employed, $row->employed);
            array_push($n_percentage, $row->percentage);
            array_push($labels, $row->quarter);
            if (!in_array($this->pickColor(), $colors)) {
                array_push($colors, $this->pickColor());
            }
        }

        $filtered = [
            'labels' => $labels,
            'employed' => $n_employed,
            'percentage' => $n_percentage,
            'colors' => $colors
        ];

        return Inertia::render('Admin/Record/Show', [
            'record' => $record,
            'branch' => $branch,
            'quarterlies' => $quarterly_records,
            'data' => $filtered,
        ]);

        // return Inertia::render('Task/Chart', ['data' => $this->getData()]);
    }

    // Random color
    public function pickColor()
    {
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $color = '#' . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . 'CC';
        return $color;
    }

    public function pickColor2()
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
