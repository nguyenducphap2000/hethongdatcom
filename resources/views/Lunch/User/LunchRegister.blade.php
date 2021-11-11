@extends('layouts.app')
@section('content')
<div class="lunch-container">
    <div class="lunch-header">
        <h1>Hệ thống đăng ký cơm trưa cho nhân viên</h1>
        {{ date('F j Y') }} - {{ now()->month }}/{{ now()->year }}
        <div class="notify">
            <p><i style="color: red">Chú ý: đăng ký ăn trưa trước 15h !!</i></p>
        </div>
    </div>
    <div class="lunch-selector">
        <div class="lunch-date">
                <div class="selector-">
                    <div class="calander">
                        <div class="header-month-year">
                           <form id="monthYear" action="{{ route('ticketsShow') }}" method="POST">
                            @csrf
                                <label for="">
                                    <select name="monthselect" id="month">
                                        <option value="0">Month</option>
                                    @foreach ($listmonth as $value )
                                        <option class="{{ $loop->index }}" value="{{ $value['number'] }}" 
                                            {{ $value['number'] == $month  ? 'selected':'' }}
                                            >
                                            {{ $value['month'] }}
                                        </option>   
                                    @endforeach    
                                    </select>
                                </label>
                                <label for="">
                                    <select name="yearselect" id="year">
                                        <option value="0">Year</option>
                                        @for ($i=2020; $i < 2030; $i++)
                                            <option value="{{ $i }}"  {{ $year == $i ? 'selected' : ''}}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </label>
                            </form> 
                            </div>
                        </div>
                    </div>
                    <div>
                    <form id="ticketstore">
                        <div class="day-infor"> 
                            <div id="days">
                                @for ($j=1; $j <= count($listday); $j++)
                                        <input name="dayselect" hidden
                                        {{ in_array($listday[$j-1],$data->toArray()) ? 'checked' : '' }} 
                                        value="{{ $listday[$j-1] }}" type="checkbox" 
                                        id="{{ $j }}" 
                                        onclick="dayclick({{ $j }})" 
                                        class="{{ in_array($listday[$j-1],$data->toArray()) ? 'table-btn active' : 'table-btn' }}"
                                        {{ date('Y-m-d') >= date('Y-m-d',strtotime($listday[$j-1]))=='1' ? 'disabled' : '' }}
                                        />
                                        <label for="{{ $j }}" data-value = "{{ $listday[$j-1] }}"
                                            class="{{ in_array($listday[$j-1],$data->toArray()) ? 'dayselect-label active' : 'dayselect-label' }}"
                                            >
                                        {{ $j }}
                                        </label>
                                @endfor
                            </div>
                            <div class="lunch-information" style="display: flex; padding-left: 5%; width: 61%">
                                <div class="lunch-label">
                                    <p><label for="">Giá tiền:</label></p>
                                    <p><label for="">Tổng ngày ăn: </label></p>
                                    <p><label for="">Tổng tiền: </label></p>
                                </div>
                                <div id="lunch-infor" class="lunch-span" style="width: 200px; padding-left: 5%;">
                                    <p><label id="price" for="price" value="{{ $prices }}">{{ $prices }}</label>vnđ/ 1 suất ăn</p>
                                    <p><label id="countday" for="day">0</label> ngày</p>
                                    <p><label id="total" for="totalprice" value="{{ $totalPrice }}">{{ $totalPrice }}</label> VNĐ</p>
                                </div>
                            </div>
                        </div>   
                        <div class="btn-saveinfor">
                            <button type="submit" class="btn btn-primary" id="update">Lưu đăng ký</button>
                            <a id="allday" class="btn btn-info">Chọn tất cả</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Lunch.User.js.javascript')
@endsection
