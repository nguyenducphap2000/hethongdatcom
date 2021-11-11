@extends('Lunch.Admin.layouts.admin')
@section('tabcontent')
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <div class="statistic">
        <div class="statistic-header">
            <h1>Thống kê ăn trưa tháng {!! date('n', strtotime($dmy)) !!}</h1>
        </div>
        <div style="display: flex">
            <form action="{{ route('StatisticIndex') }}" method="POST">
                @csrf
                <label for="">
                    <select name="MonthYear" id="selectMonthYear">
                        <option id="0" value="0">Select</option>
                    @for ($i=2020; $i<2030; $i++)
                        @for ($j=1; $j<13; $j++)
                            @if ($j < 10)
                                <option class="dmy" {{ $dmy=="$i-0$j" ? 'selected' :''}} value="{{ $i }}-0{{ $j }}">0{{ $j }}/{{ $i }}</option>
                            @else
                                <option class="dmy" {{ $dmy=="$i-$j" ? 'selected' :''}} value="{{ $i }}-{{ $j }}">{{ $j }}/{{ $i }}</option>
                            @endif
                        @endfor
                    @endfor
                    </select>
                </label>
            </form>
        </div>
        <div class="statistic-total">
            <div class="room">
            <label for="">Phòng ban: </label>
            <label for="">
                <select name="" id="">
                    <option value="">Trung tâm CNTT</option>
                </select>
            </label>
            </div>
            <div class="lunch-total">
                <span>Tổng suất ăn: </span>
                <label for="">{{ $amount }} </label>
            </div>
            <div class="money-total">
                <span>Tổng tiền: </span>
                <label id="totalprice" value="{{ $total }}"  for=""></label>
            </div>
            <div class="statistic-table" style="display: flex">
                <div class="statistic-day-amount">
                @foreach ($DayOfMonth as $key => $value )
                    <label for="" class="{{ $value['ticketsOfDay']==0 ? 'dateofmonth' : 'dateofmonth-active' }}" >
                        <p class="dayofmonth">
                            @if ($value['ticketsOfDay'] == 0)
                                <span>{{ date('d/m',strtotime($key))  }}</span>
                            @else
                                <a class="{{ $value['ticketsOfDay'] == 0 ? 'day-disabled' : 'days' }}"
                                    href="{{ route('StatisticByDay',date('Y-m-d',strtotime($key))) }}">
                                    {{ date('d/m',strtotime($key))  }}
                                </a>
                            @endif
                        </p> 
                        <p class="day-amount">{{ $value['ticketsOfDay'] }}</p>
                    </label>
                @endforeach
                </div>
            </div>
            <div class="viewdetail">
                <a class="badge badge-primary" style="font-size: 1em" href="{{ route('StatisticByMonth', $dmy) }}">Xem chi tiết tháng {{ date('m',strtotime($key))  }}</a>
            </div>
        </div>
    </div>
</div>
@endsection