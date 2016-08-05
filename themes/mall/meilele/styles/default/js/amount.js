$(function () {
        var t = $("#text_box1");
        $("#add1").click(function () {
            t.val(parseInt(t.val()) + 1)
            setTotal(); GetCount();
        })
        $("#min1").click(function () {
            t.val(parseInt(t.val()) - 1)
            setTotal(); GetCount();
        })
        function setTotal() {

            $("#total1").html((parseInt(t.val()) * 8).toFixed(2));
            $("#newslist-1").val(parseInt(t.val()) * 8);
        }
        setTotal();
    })


$(function () {
        var t = $("#text_box2");
        $("#add2").click(function () {
            t.val(parseInt(t.val()) + 1)
            setTotal(); GetCount();
        })
        $("#min2").click(function () {
            t.val(parseInt(t.val()) - 1)
            setTotal(); GetCount();
        })
        function setTotal() {

            $("#total2").html((parseInt(t.val()) * 15).toFixed(2));
            $("#newslist-2").val(parseInt(t.val()) * 15);
        }
        setTotal();
    })



$(function () {
        $(".quanxun").click(function () {
            setTotal();
            //alert($(lens[0]).text());
        });
        function setTotal() {
            var len = $(".tot");
            var num = 0;
            for (var i = 0; i < len.length; i++) {
                num = parseInt(num) + parseInt($(len[i]).text());

            }
            //alert(len.length);
            $("#zong1").text(parseInt(num).toFixed(2));
            $("#shuliang").text(len.length);
        }
        //setTotal();
    })