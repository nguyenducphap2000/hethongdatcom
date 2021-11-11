@extends('Lunch.Admin.layouts.admin')
@section('tabcontent')
    <h1>Quản lý giá</h1> 
        <div class="view-store-price">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session('insert'))
                <div class="alert alert-success">
                    {{ Session()->get('insert') }}
                </div>
            @endif
            @if (Session('error'))
                <div class="alert alert-danger">
                    {{ Session()->get('error') }}
                </div>
            @elseif (Session('success'))
                <div class="alert alert-success">
                    {{ Session()->get('success') }}
                </div>
            @elseif (Session('deleteSuccess'))
                <div class="alert alert-success">
                    {{ Session()->get('deleteSuccess') }}
                </div>
            @elseif(Session('deleteFail'))
                <div class="alert alert-danger">
                    {{ Session()->get('deleteFail') }}
                </div>
            @endif
            <div class="CRUD-price">
                <div id="table-price" class="table-price">
                    <table id="table" class="table">
                        <thead>
                            <tr>
                            <th scope="col">STT</th>
                            <th scope="col">Giá</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prices as $price )
                            <tr>
                                <th scope="row">{{ ($prices->currentPage() - 1) * 10 + ($loop->index + 1) }}</th>
                                <td class="PriceInTable">{{ $price->price }}</td>
                                <td>
                                    <form action="{{ route('pricesUpdate',$price->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input name="price" type="text" value="0" hidden>
                                        @if($price->status==true)
                                            <input name="Actived" type="submit" disabled class="btn btn-success" value="Actived"/>
                                            {{-- <label class="badge badge-success">Active</label> --}}
                                        @else
                                            <button name="active" type="submit" class="btn btn-danger" style="font-size:  13px">Disabled</button>
                                        @endif
                                    </form>
                                </td>
                                <td>{{ Date('d-m-Y',strtotime($price->created_at)) }}</td>
                                <td style="display: flex">
                                    <form id="{{ $price->id }}" action="{{ route('pricesDelete',$price->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        @if( $price->status==true)
                                            <button disabled type="button" class="btn btn-secondary">Xóa</button>
                                        @else
                                            <button type="button" onclick="DeleteButton({{ $price->id }})" id="deletePrice" class="btn btn-primary">
                                                Xóa
                                            </button>
                                        @endif
                                    </form>
                                    <button type="button" onclick="EditButton({{ $price->id }}, {{ $price->price }})" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{ $price->id }}" data-whatever="@fat" style="margin-left: 5%;">
                                        Sửa
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $prices->links() }}
                    @include('Lunch.Admin.ModalAdmin.EditPrice')
                    <div class="create-price">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCreate" data-whatever="@fat">Create</button>
                        @include('Lunch.Admin.ModalAdmin.CreatePrice')
                    </div>
                    </div>
                </div>
            </div>      
@endsection