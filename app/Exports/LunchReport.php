<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LunchReport implements FromCollection,ShouldAutoSize,WithHeadings
{
    use Exportable;
    protected $dmy;

    public function __construct($dayofmonth)
    {
        $this->dmy = $dayofmonth;
    }

    public function headings():array{
        return [
            'STT',
            'TÊN',
            'EMAIL',
            'ROLES',
            'ĐƠN GIÁ',
            'TỔNG TIỀN',
            'TỔNG VÉ'
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = Ticket::where('dateregister', $this->dmy)->with('user')->orderBy('user_id','asc')->get();
        $array = array();
        $roles = "";
        
        $totalPriceOfTicket = 0;
        $AllTicket = Ticket::WhereRaw('dateregister = ?', $this->dmy)
            ->with(['price'])->get();
            foreach($AllTicket as $key => $value){
                $totalPriceOfTicket += $value->price[0]->price;
            };
        foreach($data as $key => $value){
            if($value->user->isAdmin == 1){
                $roles = "admin";
            }else{
                $roles = "user";
            }
            if($key == 0){
                $array[$key] = [
                    'id' => $key + 1,
                    'name' => $value->user->name,
                    'email' => $value->user->email,
                    'roles' => $roles,
                    'Đơn giá' => $value->price[0]->price,
                    'Tổng tiền' => $totalPriceOfTicket,
                    'Tổng suất ăn' => Ticket::where('dateregister', $this->dmy)->count()
                ];
            }else{
                $array[$key] = [
                    'id' => $key + 1,
                    'name' => $value->user->name,
                    'email' => $value->user->email,
                    'roles' => $roles,
                    'Đơn giá' => $value->price[0]->price,
                ];
            }
        }
        return (collect($array));
    }
}
