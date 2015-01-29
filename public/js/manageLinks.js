jQuery(function ($) {


    var userid = $("#userid").val().trim();
    var apikey = $("#apikey").val().trim();
    var thumbsUp = $(".thumbsUp");
    var thumbsDown = $(".thumbsDown");
    var votes=$(".votes");

    var alertBox=$("#alert");
    var message=$("#message");

    alertBox.dialog({
        autoOpen: false,
        resizable: false,
        height:170,
        width:320,
        modal: true,
        closeOnEscape: false,
        buttons: {
            "Ok": function() {
                alertBox.dialog("close");
            }
        }
    });

    thumbsUp.hover(function(){
        $(this).css('cursor', 'hand');
    }, function(){
        $(this).css('cursor', 'pointer');
    });
    thumbsDown.hover(function(){
        $(this).css('cursor', 'hand');
    }, function(){
        $(this).css('cursor', 'pointer');
    });
    $.each(votes, function(){
        var linkid=$(this).find("#linkid").val();
        var thumbsU=$(this).find(".glyphicon-thumbs-up");
        var thumbsD=$(this).find(".glyphicon-thumbs-down");
        $.ajax({
            type: "GET",
            url: "/" + url_base + "news/" + linkid + "/getVote",
            dataType: "json"
        }).done(function (data) {

            $.each(data, function (index, element) {
                if(element.vote != 0) {
                    if (element.vote == 1) {
                        thumbsU.addClass("voted");
                    }
                    else if (element.vote == 2) {
                        thumbsD.addClass("voted");
                    }
                }
            });


        });
    });

    $.each(thumbsUp, function() {
        var votesUp=$(this).siblings(".votesUp");
        var votesDown=$(this).siblings(".votesDown");
        var linkId=$(this).siblings("#linkid").val();
        var thumbDown=$(this).siblings(".thumbsDown");

        $(this).on("click", function () {
        var thumbUp=$(this);

            if (thumbDown.hasClass("voted") & userid != 0) {
                $.ajax({
                    type: 'PUT',
                    url: "/" + url_base + "links/"+linkId+"/votes/"+userid,
                    data:{
                        link_id : linkId,
                        user_id : userid,
                        vote : 1,
                        api_key: apikey
                    }
                }).complete(function (data) {

                    thumbDown.removeClass("voted");
                    votesDown.text(parseInt(votesDown.text()) - 1);
                    thumbUp.addClass("voted");
                    votesUp.text(parseInt(votesUp.text()) + 1);
                });
            }
            else {
                if (userid != 0 & !thumbUp.hasClass("voted")) {

                    $.ajax({
                        type: 'POST',
                        url: "/" + url_base + "links/"+linkId+"/votes",
                        dataType: "json",
                        data: {
                            user_id: userid,
                            link_id: linkId,
                            vote: 1,
                            api_key: apikey
                        }
                    }).complete(function (data) {

                        thumbUp.addClass("voted");

                        votesUp.text(parseInt(votesUp.text()) + 1);
                    });


                }
                else if (userid != 0 & thumbUp.hasClass("voted")) {
                    $.ajax({
                        type: 'DELETE',
                        url: "/" + url_base + "links/"+linkId+"/votes/"+userid + "?api_key=" + apikey
                    }).complete(function (data) {

                        thumbUp.removeClass("voted");
                        votesUp.text(parseInt(votesUp.text()) - 1);
                    });


                }
                else if(userid == 0)
                {

                    alertBox.dialog("open");
                    message.text("You must be logged in to vote!");
                }
            }
        });

    });


    $.each(thumbsDown, function() {
        var votesUp=$(this).siblings(".votesUp");
        var votesDown=$(this).siblings(".votesDown");
        var linkId=$(this).siblings("#linkid").val();
        var thumbUp=$(this).siblings(".thumbsUp");

        $(this).on("click", function () {
            var thumbDown=$(this);
            if (thumbUp.hasClass("voted") & userid != 0) {
                $.ajax({
                    type: 'PUT',
                    url: "/" + url_base + "links/"+linkId+"/votes/"+userid,
                    data:{
                        link_id : linkId,
                        user_id : userid,
                        vote : 2,
                        api_key: apikey
                    }
                }).complete(function (data) {
                    thumbUp.removeClass("voted");
                    votesUp.text(parseInt(votesUp.text()) - 1);
                    thumbDown.addClass("voted");
                    votesDown.text(parseInt(votesDown.text()) + 1);
                });
            }
            else {
                if (userid != 0 & !thumbDown.hasClass("voted")) {
                    $.ajax({
                        type: 'POST',
                        url: "/" + url_base + "links/"+linkId+"/votes",
                        dataType: "json",
                        data: {
                            user_id: userid,
                            link_id: linkId,
                            vote: 2,
                            api_key: apikey
                        }
                    }).complete(function (data) {

                        thumbDown.addClass("voted");

                        votesDown.text(parseInt(votesDown.text()) + 1);
                    });


                }
                else if (userid != 0 & thumbDown.hasClass("voted")) {
                    $.ajax({
                        type: 'DELETE',
                        url: "/" + url_base + "links/"+linkId+"/votes/"+userid + "?api_key=" + apikey
                    }).complete(function (data) {

                        thumbDown.removeClass("voted");
                        votesDown.text(parseInt(votesDown.text()) - 1);
                    });


                }
                else if(userid == 0)
                {

                    alertBox.dialog("open");
                    message.text("You must be logged in to vote!");
                }
            }
        });

    });





});