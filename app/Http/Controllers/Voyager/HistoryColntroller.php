<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CicLog;
use App\User;
use App\HistoryLog_View;
use App\HistoryLog;
use Mail;
use App\Mail\SendMail;
use App\Mail\SendMailToMafc;
use App\LogsStatus;
use App\UserTax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Lender;
use App\Provincial;
use App\LenderResults;
use App\UserGroup;


class HistoryColntroller extends Controller
{

    public function index(){
        if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;

        $logsstatus = LogsStatus::get();
        if ($role_id == 7) {
            $history = HistoryLog_View::where('history_log_id','!=',null)->orderBy('created_at','desc')->get();
        }
        else if($role_id !== 1 && $role_id !== 4 && $role_id !== 5 && $role_id !== 6)
        {
            $history = HistoryLog_View::where('user_ref',$user_id)->orderBy('created_at','desc')->get();
        }
        else
        {
            $history = HistoryLog_View::orderBy('created_at','desc')->get();
        }
        if ($role_id !== 1 && $role_id !== 4 && $role_id !== 6) {
            $total_chichamdiem = HistoryLog_View::where([['progress_info','=',0],['history_log_id','=',null],['user_ref','=',$user_id]])->count();
            $total_capnhat = HistoryLog_View::where([['progress_info','>',0],['history_log_id','=',null],['user_ref','=',$user_id]])->count();
            $total_gui = HistoryLog_View::where([['history_log_id','!=',null],['user_ref','=',$user_id]])->count();
        }
        else
        {
            $total_chichamdiem = HistoryLog_View::where([['progress_info','=',0],['history_log_id','=',null]])->count();
            $total_capnhat = HistoryLog_View::where([['progress_info','>',0],['history_log_id','=',null]])->count();
            $total_gui = HistoryLog_View::where([['progress_info','>',0],['history_log_id','!=',null]])->count();
        }
        // return $history;
        $team_cs = $this->team_cs();
		return view('admin.history.browse',compact('history','logsstatus','total_chichamdiem','total_capnhat','total_gui','team_cs'));

    }



    public function team_cs()
    {
        return UserGroup::get();
    }
    /**
    *Update cic note admin
    *@param $form_date1 , $to_date1 , $form_date1 , $to_date1 , $status
    *@return $data
    **/
    public function search_data_history_month($from , $to)
    {
        if(!Auth::user()){
            $this->redirect(); 
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        if ($role_id !== 1 && $role_id !== 4) {
            $total_chichamdiem = HistoryLog_View::where([['progress_info','=',0],['history_log_id','=',null],['user_ref','=',$user_id]])->count();
            $total_capnhat = HistoryLog_View::where([['progress_info','>',0],['history_log_id','=',null],['user_ref','=',$user_id]])->count();
            $total_gui = HistoryLog_View::where([['history_log_id','!=',null],['user_ref','=',$user_id]])->count();
        }
        else
        {
            $total_chichamdiem = HistoryLog_View::where([['progress_info','=',0],['history_log_id','=',null]])->count();
            $total_capnhat = HistoryLog_View::where([['progress_info','>',0],['history_log_id','=',null]])->count();
            $total_gui = HistoryLog_View::where([['progress_info','>',0],['history_log_id','!=',null]])->count();
        }
        $logsstatus = LogsStatus::get();
        $history = HistoryLog_View::where([['created_at','>=',$from],['created_at','<=',$to]])
                                        ->orderBy('ngay_gui_ho_so','created_at','desc')
                                        ->get();
        $team_cs = $this->team_cs();
        return view('admin.history.browse',compact('history','logsstatus','total_chichamdiem','total_capnhat','total_gui','team_cs'));
    }


    /**
    *Update cic note admin
    *@param $form_date1 , $to_date1 , $form_date1 , $to_date1 , $status
    *@return $data
    **/
    public function search_data_history(Request $req)
    {
        $logsstatus = LogsStatus::get();
        $key = $req->input('text_search');
        $from_date_TN = $req->input('from_date_tn');
        $to_date_TN = $req->input('to_date_tn');
        $from_date_TC = $req->input('from_date_tc');
        $to_date_TC = $req->input('to_date_tc');
        $status = $req->input('status');
        $status_profile = $req->input('status_profile');

        if(!Auth::user()){
            $this->redirect();    
        }
        $role_id = Auth::user()->role_id;
        $user_id = Auth::user()->id;
        if ($status != "") {
           if ($status_profile == "") {

                $history = HistoryLog_View::where([['trangthai',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['cmnd','like','%'.$key.'%']])
                                        ->orWhere([['trangthai',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['phone','like','%'.$key.'%']])
                                        ->orWhere([['status',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['trangthai',null],['cmnd','like','%'.$key.'%']])
                                        ->orWhere([['status',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['trangthai',null],['phone','like','%'.$key.'%']])
                                        ->orderBy('ngay_gui_ho_so','created_at','desc')
                                        ->get();
           }
           else
           {
            if ($status_profile == "100c") {
                $history = HistoryLog_View::where([['trangthai','=',$status],['created_at','>=',$from_date_TN],['cmnd','like','%'.$key.'%'],['created_at','<=',$to_date_TN],['history_log_id','==',null],['progress_info','>=',70]])
                                        ->orWhere([['trangthai','=',$status],['created_at','>=',$from_date_TN],['phone','like','%'.$key.'%'],['created_at','<=',$to_date_TN],['history_log_id','=',null],['progress_info','>=',70]])
                                        ->orWhere([['status','=',$status],['created_at','>=',$from_date_TN],['cmnd','like','%'.$key.'%'],['created_at','<=',$to_date_TN],['history_log_id','=',null],['progress_info','>=',70]])
                                        ->orWhere([['status','=',$status],['created_at','>=',$from_date_TN],['phone','like','%'.$key.'%'],['created_at','<=',$to_date_TN],['history_log_id','=',null],['progress_info','>=',70]])
                                        ->orderBy('ngay_gui_ho_so','created_at','desc')
                                        ->get();
            }
            else if ($status_profile == "100d") {
                $history = HistoryLog_View::where([['trangthai','=',$status],['created_at','>=',$from_date_TN],['cmnd','like','%'.$key.'%'],['created_at','<=',$to_date_TN],['history_log_id','!=',null]])
                                        ->orWhere([['trangthai','=',$status],['created_at','>=',$from_date_TN],['phone','like','%'.$key.'%'],['created_at','<=',$to_date_TN],['history_log_id','!=',null]])
                                        ->orWhere([['status','=',$status],['created_at','>=',$from_date_TN],['phone','like','%'.$key.'%'],['created_at','<=',$to_date_TN],['history_log_id','!=',null]])
                                        ->orWhere([['status','=',$status],['created_at','>=',$from_date_TN],['cmnd','like','%'.$key.'%'],['created_at','<=',$to_date_TN],['history_log_id','!=',null]])
                                        ->orderBy('ngay_gui_ho_so','created_at','desc')
                                        ->get();
            }
            else if ($status_profile == "70") {
                $history = HistoryLog_View::where([['trangthai','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['cmnd','like','%'.$key.'%'],['history_log_id','!=',null],['progress_info','>',0],['progress_info','<',70]])
                                        ->orWhere([['trangthai','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['phone','like','%'.$key.'%'],['history_log_id','!=',null],['progress_info','>',0],['progress_info','<',70]])
                                        ->orWhere([['status','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['phone','like','%'.$key.'%'],['history_log_id','!=',null],['progress_info','>',0],['progress_info','<',70]])
                                        ->orWhere([['status','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['cmnd','like','%'.$key.'%'],['history_log_id','!=',null],['progress_info','>',0],['progress_info','<',70]])
                                        ->orderBy('ngay_gui_ho_so','created_at','desc')
                                        ->get();
            }
            else
            {
                $history = HistoryLog_View::where([['trangthai','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['cmnd','like','%'.$key.'%'],['history_log_id','!=',null],['progress_info','=',0]])
                                        ->orWhere([['trangthai','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['phone','like','%'.$key.'%'],['history_log_id','=',null],['progress_info','=',0]])
                                        ->orWhere([['status','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['cmnd','like','%'.$key.'%'],['history_log_id','!=',null],['progress_info','=',0]])
                                        ->orWhere([['status','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['phone','like','%'.$key.'%'],['history_log_id','=',null],['progress_info','=',0]])
                                        ->orderBy('ngay_gui_ho_so','created_at','desc')
                                        ->get();
            }
           }
        }
        else
        {
            if ($status_profile != "") {
                if ($status_profile == "100c") {
                $history = HistoryLog_View::where([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['cmnd','like','%'.$key.'%'],['history_log_id','==',null],['progress_info','>=',70]])
                                        ->orWhere([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['phone','like','%'.$key.'%'],['history_log_id','=',null],['progress_info','>=',70]])
                                        ->orderBy('ngay_gui_ho_so','created_at','desc')
                                        ->get();
                }
                else if ($status_profile == "100d") {
                    $history = HistoryLog_View::where([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['cmnd','like','%'.$key.'%'],['history_log_id','!=',null]])
                                            ->orWhere([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['phone','like','%'.$key.'%'],['history_log_id','!=',null]])
                                            ->orderBy('ngay_gui_ho_so','created_at','desc')
                                            ->get();
                }
                else if ($status_profile == "70") {
                    $history = HistoryLog_View::where([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['history_log_id','!=',null],['cmnd','like','%'.$key.'%'],['progress_info','>',0],['progress_info','<',70]])
                                            ->orWhere([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['history_log_id','!=',null],['phone','like','%'.$key.'%'],['progress_info','>',0],['progress_info','<',70]])
                                            ->orderBy('ngay_gui_ho_so','created_at','desc')
                                            ->get();
                }
                else
                {
                    $history = HistoryLog_View::where([['trangthai','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['history_log_id','!=',null],['cmnd','like','%'.$key.'%'],['progress_info','<=',$status_profile]])
                                            ->orWhere([['trangthai','=',$status],['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['history_log_id','=',null],['phone','like','%'.$key.'%'],['progress_info','<=',$status_profile]])
                                            ->orWhere([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['history_log_id','!=',null],['cmnd','like','%'.$key.'%'],['progress_info','<=',$status_profile]])
                                            ->orWhere([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['history_log_id','=',null],['phone','like','%'.$key.'%'],['progress_info','<=',$status_profile]])
                                            ->orderBy('ngay_gui_ho_so','created_at','desc')
                                            ->get();
                }
           }
           else
           {
                $history = HistoryLog_View::where([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['cmnd','like','%'.$key.'%']]) 
                                        ->orWhere([['created_at','>=',$from_date_TN],['created_at','<=',$to_date_TN],['phone','like','%'.$key.'%']])
                                        ->orderBy('ngay_gui_ho_so','created_at','desc')
                                        ->get();
           }
        }
        if ($role_id !== 1 && $role_id !== 4) {
            $total_chichamdiem = HistoryLog_View::where([['progress_info','=',0],['history_log_id','=',null],['user_ref','=',$user_id]])->count();
            $total_capnhat = HistoryLog_View::where([['progress_info','>',0],['history_log_id','=',null],['user_ref','=',$user_id]])->count();
            $total_gui = HistoryLog_View::where([['progress_info','>',0],['history_log_id','!=',null],['user_ref','=',$user_id]])->count();
        }
        else
        {
            $total_chichamdiem = HistoryLog_View::where([['progress_info','=',0],['history_log_id','=',null]])->count();
            $total_capnhat = HistoryLog_View::where([['progress_info','>',0],['history_log_id','=',null]])->count();
            $total_gui = HistoryLog_View::where([['progress_info','>',0],['history_log_id','!=',null]])->count();
        }
        $team_cs = $this->team_cs();
       return view('admin.history.browse',compact('history','logsstatus','status','status_profile','from_date_TN','to_date_TN','from_date_TC','to_date_TC','total_chichamdiem','total_capnhat','total_gui','team_cs'));
    }
    /**
    *Update cic note admin
    *@param $id , $cmnd
    *@return data [user_tax,logsstatus,history_log]
    */
    public function view_history_log($id,$cmnd)
    {   
        $lender_results = LenderResults::where('log_id','=',$id)->orderBy('created_at','desc')->get();
        $user_tax = UserTax::where('cmnd','=',$cmnd)->get();
        $logsstatus = LogsStatus::get();
        $tctd = Lender::get();
    	$history_log = HistoryLog_View::where([['history_log_id','=',$id],['cmnd','=',$cmnd]])->orWhere([['id','=',$id],['cmnd','=',$cmnd]])->limit(1)->get();
        // return $lender_results[];
        return view('admin.history.edit-add',compact('history_log','logsstatus','user_tax', 'tctd','lender_results'));
    }

    /**
    *Update cic note admin || hoang custom
    *@param log , $cic , $id ,data ,email
    *@return true || false
    */ 
    public function update_log($log , $cic , $id ,$data , $email , $data_send_mail)
    {
        if ($log !== null) {
            $save = HistoryLog::find($id)->update($data);
            if ($save === true) {
                if ($email != "") {
                    Mail::to($email)->queue(new SendMail($data_send_mail));
                } 
                return true;
            }
            else
            {
                return false;
            }
        }
        elseif($cic !== null)
        {
            $save_c = CicLog::find($id)->update($data);
            if ($save_c === true) {
                if ($email != "") {
                    Mail::to($email)->queue(new SendMail($data_send_mail));
                }
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    /**
    *Update cic note admin || hoang custom
    *@param Request , $id , $cmnd
    *@return save data to history_log or cic_log
    */ 
    public function update_note_admin(Request $req , $id , $cm)
    {

        if(!Auth::user()){
            $this->redirect();    
        }
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $param = $req->all();
        $chk = $req->input('to_chuc_tin_dung');
        ($chk != "") ?  $tctd_id = $chk : $tctd_id = "";

        $log = HistoryLog::find($id);
        $cic = CicLog::find($id);

        $data = ['hoten' => $param['hoten'],
                 'email'=> $param['email'],
                 'cmnd' => $param['cmnd'],
                 'phone' => $param['sdt'],
                 'status' => $param['status'],
                 'note' => $param['admin_note']
                ];
        // return $data;
        $update_log = array(
                 'tctd_id' => $tctd_id,
                 'notes'=> $param['admin_note'],
                 'status' => $param['status'],
                 'cs_id' => $user_id,
                 'cs_name' => $user_name
                    );
        $count = LenderResults::where('log_id',$id)->count();
        $data_1 = LenderResults::where('log_id',$id)->orderBy('created_at','desc')->get();

        if ($param['status'] == 2 ) {
            if ($count == 0) {
                echo "<script>alert('Hồ sơ này chưa được gửi đến TCTD nào! Thao tác này không thực hiện được');</script>";
                return redirect('admin/history/view-edit/'.$id.'/'.$param['cmnd'].'');  
            }
            if ($count > 0 && $data_1[0]['status'] !== 2) {
                echo "<script>alert('Hồ sơ này chưa được TCTD duyệt! Thao tác này không thực hiện được');</script>";
                return redirect('admin/history/view-edit/'.$id.'/'.$param['cmnd'].'');  
            }
            if ($count > 0 && $data_1[0]['status'] === 2) {
                if ($this->update_log($log,$cic,$id,$update_log,$param['email'],$data)) {
                    return redirect('admin/history');  
                }
                else
                {
                    echo "<script>alert('Không thành công !');</script>";
                    return redirect('admin/history/view-edit/'.$id.'/'.$param['cmnd'].''); 
                }
            }
        }
        else
        {
            if ($this->update_log($log,$cic,$id,$update_log,$param['email'],$data)) {
                return redirect('admin/history');  
            }
            else
            {
                echo "<script>alert('Không thành công !');</script>";
                return redirect('admin/history/view-edit/'.$id.'/'.$param['cmnd'].''); 
            }
        }

    }




    /******************************************************************/
    /******************************************************************/
    /****************Function get data API - GET ********************/
    /******************************************************************/
    /******************************************************************/
    public function curl_invoke_tax_get($url,$header)
    {

        $ch = curl_init ($url);
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 80 );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );

        $str = curl_exec ( $ch );
        curl_close ( $ch );
        $results = json_decode($str,true);

        return $results;
    }
    /******************************************************************/
    /******************************************************************/
    /****************Function get data API - POST ********************/
    /******************************************************************/
    /******************************************************************/
    public function curl_invoke_tax($url,$data){
        // return $this->build_post_fields($data);
        // return $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // SSL important
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);
        $results = json_decode($response,true);

        return $results;
    }


    /******************************************************************************/
    /******************************************************************************/
    /*******************Function gửi dữ liệu đến api tctd  ************************/
    /******************************************************************************/
    /***************************************************************************
    1   OCB
    2   Mirae Asset
    3   Home Credit
    4   FE Credit
    5   VPBank
    */
    public function get_data_api_partner($id_tctd,$data)
    {
        $url_1 = 'http://creditnow.vn/api/api/v2/sendLead';
        $url_2 = '';
        $url_3 = '';
        $url_4 = '';
        $url_5 = '';
        $url_6 = '';

        if ($id_tctd == 1) {
            $data = $this->curl_invoke_tax($url_1,$data);
            if ($data['Code'] !== 200) {
                return $data['Message'];
            }
            else
            {
                return $data['Data'];
            }
        }
    }    
    /******************************************************************/
    /******************************************************************/
    /*******************Function TÍNH TUỔI age  ************************/
    /******************************************************************/
    /******************************************************************/
    public function calcu_age($data)
    {
        $created_at = strtotime($data);
        $current_time = strtotime(date('Y-m-d H:i:s'));
        $interval  = abs($current_time - $created_at);
        $age  = round($interval / ((60*60*24*30*7) + (60*60*24*31*5)));  
        return $age;
    }

    /******************************************************************/
    /******************************************************************/
    /*******************Function CHECK ĐỊA CHỈ TCTD HT*******************/
    /******************************************************************/
    /******************************************************************/
    public function check_addr($code, $data)
    {
        for ($i=0; $i < count($data); $i++) { 
            if($code == $data[$i])
            {
               return false;
            }
            else
            {
                return true;
            }
        }
    }


    /******************************************************************/
    /******************************************************************/
    /*******************Function tính ngày*******************/
    /******************************************************************/
    /******************************************************************/
    public function calcu_day($date)
    {
        $created_at = strtotime($date);
        $current_time = strtotime(date('Y-m-d H:i:s'));
        $interval  = abs($current_time - $created_at);
        $day  = round($interval / (60*60*24));
        return $day;
    }
    /**
    *Update cic note admin || hoang custom
    *@param Request
    *@return save data to table LenderResults
    */ 
    public function send_credit_inst(Request $req)
    {
        $param   =  $req->all();
        // return $param;
        $province = Provincial::where('matp',$param['addr_id_send'])->get();
        (isset($province[0]['province_id']) && $province[0]['province_id']) ? $code = $province[0]['province_id'] : $code ="";

        $tctd_id =  $param['tctd_send'] ;
        $log_id  =  $param['log_send'];
        $log_qr = $param['log_id'];
        $cmnd    =  $param['cmnd_send'];
        $phone    =  $param['phone_send'];
        $name    =  $param['name_send'];
        $loan = str_replace(',','',$param['loan']);

        $data_check_dk = CicLog::where('id',$log_qr)->orderBy('created_at','desc')->get();
        /*Tính tuổi người vay*/
        if ($data_check_dk[0]['age'] !== '1770-01-01 00:00:00') {
            $age = $this->calcu_age($data_check_dk[0]['age']);
        }
        else
        {
            $age = 0;
        }
        $results = substr($data_check_dk[0]['cid_result'], 0,-11);  

        /***********************dữ liệu gửi đến tctd************************/
        if ((int)$tctd_id == 1) {
            $data  = array(
                        'User' => 'VE70000028',
                        'Pass' => 'Lemon@123!',
                        'Phonenumber'   => $phone,
                        'Source'   => 'SMS',
                        'NationalId'   => $cmnd,
                        'FullName'   => '',
                        'Province'   => $code,
                        'CampaignCode' => 'OCB',
                        'MetaData' => json_encode(array(
                                            'RequestLoanAmount' => $loan
                                        ))
                    );
            $data_send = array(
                                'cmnd' => $cmnd,
                                'results' => $results,
                                'code' => $code,
                                'age' => $age,
                                'data' => $data,
                                'tctd_id' => $tctd_id,
                                'log_id' => $log_id
                            );
        }
        if ((int)$tctd_id == 2) {
            $data_m_a = array(
                            'cmnd' => $cmnd,
                            'phone' => $phone,
                            'cmnd' => $cmnd,
                            'tctd_id' => $tctd_id,
                            'log_id' => $log_id
                        );
            $data_mail = array(
                                'hoten' => $name ,
                                'cmnd' => $cmnd ,
                                'phone' => $phone ,
                                'khoan_vay' => $param['loan'],
                                'addr' => $param['addr_send_u'],
                                'age' => $this->calcu_age($param['age_send']),
                                'thunhap' => $param['thunhap_send']
                            );
        }
        // return $data_mail;
        /********************************************************************/
        $count = LenderResults::where([['log_id',$log_id],['tctd_id',$tctd_id]])->count();
        $data_check_iset = LenderResults::where('log_id',$log_id)->orderBy('created_at','desc')->get();
        $api_tctd = Lender::select('api')->where('id',$tctd_id)->limit(1)->get();
        $isset_history = HistoryLog_View::where([['history_log_id',$log_id],['cmnd',$cmnd]])->count();

        if ($api_tctd[0]['api'] == 0) { /*check nếu tctd chưa kết nối api thì thông báo */
            echo "<script>alert('Hiện tại chưa kết nối API với TCTD này.');</script>";
            return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
        }
        else /*nếu đã kết nối thì tiếp tục*/
        {
            if ($isset_history == 0) { /*kiểm tra profile này đã gửi hồ sơ vay hay chưa || trường hợp chưa*/
                echo "<script>alert('Hồ sơ này chưa gửi yêu cầu vay! không được chuyển đến tổ chức tín dụng.');</script>";
                return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
            }
            else /*nếu đã gửi hỗ sơ vay*/
            {
                if (isset($data_check_iset[0])) /*nếu tồn tại dữ liệu rồi*/
                {
                    $created_at = strtotime($data_check_iset[0]['created_at']);
                    $current_time = strtotime(date('Y-m-d H:i:s'));
                    $interval  = abs($current_time - $created_at);
                    $day  = round($interval / (60*60*24));
                    if ($count > 0 && $day < 60) /*đã gửi đến 1 tổ chức tín dụng và thời gian gửi nhỏ hơn 60 ngày*/
                    {
                        echo "<script>alert('Hồ sơ này đã chuyển đến tổ chức tín dụng! Vui lòng chờ để biết kết quả. Nếu hồ sơ quá 60 ngày mà TCTD không phản hồi có thể chuyển đến TCTD khác');</script>";
                        return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
                    }
                    if ($count > 0 && $data_check_iset[0]['status'] == 2) /*đã gửi đến 1 tổ chức tín dụng và đã được duyệt vay */
                    {
                        echo "<script>alert('Hồ sơ đã được TCTD duyệt vay! Không được gửi đến 1 TCTD khác');</script>";
                        return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
                    }
                    if ($count === 0 && $day < 60 && $data_check_iset[0]['status'] !== 2)  /*chưa gửi đến 1 tổ chức tín dụng chưa đủ 60 ngày chưa đc duyệt */
                    {
                        echo "<script>alert('Hồ sơ này đã chuyển đến tổ chức tín dụng! Vui lòng chờ để biết kết quả. Nếu hồ sơ quá 60 ngày mà TCTD không phản hồi có thể chuyển đến TCTD khác');</script>";
                        return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
                    }
                    if ($count > 0 && $day > 60 && $data_check_iset[0]['status'] !== 2)  /*chưa gửi đến 1 tổ chức tín dụng chưa đủ 60 ngày chưa đc duyệt */
                    {
                        echo "<script>alert('Hồ sơ này đã chuyển đến TCTD này và không được duyệt! Vui lòng gửi đến TCTD khác.');</script>";
                        return redirect('admin/history/view-edit/'.$log_id.'/'.$cmnd.'');  
                    }
                    if ($count === 0 && $day > 60 && $data_check_iset[0]['status'] !== 2) /*chưa gửi đến 1 tổ chức tín dụng đã đủ 60 ngày chưa đc duyệt */
                    {
                       if ((int)$tctd_id == 1) {
                            return $this->check_conditions_ocb($data_send);
                        }
                        if ((int)$tctd_id == 2) {
                            return $this->check_conditions_mirae_asset($data_m_a,$data_mail);
                        }
                    }
                }
                else /*chưa tồn tại dữ liệu*/
                {
                    if ((int)$tctd_id == 1) { /*start api ocb*/
                        return $this->check_conditions_ocb($data_send);
                    } /*end api ocb*/
                    if ((int)$tctd_id == 2) {
                        return $this->check_conditions_mirae_asset($data_m_a,$data_mail);
                    }  
                }
            }
        }
    }

    /**
    * check điều kiện và gửi dữ liệu tại ocb
    *@param 
    *@return  
    */
    public function check_conditions_ocb($param)
    {
        $check_pronvince_ocb = array('1000','2100','1850','1600','1700','1750','1800','1300','1950','1900','2250','2150','2200','4000','4050'); /*danh sách tỉnh không thuộc hỗ trợ cho vay của ocb*/
        if ($param['results'] == 'Khách hàng hiện đang quan hệ tại 5 TCTD, không có nợ cần chú ý và không có nợ xấu tại thời điểm cuối tháng'
            || $param['results'] == 'Khách hàng hiện đang quan hệ tại 3 TCTD, không có nợ cần chú ý và không có nợ xấu tại thời điểm cuối tháng'
            || $param['results'] == 'Khách hàng hiện đang quan hệ tại 4 TCTD, không có nợ cần chú ý và không có nợ xấu tại thời điểm cuối tháng' ) {
            echo "<script>alert('Khách hàng đang trong tình trạng cảnh báo hoặc có nợ xấu ! không được duyệt');</script>";
            return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].''); 
            // return 1; /*không được vay do đang nợ*/
        }
        else
        {
            if ($this->check_addr($param['code'],$check_pronvince_ocb) == false) {
                echo "<script>alert('Khách hàng sinh sống tại tỉnh/tp mà TCTD này không hỗ trợ cho vay.');</script>";
                return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
                 // return 2;  /*sống tại tỉnh tp không hỗ trợ vay*/
            }
            else
            {
                if ($param['age'] <= 20 || $param['age'] >= 62 ) {
                    echo "<script>alert('Hồ sơ không đủ tuổi vay.');</script>";
                    return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
                    // return 3; /*hồ sơ k đủ tuổi để vay*/
                }
                else
                {
                    $mesage_ = $this->get_data_api_partner($param['tctd_id'],$param['data']);
                    $create_lender = array(
                                        'tctd_id' =>  $param['tctd_id'],
                                        'content' => $mesage_['ErrMsg'],
                                        'status' => $mesage_['ErrCode'],
                                        'log_id' => $param['log_id']
                                      );
                    if ($this->create_lender_results($create_lender) == true) {
                        echo "<script>alert('Gửi thành công.');</script>";
                        return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
                    }
                    else
                    {
                        echo "<script>alert('Gửi thất bại.');</script>";
                        return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
                    }
                }
            }
        } 
    }

    /**
    * check điều kiện và gửi dữ liệu tại mirae asset
    *@param 
    *@return  
    */
    public function check_conditions_mirae_asset($param , $data_mail)
    {
        $phone = rand(1000000000,9999999999);
        $timestamp = strtotime(date('Y-m-d H:m:s'));
        $vendorid = 10;
        $url = 'http://leads.mafcvn.vn:8081/api/leads-generator?id='.$param['cmnd'].'&phone='.$phone.'&vendorid='.$vendorid.'&timestamp='.$timestamp ;
        $key = $param['cmnd'].''.$phone.''.$vendorid.''.$timestamp.'CXqffsQtvqWayT5GarGL';
        $header = array('AuthorizationToken : '.sha1($key).'');
        $fields = array (
            'id' => $param['cmnd'],
            'phone' => '', 
            'vendorid' => 10 ,
            'timestamp' => $timestamp 
        );
        // return sha1($key);
        $data = $this->curl_invoke_tax_get($url,$header);
        if($data['result'] == true)
        {
            $create_lender = array(
                                'tctd_id' =>  $param['tctd_id'],
                                'content' => $data['message'],
                                'status' => $data['errorcode'],
                                'log_id' => $param['log_id']
                              );
            if ($this->create_lender_results($create_lender) == true) {
                Mail::to(['tuoi.tran@mafc.com.vn','thao.quach@mafc.com.vn','tuyen.tq@fibo.vn'])->queue(new SendMailToMafc($data_mail));
                echo "<script>alert('Gửi thành công.');</script>";
                return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
            }
            else
            {
                echo "<script>alert('Gửi thất bại.');</script>";
                return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
            }
        }
        else
        {
            echo "<script>alert('Gửi thất bại.');</script>";
            return redirect('admin/history/view-edit/'.$param['log_id'].'/'.$param['cmnd'].'');
        }
    }

    /********************************************************************************/
    /********************************************************************************/
    /*******************Hàm lưu dữ liệu vào tb lender_results************************/
    /********************************************************************************/
    /********************************************************************************/
    public function create_lender_results($data)
    {
        $create = LenderResults::create($data);
        if ($create) {
            return true;
        }
        else
        {
            return false;
        }
    }


    /********************************************************************************/
    /********************************************************************************/
    /*******************Hàm lưu só tiền giải ngân thực của kế toán********************/
    /********************************************************************************/
    /********************************************************************************/
    public function savemoney($id , Request $req)
    {
        $money = $req->input('tiengiainganthuc');
        $update = HistoryLog::where('id',$id)->update(['so_tien_giai_ngan_thuc' => $money]);
        if ($update == true) {
            return redirect('admin/history');
        }
    }
    /*function test*/
    public function get_DB()
    {
        $email = 'tranquangtuyengss@gmail.com';
        $verify_code = 1334;
        $log = CicLog::where([['email', '=', 'admin@admin.com'],['verify_code','=','1334']])->limit(1)->get()->toArray();
        echo "<pre>";
        echo json_encode($log);
        
        // echo json_encode($log);
    }


    /**
     *@param Reqest
     *@return success or fails 
     */
    public function send_to_cs_team(Request $req)
    {
        $param = $req->all();
        // return $param;
        if (isset($param['name']) && isset($param['group_u']) && isset($param['state']) && $param['group_u'] != null && $param['state'] !== null) {
            for ($i=0; $i < count($param['name']) ; $i++) { 
                if ($param['name'][count($param['name'])-1] == NULL) {
                    echo "<script>alert('Bạn chỉ được chọn hồ sơ đã gửi hồ sơ vay! kiểm tra lại.');</script>";
                    return redirect('admin/history');            
                }
                else
                {
                    HistoryLog::whereIn('id',$param['name'])->update(['user_group_id' => $param['group_u'] , 'cs_id' => $param['state']]);
                    echo "<script>alert('Thành công!');</script>";
                    return redirect('admin/history');
                }
            }
        /**
         * có id gửi lên 
         */
        }
        else
        {
            echo "<script>alert('Vui lòng chọn hồ sơ vay và team-cs + cs user để chuyển hồ sơ vay đến!');</script>";
            return redirect('admin/history');
        }
    }



    public function getUser_teAm($id) {
        $states = User::where("group_id",$id)->select(['id','name'])->get();
        return response()->json(['result' => $states], 200);
    }
}
