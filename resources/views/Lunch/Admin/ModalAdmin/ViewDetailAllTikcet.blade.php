<div class="modal fade bd-example-modal-lg" id="bd-example-modal-lg{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #FCB71E;">
          <h5 class="modal-title" id="exampleModalLabel">Chi tiết vé ăn tháng {{ date('m',strtotime($dmy)) }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="overflow: auto; height: 500px;">
            <div class="UserName">
                <label for=""><b>Tên:</b> </label>
                <label for="">{{ $value->name }}</label>
            </div>
            <div class="ticket-register">
              <label for=""><b>Tổng số vé trong tháng:</b> </label>
              <label for="">{{ count($value->ticket) }}</label>
            </div>
            <div class="detail-dayregister">
              <label for=""><b>Những ngày đã đăng ký ăn:</b> </label>
              <div class="dayregistered">
                @php
                  $dateregister = array();
                  foreach($value->ticket as $day){
                    array_push($dateregister,$day->dateregister);
                  }
                @endphp
                  {{-- <p>{{ date('d-m-Y (l)',strtotime($day->dateregister)) }} - <label for="" class="PriceEachDay">{{ $day->price[0]['price'] }}</label></p> --}}
                  @for ($j=1; $j <= count($listday); $j++)
                    {{-- @foreach ($value->ticket as $day) --}}
                    <label 
                      class="{{ in_array($listday[$j-1],$dateregister) ? 'Detail-Day-Selected' : 'Detail-Day-NotSeleted' }}" for="">
                      {{ $j }}
                    </label>
                    {{-- @endforeach --}}
                  @endfor  
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>