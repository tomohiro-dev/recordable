<?php

namespace App\Http\Controllers;

use App\Timer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TimerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Timer::mine()->orderBy('started_at', 'desc')->paginate(20)->toArray();
    }

    public function indexMonth() {
        $this_year = Carbon::today('Asia/Tokyo')->year;
        $this_month = Carbon::today('Asia/Tokyo')->month;
        return Timer::mine()
        ->whereYear('started_at', $this_year)
        ->whereMonth('started_at', $this_month)
        ->get();
    }
    //TODO: ユーザーのTimezone毎に時間が対応するように設定したい（作成時はAsia/Tokyo）

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
        $timer =  Timer::mine()->create([
            'name' => $data['name'],
            'memo' => $data['memo'],
            'category_id' => $data['category_id'],
            'category_name' => $data['category_name'],
            'category_color' => $data['category_color'],
            'user_id' => Auth::user()->id,
            'started_at' => new Carbon,
            'stopped_at' => null,
        ]);

        return $timer;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}