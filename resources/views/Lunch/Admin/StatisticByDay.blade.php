@extends('Lunch.Admin.layouts.admin')
@section('tabcontent')
    <div class="container-header">
        <h1>Danh sách ăn trưa ngày {{ date('d/m/Y',strtotime($dmy)) }} </h1>
        <div class="statistic-infor">
            <p>Tổng suất ăn: {{ $amount }}</p>
            <span>Tổng tiền: </span>
            <label id="totalprice" value="{{ $total }}"  for=""></label>
        </div>
        <form action="{{ route('StatisticByDay') }}" method="GET">
            <select name="dayofmonth" id="selectDay">
                @for ($i=0; $i < count($DayOfMonth); $i++)
                    <option value="{{ $DayOfMonth[$i] }}" {{ date('d-m-Y', strtotime($DayOfMonth[$i])) == date('d-m-Y', strtotime($dmy)) ? 'selected' : ''}} >
                        {{ date('d/m/Y',strtotime($DayOfMonth[$i])) }}
                    </option>
                @endfor
            </select>
        </form>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">STT</th>
                <th scope="col">Tên</th>
                <th scope="col">Email</th>
                <th scope="col">Roles</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $value)
                <tr>
                    <th scope="row">{{ ($users->currentPage() - 1) * 10 + ($key + 1) }}</th>
                    <td>{{ $value->user->name }}</td>
                    <td>{{ $value->user->email }}</td>
                    @if ($value->user->isAdmin == 1)
                        <td>Admin</td>
                    @else
                        <td>User</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="export-excel" style="padding-top: 1%">
            <a class="badge badge-primary" style="font-size: 1em" href="{{ route('ReportExport',$dmy) }}">Export excel</a>
        </div>
        {{ $users->links() }}
    </div>
@endsection