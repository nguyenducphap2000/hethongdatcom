@extends('layouts.app')
@section('content')
  <div class="admin-container">
    <div class="admin-menu">
        <div class="card-header" style="background-color: #FCB71E;"><h3>Chuyển trang</h3></div>
        <div class="c3">
            <ul class="nav flex-column">
                <li class="nav-item admin">
                  <a class="nav-link" href="{{ route('StatisticByDay',date('Y-m-d')) }}"
                  >Danh sách ăn trưa</a>
                </li>
                <li class="nav-item admin">
                  <a class="nav-link active" href="{{ route('StatisticIndex') }}">Thống kê ăn trưa</a>
                </li>
                <li class="nav-item admin">
                  <a class="nav-link" href="{{ route('pricesIndex') }}">Quản lý giá </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="admin-manage">
      <div class="tab-content" id="myTabContent">
        @yield('tabcontent')
      </div>
    </div>
  </div>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $("#selectMonthYear").click(function(){
            $(this).closest('form').submit();
        });
        $("#selectprice").click(function(){
            $(this).closest('form').submit();
        })
        $("#totalprice").text(formatNumber(+$("#totalprice").attr("value"),'.',',') + " VND");
        $.each($("#selectprice").find('.price'), function( key, value ) {
            value.text = formatNumber(+value.text,'.',',') + " VND";
        });
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

    {{-- price management --}}
    <script>
      $.each($("#table").find('.PriceInTable'), function( key, value ) {
          $(value).html(formatNumber(+$(value).html(),'.',',') + " VND");
      });
      function DeleteButton(id){
          swal({
              title: "Are you sure?",
              text: "Bạn có chắc muốn xóa !",
              icon: "warning",
              buttons: true,
              dangerMode: true,
              })
              .then((willDelete) => {
              if (willDelete) {
                  $("#"+id).submit();
              }
          });
      };

      function EditButton(id,price){
          $('.customModal').attr('id',"exampleModal"+id);
          $('[name = price]').val(price);
          $('#updatePriceForm').find('button[type=submit]').val(id);
      }
      $(document).on('submit','#updatePriceForm',function(e){
          e.preventDefault();
          var price = $(this).find('input[name=price]').val(),
              id = $(this).find('button[type=submit]').val(),
              token = $(this).find('input[name=_token]').val();
          var url = "{{ route('pricesUpdate', ':id') }}";
          url = url.replace(':id',id);
          $.ajax({
              url:url,
              method:"put",
              data: {
                  price: price,
                  _token: token
                  },
              success:function(res){
                  console.log('#exampleModal'+id);
                  $('#exampleModal'+id).modal('hide');
                  if(res.success){
                      swal({
                      title: "Successfully",
                      text: res.success,
                      icon: "success",
                      }).then(function(){
                          location.reload();
                      });
                  }else if(res.error){
                      swal({
                      title: "Failed",
                      text: res.error,
                      icon: "error",
                      }).then(function(){
                          location.reload();
                      });
                  }
              },
              error:function (res){
                  $('#exampleModal'+id).modal('hide');
                  var errs = res.responseJSON.errors.price[0];
                  swal({
                      title: "Failed",
                      text: errs,
                      icon: "error",
                      }).then(function(){
                          location.reload();
                  });
              }
          });
      });
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

  {{-- statistic by day --}}
  <script>
    $("#selectDay").click(function(){
        $(this).closest('form').submit();
    });
  </script>
@endsection