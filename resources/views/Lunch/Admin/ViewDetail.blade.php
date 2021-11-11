@extends('layouts.app')
@section('content')
<div class="view-detail">
    <nav class="navbar navbar-light bg-light">
        {{-- @php
            $dmy = explode('/',Request::path())[3];
        @endphp --}}
        <form class="form-inline" action="{{ Route('StatisticByMonth', $dmy) }}">
            <input required class="form-control mr-sm-2" name="TxtSearch" value="{{ old('TxtSearch') }}"
            type="search" placeholder="Nhập tên hoặc email" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
        </form>
        <div class="link-back">
            <a href="{{ route('StatisticIndex') }}" class="btn btn-info">Back</a>
        </div>
    </nav>
    <div class="staistic-ticket">
        <h1 style="text-align: center">Bảng thống kê tháng {{ date('m',strtotime($dmy)) }}</h1>
    </div>
    <div class="total-tickets">
        <span><h5>Tổng suất ăn: {{ $amount }}</h5></span>
    </div>
    <div style="display: flex">
        <span><h5>Chọn tháng: </h5></span>
        <form action="{{ route('StatisticByMonth') }}" method="GET">
            <label for="">
                <select name="dmySelected" id="selectMonthYear">
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
    <table class="table">
        <thead>
            <tr>
            <th scope="col">STT</th>
            <th scope="col">Tên</th>
            <th scope="col">Email</th>
            <th scope="col">Roles</th>
            <th scope="col">Tổng suất ăn</th>
            <th scope="col">Tổng tiền ăn trong tháng</th>
            <th scope="col">Xem chi tiết các ngày</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ListUser as $key => $value )
                <tr>
                    @php
                        $sum = 0;
                    @endphp
                    @foreach ($value->ticket as $subTotal)
                        @php
                           $sum+= $subTotal->price[0]->price;
                        @endphp
                    @endforeach
                    <th scope="row">{{ ($ListUser->currentPage() - 1) * 10 + ($key + 1) }}</th>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->email }}</td>
                    @if($value->isAdmin==0)
                        <td>User</td>
                    @else
                        <td>Admin</td>
                    @endif
                    <td>{{ count($value->ticket) }}</td>
                    <td class="totalprice">{{ $sum  }}</td>
                    <td>
                        <button type="button" class="btn btn-primary" 
                                data-toggle="modal" data-target="#bd-example-modal-lg{{ $value->id }}">
                            Xem chi tiết
                        </button>
                    </td>
                    @include('Lunch.Admin.ModalAdmin.ViewDetailAllTikcet')
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $ListUser->links() }}
</div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $("#selectMonthYear").click(function(){
            $(this).closest('form').submit();
        });
    </script>
    <script>
        $(".price").text(formatNumber(+$(".price").attr("value"),'.',',') + " VND");
        $.each($(".totalprice"),function(key, value){
            $(value).html(formatNumber(+$(value).html(),'.',',') + " VND");
        });
        $.each($(".dayregistered").find(".PriceEachDay"),function(key, value){
            $(value).html(formatNumber(+$(value).html(),'.',',') + " VND");
        });
        // $(".totalmoney").text(formatNumber(+$(".totalmoney").attr("money"),'.',',') + " VND");
            function formatNumber(nStr, decSeperate, groupSeperate) {
                nStr += '';
                x = nStr.split(decSeperate);
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
                }
                return x1 + x2;
            }
    </script>

@endsection