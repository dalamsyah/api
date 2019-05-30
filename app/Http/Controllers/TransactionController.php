<?php

namespace App\Http\Controllers;
use App\Transaksi;
use App\PreTransaksi;

use App\THeader;
use App\TDetail;

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
	
	public function t_header(Request $request){
		
		$orderno = $this->hashOrder();
		$jamArr = explode(",", $request->input('jam_main'));
		
		$data = new THeader();
		$data->tanggal = $request->input('tanggal');
		$data->order_no = $orderno;
		$data->username = $request->input('username');
		$data->kode_tim = $request->input('kode_tim');
		$data->kode_lapangan = $request->input('kode_lapangan');
		$data->nama_lapangan = $request->input('nama_lapangan');
		$data->harga_lapangan = $request->input('harga_lapangan');
		$data->total_harga = $request->input('total_harga');
		$data->total_jam = count($jamArr);
		$data->status = $request->input('status');
		
		
		for( $i = 0; $i < count($jamArr); $i++){
			
			$det = new TDetail();
			$det->order_no = $orderno;
			$det->jam_main = $jamArr[$i];
			$det->harga = $request->input('harga_lapangan');
			$det->save();
			
		}
		
		$data->save();
		
		$t = array();
		return response()->json(array('message' => 'success', 'total' => count($t), 'result' => $t), 200);
		
	}
	
	public function pre_transaction(Request $request){
		
		$orderid = $this->getOrderId($request->input('jam_mulai'), $request->input('jam_selesai'), $request->input('tanggal'));
		
		$data = new PreTransaksi();
		$data->tanggal = $request->input('tanggal'); //08-05-2019
		$data->order_no = $orderid;
		$data->username = $request->input('username');
		$data->kode_tim = $request->input('kode_tim');
		$data->kode_lapangan = $request->input('kode_lapangan');
		$data->nama_lapangan = $request->input('nama_lapangan');
		$data->harga_lapangan = $request->input('harga_lapangan');
		$data->jam_main = $request->input('jam_main');
		$data->total_jam = $total_jam;
		$data->total_harga = $total_jam * $request->input('harga_lapangan');
		$data->status = 'waiting';
		
		$t = array();
		
		if($this->pre_check($orderid) < 1 ){
			$data->save();
			
			return response()->json(array('message' => 'success', 'total' => count($t), 'result' => $t), 200);
		}else{
			return response()->json(array('message' => 'already exist order id '.$orderid, 'total' => count($t), 'result' => $t), 200);
		}
		
	}
	
	public function pre_transaction2(Request $request){
		
		$jam1 = explode(":", $request->input('jam_mulai'));
		$jam2 = explode(":", $request->input('jam_selesai'));
		$total_jam = (int)$jam2[0] - (int)$jam1[0];
		
		$orderid = $this->getOrderId($request->input('jam_mulai'), $request->input('jam_selesai'), $request->input('tanggal'));
		
		$data = new PreTransaksi();
		$data->tanggal = $request->input('tanggal'); //08-05-2019
		$data->order_no = $orderid;
		$data->username = $request->input('username');
		$data->kode_tim = $request->input('kode_tim');
		$data->kode_lapangan = $request->input('kode_lapangan');
		$data->nama_lapangan = $request->input('nama_lapangan');
		$data->harga_lapangan = $request->input('harga_lapangan');
		$data->jam_mulai = $request->input('jam_mulai');
		$data->jam_selesai = $request->input('jam_selesai');
		$data->total_jam = $total_jam;
		$data->total_harga = $total_jam * $request->input('harga_lapangan');
		$data->status = 'waiting';
		
		$t = array();
		
		if($this->pre_check($orderid) < 1 ){
			$data->save();
			
			return response()->json(array('message' => 'success', 'total' => count($t), 'result' => $t), 200);
		}else{
			return response()->json(array('message' => 'already exist order id '.$orderid, 'total' => count($t), 'result' => $t), 200);
		}
		
		//return $this->res("success"); //response('Berhasil Tambah Data');
		
	}
	
	public function get(){
		//$response = App\Transaksi::all();
		//$t = App\Transaksi::orderBy('id', 'asc')->get();
		
		try {
			$t = app('db')->select("SELECT created_at FROM transaksi where tanggal ='".$tgl."' and (  ) ");
			
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
	
	public function getHasOrder(){
		return $this->hashOrder();
	}
	
	public function pre_check($orderno){

		
		//$orderno = 'RB-060819-0812'; //$request->input('order_no');
		$t = app('db')->select("SELECT id FROM pre_transaksi where order_no = '".$orderno."'");
		
		return count($t);
		
	}
	
	public function pre_update(Request $request){
		
		$orderno = $request->input('order_no');
		
		$update = app('db')->table('pre_transaksi')->where('order_no', $orderno)->update(['status' => 'success']);
		
		$t = array();
		
		return response()->json(array('message' => 'success update order no '.$orderno, 'total' => count($t), 'result' => $t), 200);
		
	}
	
	public function check_transaction($orderno){
		
		//$orderno = 'RB-060819-0812'; //$request->input('order_no');
		$t = app('db')->select("SELECT id FROM transaksi where order_no = '".$orderno."'");
		
		return count($t);
		
	}
	
	public function insert_transaction(){
		
		$data = new Transaksi();
		$data->tanggal = $request->input('tanggal'); //08-05-2019
		$data->order_no = $orderid;
		$data->username = $request->input('username');
		$data->kode_tim = $request->input('kode_tim');
		$data->kode_lapangan = $request->input('kode_lapangan');
		$data->nama_lapangan = $request->input('nama_lapangan');
		$data->harga_lapangan = $request->input('harga_lapangan');
		$data->jam_mulai = $request->input('jam_mulai');
		$data->jam_selesai = $request->input('jam_selesai');
		$data->total_jam = $total_jam;
		$data->total_harga = $total_jam * $request->input('harga_lapangan');
		$data->status = 'waiting';
		
		$t = array();
		
		if($this->pre_check($orderid) < 1 ){
			$data->save();
			
			return response()->json(array('message' => 'success', 'total' => count($t), 'result' => $t), 200);
		}else{
			return response()->json(array('message' => 'already exist order id '.$orderid, 'total' => count($t), 'result' => $t), 200);
		}
		
	}
	
	public function hashOrder2($len = 5){
	  $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	  $base = strlen($charset);
	  $result = '';

	  $now = explode(' ', microtime())[1];
	  while ($now >= $base){
		$i = $now % $base;
		$result = $charset[$i] . $result;
		$now /= $base;
	  }
	  return substr($result, -5);
	}
	
	public function hashOrder(){
		
		//SN-dWcz0C-
		
		//$len=5; $abc="aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789";
		
		//$letters = str_split($abc);
		//$str = "";
		//for ($i=0; $i<=$len; $i++) {
			//$str .= $letters[rand(0, count($letters)-1)];
		//};
		
		//return $str;
		
		return substr(sha1(mt_rand() . microtime()), mt_rand(0,35), 5);
	}
	
	public function getOrderId($jam_mulai, $jam_selesai, $tgl){
		//SN-080919-0809; senin
		//SL-080919-0810; selasa
		//RB
		//KM
		//JM
		//SB
		//MG
		
		$c = explode(":", $jam_mulai);
		$d = explode(":", $jam_selesai);
		
		$jam = $c[0].$d[0];
		
		$dt = Carbon::parse($tgl);
		
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
				$orderId = "SL-".$tgl."-".$jam;
			break;
			case 3:
				$orderId = "RB-".$tgl."-".$jam;
			break;
			case 4:
				$orderId = "KM-".$tgl."-".$jam;
			break;
			case 5:
				$orderId = "JM-".$tgl."-".$jam;
			break;
			case 6:
				$orderId = "SB-".$tgl."-".$jam;
			break;
			case 0:
				$orderId = "MG-".$tgl."-".$jam;
			break;
		}
		
		return $orderId; //'ORD' . sprintf('%06d', intval($number) + 1);
	}
	
	public function getMember($hari){
		
		$t = app('db')->select("SELECT * from member where hari = '".$hari."'");
		
		return response()->json(array('message' => 'success', 'total' => count($t), 'result' => $t), 200);
	}
	
}
