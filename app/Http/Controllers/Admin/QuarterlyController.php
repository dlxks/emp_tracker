<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Banner;
use App\Models\Quarterly;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Termwind\Components\Dd;

class QuarterlyController extends Controller
{
    use Banner;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'record_id' => ['required'],
            'year' => ['required'],
            'quarter' => ['required'],
            'employed' => ['required', 'integer'],
            'total_graduates' => ['required', 'integer'],
        ]);

        if ($val->fails()) {
            $this->flash($val->errors()->first(), 'danger');
            return redirect()->back();
        }

        // Check if data will exceed number of graduates
        $record_data = Quarterly::where('record_id', $request['record_id'])
            ->get();
        $total_employed = 0;
        foreach ($record_data as $rdata) {
            $total_employed += $rdata->employed;
        }
        $total_employed += $request['employed'];

        if ($total_employed > $request['total_graduates']) {
            $this->flash('Total number of employed exceeded the number of total graduates.', 'danger');
            return redirect()->back();
        } else {
            // Compute for the percentage of graduate.
            $percentage = ($request['employed'] / $request['total_graduates']) * 100;
            $total_percentage = ($total_employed / $request['total_graduates']) * 100;

            // Storing quarterly
            $store_quarterly = Quarterly::updateOrCreate(
                [
                    'quarter' => $request['quarter'],
                    'year' => $request['year'],
                ],
                [
                    'record_id' => $request['record_id'],
                    'employed' => $request['employed'],
                    'percentage' => $percentage,
                ]
            );

            // Storing in records
            $fnd_record = Record::where('id', $request['record_id'])->first();
            $fnd_record->update([
                'total_employed' => $total_employed,
                'total_percentage' => $total_percentage,
            ]);

            $this->flash('New record added.', 'success');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quarterly  $quarterly
     * @return \Illuminate\Http\Response
     */
    public function show(Quarterly $quarterly)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quarterly  $quarterly
     * @return \Illuminate\Http\Response
     */
    public function edit(Quarterly $quarterly)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quarterly  $quarterly
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quarterly $quarterly)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quarterly  $quarterly
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Quarterly $quarterly)
    {
        // Get data of records
        $f_record = Record::where('id', $quarterly->record_id)
            ->first();
        // Subtract the number of employed in a quarter to the total number of employed in the records
        $n_employed = $f_record->total_employed - $quarterly->employed;
        // Compute for new percentage
        $n_percent = ($n_employed / $f_record->total_graduates) * 100;
        // Updating in records
        $fnd_record = Record::where('id', $request['record_id'])->first();
        $f_record->update([
            'total_employed' => $n_employed,
            'total_percentage' => $n_percent,
        ]);

        // Deleting quartely data
        $quarterly->delete();
        $this->flash('Data removed.', 'success');
        return redirect()->back();
    }
}
