jQuery(function ($) {


    //$("textarea").css('width', '350px');
   // $("textarea").css('height', '100px');
    $("textarea").addClass('form-control');
    $("input[type=text]").addClass('form-control');
    $("input[type=submit]").addClass(' btn btn-success');
    $("form").addClass("form-horizontal col-xs-10 col-sm-6 col-md-7 ")




    //$("input[type=text]").css('width', '350px');

    $(".checkbox").on('click', function(){
        if($(this).parent().parent().hasClass("selected"))
        {
            $(this).parent().parent().removeClass("selected")
        }
        else if(!$(this).parent().parent().hasClass("selected"))
        {
            $(this).parent().parent().addClass("selected")
        }
    });

    $(".checkall").on('click', function(){

        if($(this).is(":checked"))
        {
            $(".checkbox").prop('checked', true);
            $(".checkbox").parent().parent().addClass("selected");

        }
        else
        {
            $(".checkbox").prop('checked', false);
            $(".checkbox").parent().parent().removeClass("selected");
        }

    });

    $("#submitLinks").on('click', function(){

        var selected=$(".selected");

        $.each(selected, function(){
            url=$(this).find(".url").html();
            description=$(this).find(".description").text();
            //$description.replace(/&nbsp;/, '');
            //$description.replace(/"/, '``');


            $.ajax({
                type: 'POST',
                url: "/" + url_base + "links",
                data:{
                    url: url,
                    description: description,
                    user_id: $("#userid").val().trim(),
                    api_key: $("#apikey").val().trim()
                }
            }).success(function (data) {

                window.location = "/" + url_base + "news";


            });

        });

    });


});