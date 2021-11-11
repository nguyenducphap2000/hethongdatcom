<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LunchReport;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class StatisticController extends AdminController 
{
    public function index(Request $request)
    {
        if(Auth::user()->isAdmin == 1){
            $ticketShow = new Ticket();
            $data = $ticketShow->showDATA($request);
            $ArrayDayOfMonth = array();
            $totalAllOfTicket = 0;
            $amount = Ticket::WhereRaw('month(dateregister) = ?', $data['month'])
                ->WhereRaw('year(dateregister) = ?', $data['year'])
                ->count();
            $listday = $data['listday'];

            for ($i = 0; $i < count($listday); $i++) {
                $ArrayDayOfMonth[$listday[$i]] = [
                    'ticketsOfDay' => Ticket::where('dateregister', $listday[$i])->count(),
                ];
            };

            $AllTicket = Ticket::WhereRaw('month(dateregister) = ?', $data['month'])
            ->WhereRaw('year(dateregister) = ?', $data['year'])->with(['price'])->get();
            foreach($AllTicket as $key => $value){
                $totalAllOfTicket += $value->price[0]->price;
            }
            return view('Lunch.Admin.StatitsticManagement', [
                'DayOfMonth' => $ArrayDayOfMonth,
                'amount' => $amount,
                'dmy' => $data['monthyear'],
                'total' => $totalAllOfTicket,
            ]);
        }else{
            return back();
        }
    }
    public function StatisticByMonth(Ticket $ticket,Request $request, $dmy = null)
    {   
        if($dmy == null){
            $dmy = $request->dmySelected;
        }
        $data = $ticket->showDATA($request,$dmy);
        $amount = Ticket::WhereRaw('month(dateregister) = ?', date('m', strtotime($dmy)))
                ->WhereRaw('year(dateregister) = ?', date('Y', strtotime($dmy)))
                ->count();
        $request->session()->put('dmy',$dmy);
        $users = new User();
        if(!empty($request->TxtSearch))
            $users = $users->where('name','like','%'. $request->TxtSearch . '%')
            ->orWhere('email','like','%'. $request->TxtSearch . '%');

        $users = $users->with(['ticket' => function ($t) use ($dmy) {
            $t->with(['price'])->WhereRaw('month(dateregister) = ?', date('m', strtotime($dmy)))
                ->WhereRaw('year(dateregister) = ?', date('Y', strtotime($dmy)));
        }])->paginate(10)->appends(request()->input());

        return view('Lunch.Admin.ViewDetail', [
            'ListUser' => $users,
            'dmy' => $dmy,
            'amount' => $amount,
            "listday" => $data['listday']
        ]);
    }
    public function StatisticByDay(Request $request,$dmy = null){
        $totalPriceOfTicket = 0;

        $amount = Ticket::WhereRaw('dateregister = ?', ($dmy != null) ? $dmy : $request->dayofmonth)->count();
        $usersInDay = Ticket::where('dateregister', ($dmy != null) ? $dmy : $request->dayofmonth)
        ->with('user')->paginate(10)->appends($request->all());
        
        $ticketShow = new Ticket();
        if($dmy != null){
            $data = $ticketShow->showDATA($request,$dmy);
        }else{
            $data = $ticketShow->showDATA($request,$request->dayofmonth);
        }
        $listday = $data['listday'];

        $AllTicket = Ticket::WhereRaw('dateregister = ?', ($dmy != null) ? $dmy : $request->dayofmonth)
            ->with(['price'])->get();
            foreach($AllTicket as $key => $value){
                $totalPriceOfTicket += $value->price[0]->price;
            }
        return view('Lunch.Admin.StatisticByDay',[
            'DayOfMonth' => $listday,
            'dmy' => (isset($request->dayofmonth)) ? $request->dayofmonth : $dmy,
            'users' => $usersInDay,
            'amount' => $amount,
            'total' => $totalPriceOfTicket
        ]);
    }

    public function ReportExport($dmy){
        return Excel::download(new LunchReport($dmy),'LunchReport.xlsx');
    }
}
