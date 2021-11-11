<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PriceValidator;
use App\Models\Price;
use App\Models\Ticket;
use Illuminate\Http\Request;

class PriceController extends AdminController
{
    public function index()
    {
        return view('Lunch.Admin.PriceManagement',[
            'prices' => Price::paginate(10),
            'dmy' => date('Y-m-d')
        ]);
    }
    public function update(PriceValidator $request,$priceID)
    {
        if($request->Actived || !Price::Where('id',$priceID)->exists()){
            return back()->with('error', 'Cập nhật giá không thành công');
        }else{
            if($request->price){
                // check price, tickets registered
                if(Ticket::where('price_id',$priceID)->exists()){
                    return response()->json([
                        'error' => 'Giá này đã được đăng ký vé, vui lòng nhập lại!'
                    ]);
                }else{
                    $newprices = Price::where('id', $priceID)->update(['price' => $request->price]);
                    if (isset($newprices)) {
                        return response()->json([
                            'success' => 'Cập nhật giá thành công'
                        ]);
                    } else {
                        return response()->json([
                            'error' => 'Cập nhật giá không thành công'
                        ]);
                    }
                }
            }else{
                $currentPrice = Price::where('status', true)->update(['status' => false]);
                $UpdatePriceState = Price::where('id', $priceID)->update(['status' => true]);
                if ($UpdatePriceState) {
                    return back()->with([
                        'success' => 'Cập nhật giá thành công'
                    ]);
                } else {
                    return back()->with([
                        'error' => 'Cập nhật giá không thành công'
                    ]);
                }
            }
        }
    }
    public function store(PriceValidator $request)
    {
        if($request->price){
            if($request->price != 0){
                $pricesCreate = Price::create([
                    'price' => $request->price
                ]);
            }
        }
        
        if (isset($pricesCreate)) {
            return back()->with('insert', 'Thêm mới thành công');
        } else {
            return back()->with('error', 'Thêm mới không thành công');
        }
    }
    public function destroy($id, Request $request)
    {
        if ($id) {
            if (Price::find($id)->status == 1) {
                return back()->with('deleteFail', 'Xóa không thành công');
            } else {
                $delete = Price::where('id', $id)->delete();
                Ticket::where('price_id',$id)->delete();
                if ($delete) {
                    return back()->with('deleteSuccess', 'Xoa thành công');
                } else {
                    return back()->with('deleteFail', 'Xóa không thành công');
                }
            }
        } else {
            return back()->with('deleteFail', 'Xóa không thành công');
        }
    }
}
