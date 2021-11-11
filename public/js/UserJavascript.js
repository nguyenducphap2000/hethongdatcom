        $('#month').click(function(e){
            $(this).closest('form').submit();
        });

        $('#year').click(function(e){
            $(this).closest('form').submit();
        });
        $("#allday").click(function(){
            var dayarray = $("#days").find("input"); 
            var price = +$("#lunch-infor").find("#price").text()*1000;
            for (let i = 1; i <= dayarray.length; i++) {
                if($("#allday").is(":checked")){
                    if(!$("#days").find("#"+i).attr("disabled")){
                        $("#days").find("#"+i).prop('checked',true);
                        $("#days").find("#"+i).attr('class','table-btn active');
                    }
                }else{
                    if(!$("#days").find("#"+i).attr("disabled")){
                        $("#days").find("#"+i).prop('checked',false);
                        $("#days").find("#"+i).attr('class','table-btn');
                    }
                }
            } 
            caculatePrice($("#days").find(":checked").length, price);
        });
        function dayclick(num){
            var countday = $("#lunch-infor").find("#countday");
            var count = +$("#lunch-infor").find("#countday").text();
            var price = +$("#lunch-infor").find("#price").text()*1000;
            var check = $("#"+num).prop("checked");
            console.log($("#days").find("#"+num).attr("class"));
            if($("#days").find("#"+num).attr("class")==='table-btn active')
            {
                count--;
                countday.text(count);
                $("#days").find("#"+num).attr('class', 'table-btn');
            }else{
                count++;
                countday.text(count);
                $("#days").find("#"+num).attr('class', 'table-btn active');
            }
            caculatePrice(count,price);
        }
        function caculatePrice(totalday, price){
            var htmlTotal = $("#lunch-infor").find("#total");
            htmlTotal.text(formatNumber(totalday * price, '.', ','));
            $("#lunch-infor").find("#countday").text(totalday);
        }
        $("#save").click('submit',function(e){
            e.preventDefault();
            var monthSelect = $("#month").find(":selected").val();
            var yearSelect = $("#year").find(":selected").val();
            var totalday = $("#lunch-infor").find("#countday").text();
            if(monthSelect == 0 || yearSelect==0){
                return swal({
                    title: "Opp!",
                    text: "Bạn chưa chọn tháng và năm !!!",
                    icon: "warning",
                    });
            }else if(totalday==='0'){
                return swal({
                    title: "Opp!",
                    text: "Bạn chưa chọn ngày !!!",
                    icon: "warning",
                    });
            }
            var allday = $("#table tr").find("a");
            var totalprice = $("#lunch-infor").find("#total").text();
            const dayselect = [];
            for(let i=0; i < allday.length; i++){
                if(allday[i].className === "table-btn active"){
                    dayselect.push(allday[i].id);
                }
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
                    }
                });
            $.ajax({
                url:"{{ url('tickets') }}",
                method:"post",
                data: {
                    formdata: $("#ticketstore").serializeArray()
                    },
                success: function(result){
                    var allday = $("#table tr").find("a");
                    for(let i=0; i < allday.length; i++){
                        document.getElementById(allday[i].id).className = "table-btn";
                    }
                    $('#allday').prop('checked',false);
                    console.log(result.fail);
                    if(result.fail){
                        swal({
                        title: "Ngày bạn chọn đã được đặt hoặc quá hạn !!",
                        text: result.dateExist.join("\n"),
                        icon: "error",
                        }).then(function(){
                            location.reload();
                        });
                    }else{
                        swal({
                        title: "Good job!",
                        text: "Bạn đã đặt cơm trưa thành công !",
                        icon: "success",
                        }).then(function(){
                            location.reload();
                        });
                    }
                }
            });
        });

        $("#update").click('submit',function(e){
            e.preventDefault();
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
                url:"{{ url('tickets/update') }}",
                method:"post",
                data: {
                    dataUpdate: data,
                    notChecked: notchecked
                    },
                success: function(result){
                    console.log(result);
                    if(result.fail){
                        swal({
                        title: "Update Failed",
                        text: "Cập nhật ngày ăn không thành công !!",
                        icon: "error",
                        }).then(function(){
                            location.reload();
                        });
                    }else{
                        swal({
                        title: "Good job!",
                        text: "Bạn đã cập nhật thành công !",
                        icon: "success",
                        }).then(function(){ 
                            location.reload();
                        });
                    }
                }
            });
        });
        var price = +$("#lunch-infor").find("#price").text()*1000;
        $("#countday").text($("#days").find("input:checked").length);
        $("#total").text(formatNumber(+$("#days").find("input:checked").length * price, '.', ','));

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

        var daychecked = $("#days").find("input:checked");
        if(daychecked.length > 0){
            $("#save").attr("hidden","hidden");
            $("#update").attr("hidden",false);
        }else if (daychecked.length === 0){
            $("#save").attr("hidden",false);
            $("#update").attr("hidden","hidden");
        } 