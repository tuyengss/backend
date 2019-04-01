@extends('voyager::master')



@section('page_header')

@stop



@section('content')
    <div class="page-content browse container-fluid">
         @include('vendor.voyager.checkuser')
 
        <?php $u_id = Auth::user()->id;?>
        <?php $role_id = Auth::user()->role_id;?>
        <?php $group_id_u = Auth::user()->group_id; ?>
        <div class="row">

            <div class="col-sm-12 text-center" style="background-color: white; padding-bottom: 20px;">

                <div class="row">

                    <div class="col-sm-12 text-left">

                        <h3 style="font-weight: bold;">Lịch sử vay</h3>

                    </div>

                </div>

                <form class="form-inline" action="{{url('admin/history')}}" method="POST">

                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"/>
                    <div class="col-sm-6 text-left">
                        <div class="container">
                            <div class="col-sm-12">
                                <div class="col-sm-4" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                    <label for="colFormLabelLg" style="font-weight: bold;">Ngày tiếp nhận hồ sơ :</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-bottom: 10px;">
                                    <div class="form-group">
                                        <label for="inputPassword6" >Từ: </label>
                                        <input type="date" name="from_date_tn" id="from_date_TN" value="{{date("Y")}}-01-01" class="form-control mx-sm-3" aria-describedby="passwordHelpInline">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword6" >Đến: </label>
                                        <input type="date" name="to_date_tn" id="to_date_TN" value="{{ date("Y") }}-12-31" class="form-control mx-sm-3" aria-describedby="passwordHelpInline">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Ngày giải ngân :</label>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="inputPassword6" >Từ: </label>
                                        <input type="date" name="from_date_tc" id="from_date_TC" value="{{date("Y")}}-01-01" class="form-control mx-sm-3" aria-describedby="passwordHelpInline">
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword6" >Đến: </label>
                                        <input type="date" name="to_date_tc" id="to_date_TC" value="{{ date("Y") }}-12-31" class="form-control mx-sm-3" aria-describedby="passwordHelpInline">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-left">
                        <div class="container">
                            <div class="col-sm-12">
                                @if(Auth::user())
                                <?php $role_id = Auth::user()->role_id;?>
                                @if($role_id === 1 || $role_id === 4 || $role_id === 5 || $role_id === 6 || $role_id === 7)
                                <div class="col-sm-3" style="padding-bottom: 10px;">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Phân loại hồ sơ:</label>
                                    </div>  
                                </div>
                                <div class="col-sm-9" style="margin-bottom: 10px;">
                                    <select class="form-control" id="status_profile_search_history" name="status_profile">
                                        <option value="">Chọn loại hồ sơ</option>
                                        <option value="100d">Đã gửi hồ sơ vay</option>
                                        <option value="100c">Chưa gửi hồ sơ vay</option>
                                        <option value="70">Chưa cập nhật đầy đủ hồ sơ</option>
                                        <option value="0">Chỉ chấm điểm</option>
                                    </select>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                        <div class="container">
                            <div class="col-sm-12">
                                <div class="col-sm-3" >
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Trạng thái:</label>
                                    </div>
                                </div>
                                <div class="col-sm-9" >
                                    <div class="form-group">
                                        <select class="form-control" id="status_search_history" name="status" style="min-width: 200px;">
                                             <option value="">Chọn trạng thái</option>
                                            @foreach($logsstatus as $key => $row)
                                                <option value="{{$row->id}}">{{$row->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-sm-6 text-left">
                        <div class="container">
                            <div class="col-sm-12">
                                <div class="col-sm-4" style="padding-top: 10px;">
                                    <div class="form-group row">
                                        <label for="colFormLabelLg" style="font-weight: bold;">Nhập từ khóa tìm kiếm:</label>
                                    </div>
                                </div>
                                <div class="col-sm-8" style="margin-top: 10px;">
                                    <div class="container">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="text_search" value="" placeholder="Nhập từ khóa tìm kiếm( CMND, SĐT )" style="min-width: 420px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-left">
                    </div>
                    <button type="submit" class="btn btn-danger mb-2">Tìm kiếm</button>

                </form>



            </div>

        </div>

        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="table-responsive">
                    <form onsubmit="return confirm('Có chắc chắn muốn chuyển các hồ sơ này đến Team-CS ?')" action="{{route('send-to-cs-team')}}" method="post" accept-charset="utf-8">
                        @csrf
                        <table  id="dataTable_his" class="table table-hover dataTable_his">
                            @if($role_id != 7)
                            @if($role_id == 8)
                            <caption style="color: #22a7f0; margin-bottom: 30px;"> &#9679;&nbsp;Hồ sơ đã gửi hồ sơ vay(Total):&nbsp;<span class="label label-success" style="font-weight: bold;">{{$total_gui}}</span></caption>
                            @else
                            <caption style="color: #22a7f0; margin-bottom: 30px;">&#9679;&nbsp;Hồ sơ chỉ chấm điểm(Total): <span class="label label-danger" style="font-weight: bold;">{{$total_chichamdiem}}</span>|| &#9679;&nbsp;Hồ sơ đã cập nhật(Total): <span class="label label-warning" style="font-weight: bold;">{{$total_capnhat}}</span>|| &#9679;&nbsp;Hồ sơ đã gửi hồ sơ vay(Total):&nbsp;<span class="label label-success" style="font-weight: bold;">{{$total_gui}}</span></caption>
                            @endif
                            @endif
                            @if($role_id == 1 || $role_id == 4 || $role_id == 6)
                            <div class="text-left">
                                <div class="col-md-4">
                                    <select class="form-control" id="team" name="group_u" style="min-width: 200px;">
                                         <option value="">---Chọn Team-CS---</option>
                                        @foreach($team_cs as $key => $row)
                                            <option value="{{$row->id}}">{{$row->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="state" class="form-control">
                                        <option value="">--Chọn user--</option>
                                    </select>
                                </div> <button type="submit" class="btn btn-primary"><i class="voyager-forward"></i> Chuyến đến CS quản lý</button>
                            </div>
                             <hr>
                            @endif
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    @if($role_id != 8)
                                    <th width="20px"><input type="checkbox" id="checkall">Check all</th>
                                    @endif
                                    @if($role_id === 7)
                                    <th>Agencies</th>
                                    <th>Referal</th>
                                    <th>CS</th>
                                    <th>Họ tên</th>
                                    @endif
                                    <th>Ngày tạo</th>
                                    @if($role_id != 8)
                                    <th>Ngày gửi HS</th>
                                    @endif

                                    <th>Phone</th>

                                    <th>CMND</th>
                                    
                                    <th>Số tiền giải ngân</th>
                                    @if($role_id != 7)
                                    <th>Ngày giải ngân</th>
                                    <th>Người đăng ký</th>
                                    @endif
                                    @if($role_id === 1 || $role_id === 4 || $role_id === 5 || $role_id === 6)
                                     <th>Tỷ lệ hoàn thành (%)</th> 
                                    <th>Agencies</th>
                                    <th>Phân loại hồ sơ</th>
                                
                                    <th>Trạng thái</th>

                                    <th>Tác vụ</th>
                                    @elseif($role_id === 7)
                                    <th>Khoản muốn vay</th>
                                    <th>Trạng thái</th>
                                    <th>Tiền giải ngân thực</th>
                                    <th>Action</th>
                                    @else
                                    <th>Trạng thái</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($history as $key => $val)

                                    @if($role_id === 1 || $role_id === 4 || $role_id === 6)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <th><input type="checkbox" name="name[]" value="{{$val->history_log_id}}"></th>
                                        <?php $date = date('d-m-Y', strtotime($val->created_at))  ?> 
                                        <?php $date_1 = date('d-m-Y', strtotime($val->ngay_gui_ho_so))  ?> 
                                        <td>@if($date !== '01-01-1970'){{ date('d-m-Y', strtotime($val->created_at)) }} @endif </td>
                                        <td>@if($date_1 !== '01-01-1970'){{ date('d-m-Y', strtotime($val->ngay_gui_ho_so)) }} @else {{'Chưa gửi'}} @endif</td>
                                        <td> {{ $val->phone_id }}</td>
                                        <td>{{ $val->cmnd }}</td>
                                        <td>{{ $val->so_tien_giai_ngan }}</td>
                                        <td>@if($val->ngay_giai_ngan != '0000-00-00 00:00:00') {{ $val->ngay_giai_ngan }} @endif</td>
                                        <td>{{ $val->name }}</td>
                                         <td>{{$val->progress_info }} %</td> 
                                        <td>{{ $val->agencies }}</td>
                                        <td>
                                            @if(($val->progress_info == 5) && ($val->history_log_id == null))
                                                <span class="label label-danger">Hồ sơ chỉ chấm điểm</span>
                                            @elseif($val->progress_info < 50 && $val->history_log_id != null)
                                                <span class="label label-success">Hồ sơ đã gửi hồ sơ vay</span>
                                            @elseif($val->progress_info < 50 && $val->history_log_id == null)
                                                <span class="label label-info">Hồ sơ chưa cập nhật đầy đủ</span>
                                            @elseif($val->progress_info >= 50 && $val->history_log_id == null)
                                                <span class="label label-warning">Hồ sơ chưa gửi hồ sơ vay</span>
                                            @elseif($val->progress_info > 50 && $val->history_log_id !==null)
                                                <span class="label label-success">Hồ sơ đã gửi hồ sơ vay</span>
                                            @endif
                                        </td>
                                        <td>
                                        @if($val->trangthai !== null)
                                            
                                            @if($val->trangthai == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @else
                                            @if($val->status == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->status == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->status == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @endif
                                        </td>
                                        @if($val->history_log_id !== null)
                                        <td><a href="{{url('admin/history/view-edit',[$val->history_log_id,$val->cmnd])}}" title="View" class="btn btn-sm btn-primary view">
                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Xem</span>
                                        </a></td>
                                        @else
                                        <td><a href="{{url('admin/history/view-edit',[$val->id,$val->cmnd])}}" title="View" class="btn btn-sm btn-primary view">
                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Xem</span>
                                        </a></td>
                                        @endif
                                    </tr>
                                    @elseif($role_id == 5)    {{--show history user_cs--}}
                                    @if($group_id_u == $val->user_group)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <th><input type="checkbox" name="name[]" value="{{$val->history_log_id}}"></th>
                                        <?php $date = date('d-m-Y', strtotime($val->created_at))  ?> 
                                        <?php $date_1 = date('d-m-Y', strtotime($val->ngay_gui_ho_so))  ?> 
                                        <td>@if($date !== '01-01-1970'){{ date('d-m-Y', strtotime($val->created_at)) }} @endif </td>
                                        <td>@if($date_1 !== '01-01-1970'){{ date('d-m-Y', strtotime($val->ngay_gui_ho_so)) }} @else {{'Chưa gửi'}} @endif</td>
                                        <td> {{ $val->phone_id }}</td>
                                        <td>{{ $val->cmnd }}</td>
                                        <td>{{ $val->so_tien_giai_ngan }}</td>
                                        <td>@if($val->ngay_giai_ngan != '0000-00-00 00:00:00') {{ $val->ngay_giai_ngan }} @endif</td>
                                        <td>{{ $val->name }}</td>
                                        <td>{{ round(($val->progress_info/52)*100) }} %</td>
                                        <td>{{ $val->agencies }}</td>
                                        <td>
                                            @if(($val->progress_info == 5) && ($val->history_log_id == null))
                                                <span class="label label-danger">Hồ sơ chỉ chấm điểm</span>
                                            @elseif($val->progress_info < 50 && $val->history_log_id != null)
                                                <span class="label label-success">Hồ sơ đã gửi hồ sơ vay</span>
                                            @elseif($val->progress_info < 50 && $val->history_log_id == null)
                                                <span class="label label-info">Hồ sơ chưa cập nhật đầy đủ</span>
                                            @elseif($val->progress_info >= 50 && $val->history_log_id == null)
                                                <span class="label label-warning">Hồ sơ chưa gửi hồ sơ vay</span>
                                            @elseif($val->progress_info > 50 && $val->history_log_id !==null)
                                                <span class="label label-success">Hồ sơ đã gửi hồ sơ vay</span>
                                            @endif
                                        </td>
                                        <td>
                                        @if($val->trangthai !== null)
                                            
                                            @if($val->trangthai == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @else
                                            @if($val->status == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->status == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->status == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @endif
                                        </td>
                                        @if($val->history_log_id !== null)
                                        <td><a href="{{url('admin/history/view-edit',[$val->history_log_id,$val->cmnd])}}" title="View" class="btn btn-sm btn-primary view">
                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Xem</span>
                                        </a></td>
                                        @else
                                        <td><a href="{{url('admin/history/view-edit',[$val->id,$val->cmnd])}}" title="View" class="btn btn-sm btn-primary view">
                                                <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Xem</span>
                                        </a></td>
                                        @endif
                                    </tr>
                                    @endif
                                    @elseif($role_id == 7)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <th><input type="checkbox" name="name[]" value="{{$val->history_log_id}}"></th>
                                        <td>{{$val->agencies}}</td>
                                        <td>{{$val->referal_name}}</td>
                                        <td>@if($val->cs_name_history != null){{ $val->cs_name_history }} @else {{ $val->cs_name_log }} @endif</td>
                                        <td>{{$val->name}}</td>
                                        <td>{{date('d-m-Y', strtotime($val->created_at))}}</td>
                                        <td>{{date('d-m-Y', strtotime($val->ngay_gui_ho_so))}}</td>
                                        <td>{{$val->phone}}</td>
                                        <td>{{$val->cmnd}}</td>
                                        <td>{{$val->so_tien_giai_ngan}}</td>
                                        <td>{{$val->khoanvay}}</td>
                                        <td>
                                        @if($val->trangthai !== null)
                                            
                                            @if($val->trangthai == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->trangthai == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @else
                                            @if($val->status == 1)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 2) 
                                                <span class="label label-success">{{$val->status_name}}</span>
                                            @elseif($val->status == 3)
                                                <span class="label label-primary">{{$val->status_name}}</span>
                                            @elseif($val->status == 4) 
                                               <span class="label label-info">{{$val->status_name}}</span>
                                            @elseif($val->status == 5) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 6) 
                                               <span class="label label-warning">{{$val->status_name}}</span>
                                            @elseif($val->status == 7) 
                                               <span class="label label-danger">{{$val->status_name}}</span>
                                            @endif
                                        @endif
                                        </td>
                                        <form action="{{url('admin/updatemoney',$val->history_log_id)}}" method="post" accept-charset="utf-8">
                                         @csrf
                                           <td><input type="text" id="money" name="tiengiainganthuc" class="form-control" value="@if($val->so_tien_giai_ngan_thuc !== null) {{$val->so_tien_giai_ngan_thuc}} @endif" placeholder=""></td>
                                            <td><button type="submit" title="View" class="btn btn-sm btn-primary view">
                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Lưu</span>
                                            </button></td>
                                        </form>
                                        
                                    </tr>
                                    @elseif($role_id == 8)
                                        @if($val->history_log_id !== null)
                                            @if($u_id === $val->user_ref)
                                                <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{ $val->created_at->format('d/m/Y') }}</td>
                                                    <td>
                                                        
                                                        {{'*******'.substr((string)$val->phone_id,-4)}}
                                                    </td>
                                                    <td>
                                                        {{'*********'.substr((string)$val->cmnd,-4)}}
                                                    </td>
                                                    <td>{{ $val->so_tien_giai_ngan }}</td>
                                                    <td>@if($val->ngay_giai_ngan != '0000-00-00 00:00:00') {{ $val->ngay_giai_ngan }} @endif</td>
                                                    <td>{{ $val->note }}</td>
                                                    <td>
                                                    @if($val->trangthai !== null)
                                                        <span class="
                                                        @if($val->trangthai == 1) 
                                                            {{'label label-primary'}}
                                                        @elseif($val->trangthai == 2) 
                                                            {{'label label-success'}}
                                                        @elseif($val->trangthai == 3)
                                                            {{'label label-primary'}}
                                                        @elseif($val->trangthai == 4) 
                                                            {{'label label-info'}}
                                                        @elseif($val->trangthai == 5) 
                                                            {{'label label-warning'}}
                                                        @elseif($val->trangthai == 6) 
                                                            {{'label label-warning'}}
                                                        @elseif($val->trangthai == 7) 
                                                            {{'label label-danger'}} 
                                                        @endif"> {{ $val->status_name}} </span>
                                                    @else
                                                     <span class="
                                                        @if($val->status == 1) 
                                                            {{'label label-primary'}}
                                                        @elseif($val->status == 2) 
                                                            {{'label label-success'}}
                                                        @elseif($val->status == 3)
                                                            {{'label label-primary'}}
                                                        @elseif($val->status == 4) 
                                                            {{'label label-info'}}
                                                        @elseif($val->status == 5) 
                                                            {{'label label-warning'}}
                                                        @elseif($val->status == 6)
                                                            {{'label label-warning'}}
                                                        @elseif($val->status == 7) 
                                                            {{'label label-danger'}} 
                                                        @endif"> {{ $val->status_name}} </span>
                                                    @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach

                            </tbody>

                        </table>
                    </form>
                </div>
            </div>

        </div>

    </div>



   

@stop
@section('javascript')
<script type="text/javascript">
    @if(isset($status))
        $('#status_search_history option[value={{$status}}]').attr('selected','selected');      
    @endif 
    @if(isset($status_profile))
        $('#status_profile_search_history option[value={{$status_profile}}]').attr('selected','selected');    
    @endif

    $(document).ready(function() {
        $('select[name="group_u"]').on('change', function(){
        var group_id = $(this).val();
        if(group_id) {
            $.ajax({
                url: 'admin/user-in-group/'+group_id,
                type:"GET",
                dataType:"json",
                success:function(data) {
                    $('select[name="state"]').empty();
                    $.each(data.result, function(key, val){
                        $('select[name="state"]').append('<option value="'+ val.id +'">' + val.name + '</option>');
                    });
                }
            });
        } else {
            $('select[name="state"]').empty();
             $('select[name="state"]').append('<option value="">--Chọn user--</option>');
        }

    });
    });
</script>
@stop



