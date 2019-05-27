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

    public function index(Request $request){
		
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
	
	public function getOrderId()
{
		//SN-080919-0809; senin
		//SL-080919-0810; selasa
		//RB
		//KM
		//JM
		//SB
		//MG
		
		// Get the last created order 
		/* $lastOrder = app('db')->select("SELECT created_at FROM transaksi order by created_at desc limit 1");

		if ( ! $lastOrder )

			$number = 0;
		else 
			$number = substr($lastOrder[0]->created_at, 3); */
		
		$tglFormat = "27-05-2019";

		$day = Carbon::createFromFormat('d-m-Y', $tglFormat)->dayOfWeek;
		$t = Carbon::createFromFormat('d-m-Y', $tglFormat)->day;
		$bln = Carbon::createFromFormat('d-m-Y', $tglFormat)->month;
		$thn = Carbon::createFromFormat('d-m-Y', $tglFormat)->year;
		
		$tgl = $t.$bln.$thn;
		
		$orderId = "";
		
		switch($day){
			case 1:
				$orderId = "SN-".$tgl;
			break;
			case 2:
				$orderId = "SL".$tgl;
			break;
			case 3:
				$orderId = "RB".$tgl;
			break;
			case 4:
				$orderId = "KM".$tgl;
			break;
			case 5:
				$orderId = "JM".$tgl;
			break;
			case 6:
				$orderId = "SB".$tgl;
			break;
			case 7:
				$orderId = "MG".$tgl;
			break;
		}
		
		return $orderId; //'ORD' . sprintf('%06d', intval($number) + 1);
	}
	
	protected function respond($status, $data = [])
    {
    	return response()->json($data, $this->statusCodes[$status]);
    }
	
}
