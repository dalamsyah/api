<?php

namespace App\Http\Controllers;
use App\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index2(Request $request){
		
		app('db')->transaction(function() {
			// DB work
		});
		
		//$results = app('db')->select("SELECT * FROM transaksi");
		
        $data = new Transaksi();
		$data->tanggal = $request->input('tanggal');
		$data->lapanganId = $request->input('lapanganId');
		$data->jam = $request->input('jam');
		$data->userId = $request->input('userId');
		$data->teamId = $request->input('teamId');
		$data->save();
		
		return response('Berhasil Tambah Data');
		
    }
	
	public function index(Request $request){
		
		$data = new Transaksi();
		$data->tanggal = $request->input('tanggal');
		$data->order_no = $request->input('order_no');
		$data->username = $request->input('username');
		$data->kode_tim = $request->input('kode_tim');
		$data->kode_lapangan = $request->input('kode_lapangan');
		$data->nama_lapangan = $request->input('nama_lapangan');
		$data->harga_lapangan = $request->input('harga_lapangan');
		$data->total_harga = $request->input('total_harga');
		$data->total_jam = $request->input('total_jam');
		$data->jam_mulai = $request->input('jam_mulai');
		$data->jam_selesai = $request->input('jam_selesai');
		$data->save();
		
		$t = array();
		
		return response()->json(array('message' => 'success', 'total' => count($t), 'result' => $t), 200);
		
		//return $this->res("success"); //response('Berhasil Tambah Data');
		
	}
	
	public function get(){
		//$response = App\Transaksi::all();
		//$t = App\Transaksi::orderBy('id', 'asc')->get();
		
		try {
			$t = app('db')->select("SELECT created_at FROM transaksi order by created_at desc limit 1");
			
		} catch ( \Exception $ex) { // Anything that went wrong
		
			return response()->json(
				array(
					'status' => app('Illuminate\Http\Response')->status(), 
					'message' => $ex->getMessage() , 
					'total' => 0, 
					'result' => []
					), 200);
			
		}
		
		return response()->json(array('status' => app('Illuminate\Http\Response')->status(), 'message' => 'success', 'total' => count($t), 'result' => $t), 200);
	}
	
	public function getOrderId($jam, $tgl)
{
		//SN-080919-0809; senin
		//SL-080919-0810; selasa
		//RB
		//KM
		//JM
		//SB
		//MG
		
		$jam = "0810";
		
		$dt = Carbon::parse('2019-05-27');
		
		$day = $dt->isoFormat('d');
		
		$t = $dt->isoFormat('MM');
		$b = $dt->isoFormat('DD');
		$y = $dt->isoFormat('YY');
		
		$tgl = $t.$b.$y;
		
		$orderId = "";
		
		switch($day){
			case 1:
				$orderId = "SN-".$tgl."-".$jam;
			break;
			case 2:
				$orderId = "SL".$tgl."-".$jam;
			break;
			case 3:
				$orderId = "RB".$tgl."-".$jam;
			break;
			case 4:
				$orderId = "KM".$tgl."-".$jam;
			break;
			case 5:
				$orderId = "JM".$tgl."-".$jam;
			break;
			case 6:
				$orderId = "SB".$tgl."-".$jam;
			break;
			case 0:
				$orderId = "MG".$tgl."-".$jam;
			break;
		}
		
		return $orderId; //'ORD' . sprintf('%06d', intval($number) + 1);
	}
	
	public function res($msg, $data = [])
    {
    	return response()->json($data, $msg, app('Illuminate\Http\Response')->status());
    }
	
}
