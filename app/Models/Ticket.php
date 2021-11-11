<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'price_id',
        'dateregister'

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function price()
    {
        return $this->hasMany(Price::class,'id','price_id');
    }

    public function updateDATA(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dataUpdate = (isset($request->dataUpdate)) ? $request->dataUpdate : "";
        $check = false;
        $prices = Price::where('status', true)->get('id');
        // time greater than 3pm today
        if(time() > strtotime('15:00')){
            return $check = false;
        }
        //date checked not null
        if($dataUpdate != null) {
            // loop to get date register
            for ($i = 0; $i < count($dataUpdate); $i++) {
                $checkDateExist = Ticket::where('user_id', Auth::user()->id)
                    ->where('dateregister',$dataUpdate[$i])->exists();
                if($checkDateExist == false){
                    //check t7 cn
                    if (
                        date('D', strtotime($dataUpdate[$i])) === 'Sun'
                        || date('D', strtotime($dataUpdate[$i])) === 'Sat'
                    ) {
                        $check = false;
                    } else {
                        // check date register smaller than date current
                        if (
                            Carbon::createFromFormat('Y-m-d', $dataUpdate[$i])
                            ->lte(Carbon::now()->format('Y-m-d'))
                        ) {
                            return $check = false;
                        } else {
                            $check = true;
                                Ticket::Create ([
                                    'user_id' => Auth::user()->id,
                                    'price_id' => $prices[0]->id,
                                    'dateregister' => $dataUpdate[$i]
                            ]);
                        }
                    }
                }else{
                    Ticket::where('user_id', Auth::user()->id)
                        ->where('dateregister', $dataUpdate[$i])->delete();
                        $check = true;
                }
            }
        }
        return $check;
    }
    public function showDATA(Request $request,$dmy = null)
    {
        $listmonth = array();
        //list of month Eg: May - 5...
        for ($i = 1; $i < 13; $i++) {
            $listmonth[$i] = [
                'number' => DateTime::createFromFormat('!m', $i)->format('m'),
                'month' => DateTime::createFromFormat('!m', $i)->format('F')
            ];
        }
        //user click select option
        if ($request->monthselect && $request->yearselect) {
            $month = $request->monthselect;
            $year = $request->yearselect;
        } else if ($request->MonthYear) {
            $month = date('m', strtotime("$request->MonthYear"));
            $year = date('Y', strtotime("$request->MonthYear"));
        }else if($dmy !=  null){
            $month = date('m', strtotime("$dmy"));
            $year = date('Y', strtotime("$dmy"));
        }else {
            $month = date('m');
            $year = date('Y');
        }

        $start_date = "01-" . $month . "-" . $year;
        $start_time = strtotime($start_date);

        $end_time = strtotime("+1 month", $start_time);

        for ($i = $start_time; $i < $end_time; $i += 86400) {
            $list[] = date('Y-m-d', $i);
        }
        $data = Ticket::where('user_id', Auth::user()->id)
            ->WhereRaw('month(dateregister) = ?', $month)
            ->WhereRaw('year(dateregister) = ?', $year)
            ->pluck('dateregister');
        
        //total price    
        $totalAllOfTicket = 0;
        $AllTicket = Ticket::Where('user_id',Auth::user()->id)->WhereRaw('month(dateregister) = ?', $month)
            ->WhereRaw('year(dateregister) = ?', $year)->with(['price'])->get();
            foreach($AllTicket as $key => $value){
                $totalAllOfTicket += $value->price[0]->price;
            } 
        return [
            "listmonth" => $listmonth,
            "month" => $month,
            "year" => $year,
            "monthyear" => "$year-$month",
            "listday" => $list,
            "data" => $data,
            "totalPrice" => $totalAllOfTicket
        ];
    }
}
