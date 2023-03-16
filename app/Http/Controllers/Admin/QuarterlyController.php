<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\Banner;
use App\Models\Quarterly;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

            // dd($total_percentage);

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

            // if ($store_quarterly->fails()) {
            //     $this->flash($store_quarterly->errors()->first(), 'danger');
            //     return redirect()->back();
            // } else {
            $this->flash('New record added.', 'success');
            return redirect()->back();
            // }


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
        $validator = Validator::make($request->all(), [
            'record_id' => ['required'],
            // 'year' => ['required'],
            'quarter' => ['required'],
            'employed' => ['required', 'integer'],
            'total_graduates' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            $this->flash($validator->errors()->first(), 'danger');
            return back();
        }

        $percentage = ($request['employed'] / $request['total_graduates']) * 100;

        $quarterly->update([
            'quarter' => $request['quarter'],
            // 'year' => $request['year'],
            'record_id' => $request['record_id'],
            'employed' => $request['employed'],
            'percentage' => $percentage,
        ]);

        $this->flash('Record updated.', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quarterly  $quarterly
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Quarterly $quarterly)
    {
        $f_record = Record::where('id', $quarterly->record_id)
            ->first();
        // $

        // $data = Quarterly::find($quarterly->id);
        // $data->delete();

        // $this->flash('Record removed.', 'success');

        // return redirect()->back();
    }
}
