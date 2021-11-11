<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $ticket = new Ticket();
        $data = $ticket->showDATA($request);
        $prices = Price::where('status', true)->get('price');  
        return view('Lunch.User.LunchRegister', [
            "listmonth" => $data['listmonth'],
            "month" => $data['month'],
            "year" => $data['year'],
            "listday" => $data['listday'],
            "data" => $data['data'],
            "prices" => $prices->isEmpty() ? 0 : $prices[0]->price,
            'totalPrice' => $data['totalPrice']
        ]);
    }
    public function update(Request $request)
    {
        $ticket = new Ticket();
        $data = $ticket->updateDATA($request, 'update');
        if ($data) {
            return response()->json([
                'success' => "Update successful"
            ]);
        } else {
            return response()->json([
                'fail' => "Update failed",
            ]);
        }
    }
    public function show(Request $request)
    {
        $prices = Price::where('status', true)->get('price');
        $ticket = new Ticket();
        $data = $ticket->showDATA($request, 'show');
        return view('Lunch.User.LunchRegister', [
            "listmonth" => $data['listmonth'],
            "month" => $data['month'],
            "year" => $data['year'],
            "listday" => $data['listday'],
            "data" => $data['data'],
            "prices" => $prices->isEmpty() ? 0 : $prices[0]->price,
            'totalPrice' => $data['totalPrice']
        ]);
    }
}
