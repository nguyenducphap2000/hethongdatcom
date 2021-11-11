    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $('#month').click(function(e){
            $(this).closest('form').submit();
        });

        $('#year').click(function(e){
            $(this).closest('form').submit();
        });
        const DaySelected = [];
        $("#allday").click(function(){
            var totalBefore = +$("#total").attr("value");
            currentDay = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+(today.getDate());
            var totalCurrent = 0;
            var dayarray = $("#days").find("input"); 
            var price = +$("#lunch-infor").find("#price").attr("value");
            for (let i = 1; i <= dayarray.length; i++) {
                var t7cn = new Date($("#year").find(":selected").val(),
                                    $("#month").find(":selected").attr("class"),
                                    $("#days").find("#"+i).attr("id"));
                if(t7cn.getDay()==6 || t7cn.getDay()==0){
                    $("#days").find("#"+i).attr('class','table-btn');
                    $("[for='"+i+"']").attr("class",'dayselect-label');
                }else if(new Date($("#days").find("#"+i).val()) < new Date(currentDay)){
                    if($("#days").find("#"+i).attr('class') == 'table-btn active'){
                        $("#days").find("#"+i).prop('checked',true);
                        $("#days").find("#"+i).attr('class','table-btn active');
                        $("[for='"+i+"']").attr("class",'dayselect-label active');
                    }else{
                        $("#days").find("#"+i).attr('class','table-btn');
                        $("[for='"+i+"']").attr("class",'dayselect-label');
                    }
                }else if(new Date($("#days").find("#"+i).val()) > new Date(currentDay)){
                    if($("#days").find("#"+i).attr("class") =='table-btn'){
                        DaySelected.push($("#days").find("#"+i).val());
                    }
                    $("#days").find("#"+i).prop('checked',true);
                    $("#days").find("#"+i).attr('class','table-btn active');
                    $("[for='"+i+"']").attr("class",'dayselect-label active');
                }
            } 
            totalCurrent = $("#days").find(":checked").length * price;
            caculatePrice($("#days").find(":checked").length, totalCurrent);
        });
        

        function dayclick(num){
            var day = $("#days").find("#"+num).val();
            if(DaySelected.indexOf(day) != -1){
                DaySelected.splice(DaySelected.indexOf(day),1);
            }else{
                DaySelected.push($("#days").find("#"+num).val());
            }

            var totalPrice = +$("#total").attr("value");
            var countday = $("#lunch-infor").find("#countday");
            var count = +$("#lunch-infor").find("#countday").text();
            var price = +$("#lunch-infor").find("#price").attr("value");
            var t7cn = new Date($("#year").find(":selected").val(),$("#month").find(":selected").attr("class"),$("#days").find("#"+num).attr("id"));
            if($("#days").find("#"+num).attr("class")==='table-btn active')
            {
                count--;
                countday.text(count);
                $("#days").find("#"+num).attr('class', 'table-btn');
                $("[for='"+num+"']").attr("class",'dayselect-label')
                $("#"+num).prop("checked",false);
                totalPrice-=price;
            }else{
                if(t7cn.getDay()==6 || t7cn.getDay()==0){
                    $("#days").find("#"+num).attr('class', 'table-btn');
                    $("[for='"+num+"']").attr("class",'dayselect-label')
                    $("#"+num).prop("checked",false);
                }else{
                    count++;
                    countday.text(count);
                    $("#days").find("#"+num).attr('class', 'table-btn active');
                    $("[for='"+num+"']").attr("class",'dayselect-label active')
                    $("#"+num).prop("checked",true);
                    totalPrice+=price;
                }
            }
            caculatePrice(count,totalPrice);
        }
        function caculatePrice(totalday, TotalPrice){
            $("#total").text(formatNumber(TotalPrice, '.', ','));
            $("#total").attr("value",TotalPrice);
            $("#lunch-infor").find("#countday").text(totalday);
        }
    </script>
    <script>
        $("#update").click('submit',function(e){
            e.preventDefault();
            console.log(DaySelected);
            var monthSelect = $("#month").find(":selected").val();
            var yearSelect = $("#year").find(":selected").val();
            var totalday = $("#lunch-infor").find("#countday").text();
            console.log();
            if(monthSelect == 0 || yearSelect==0){
                return swal({
                    title: "Opp!",
                    text: "Bạn chưa chọn tháng và năm !!!",
                    icon: "warning",
                    }).then(function(){
                            location.reload();
                        });
            }else if(totalday==='0' && $("#days").find(":checked").length === 0){
                    return swal({
                    title: "Bạn chưa chọn ngày !!",
                    text: "Nhấn ok để  hủy đăng ký ngày ăn, Cancel để chọn lại",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    })
                    .then((willDelete) => {
                    if (willDelete) {
                        swal("Hủy đăng ký ăn thành công", {
                        icon: "success",
                        }).then(function(){
                            var arrayNotCheck = $("#days").find("input:checkbox:not(:checked)");
                            var notchecked = [];
                            for(let i=0; i<arrayNotCheck.length; i++){
                                notchecked.push(arrayNotCheck[i]['value']);
                            }
                            var data = $("#ticketstore").serializeArray();
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                                    }
                                });
                            $.ajax({
                                url:"{{ route('ticketsUpdate') }}",
                                method:"post",
                                data: {
                                    dataUpdate: DaySelected,
                                    },
                                });
                            location.reload();
                        });
                    } else {
                        location.reload();
                    }
                    });
            }
            var arrayNotCheck = $("#days").find("input:checkbox:not(:checked)");
            var notchecked = [];
            for(let i=0; i<arrayNotCheck.length; i++){
                notchecked.push(arrayNotCheck[i]['value']);
            }
            var data = $("#ticketstore").serializeArray();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                    }
                });
            $.ajax({
                url:"{{ route('ticketsUpdate') }}",
                method:"post",
                data: {
                    dataUpdate: DaySelected,
                    },
                success: function(result){
                    if(result.fail){
                        swal({
                        title: "Failed",
                        text: "Đăng ký ngày ăn không thành công !!",
                        icon: "error",
                        }).then(function(){
                            location.reload();
                        });
                    }else{
                        swal({
                        title: "Successfully",
                        text: "Đăng ký ngày ăn thành công !",
                        icon: "success",
                        }).then(function(){ 
                            location.reload();
                        });
                    }
                }
            });
        });
    </script> 
     <script>
        var price = +$("#lunch-infor").find("#price").text();
        $("#countday").text($("#days").find("input:checked").length);
        $("#price").text(formatNumber(+$("#lunch-infor").find("#price").text(), '.', ','));
        $("#total").text(formatNumber(+$("#total").text(), '.', ','))
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
    <script>
        var today = new Date();
        var currentDay = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+(today.getDate()+1);
        var dayarray = $("#days").find("input"); 
            for (let i = 1; i <= dayarray.length; i++) {
                var SunSat = new Date($("#year").find(":selected").val(),$("#month").find(":selected").attr("class"),$("#days").find("#"+i).attr("id"));
                if(new Date($("#days").find("#"+i).val()) < new Date(currentDay)){
                    $("[for='"+i+"']").css({ "cursor": "not-allowed"});
                    if($("[for='"+i+"']").attr("class")==='dayselect-label'){
                        $("[for='"+i+"']").css({ "background-color": "gray"});
                    }
                }else if(SunSat.getDay()==6){
                        $("[for='"+i+"']").css({ "cursor": "not-allowed"});
                        $("[for='"+i+"']").css({ "background-color": "gray"});
                        $("[for='"+i+"']").hover(function(){
                            $("[for='"+i+"']").text('T7');
                        },function(){
                            $("[for='"+i+"']").text(i);
                        });
                }else if(SunSat.getDay()==0){
                        $("[for='"+i+"']").css({ "cursor": "not-allowed"});
                        $("[for='"+i+"']").css({ "background-color": "gray"});
                        $("[for='"+i+"']").hover(function(){
                            $("[for='"+i+"']").text('CN');
                        },function(){
                            $("[for='"+i+"']").text(i);
                        });
                }else{
                    console.log('bigger');
                }
            } 

    </script>