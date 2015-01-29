jQuery(function ($) {
    var userid = $("#userid").val().trim();
    var linkid = $("#linkid").val().trim();
    var apikey = $("#apikey").val().trim();
    var upPar = $("#thumbsUp").parent();
    var downPar = $("#thumbsDown").parent();
    var thumbsUp = $("#thumbsUp");
    var thumbsDown = $("#thumbsDown");
    var comments=$(".comments");

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


    if(userid)
    {

        var dialog=$("#dialog");
        var editAction=$(".edit");
        var deleteAction=$(".delete");
        var newComment=$(".newcomment");
        var postComment=$("#postComment");
        var deleteId=null;
        var admin=false;
        var userComment=0;
        var deleteLink=$("#deleteLink");

        function deleteEntry()
        {
            if(admin == false)
            {
                $.ajax({
                    type: 'delete',
                    url: "/" + url_base + "links/"+linkid+"/comments/" + deleteId+"?api_key="+apikey
                }).done(function (data) {

                    if(data == false)
                    {
                        alertBox.dialog('open');
                        message.text('You are not longer logged in!');
                    }
                    else {

                        $('.commentid[value=' + data['comment_id'] + ']').parent().parent().parent().remove();
                    }

                });
            }
            else if(admin == true)
            {

                $.ajax({
                    type: 'PUT',
                    url: "/" + url_base + "links/" + linkid + "/comments/" + deleteId,
                    data:{
                        comment_text: "The comment was deleted because its content was offensive or inappropriate.",
                        link_id: linkid,
                        api_key: apikey,
                        user_id: userComment
                    }
                }).done(function (data) {

                    if(data == false)
                    {
                        alertBox.dialog('open');
                        message.text('You are not longer logged in!');
                    }
                    else {

                        $('.commentid[value=' + deleteId + ']').parent().siblings(".comment").find(".commenttext").text("The comment was deleted because its content was offensive or inappropriate.");
                        deleteId = null;
                    }

                });
            }

        }

        dialog.dialog({
            autoOpen: false,
            resizable: false,
            height:200,
            width:320,
            modal: true,
            closeOnEscape: false,
            buttons: {
                "Yes": function() {
                    deleteEntry();
                    dialog.dialog("close");
                },
                "No": function() {
                   deleteId=null;
                    dialog.dialog("close");
                }
            }
        });

        deleteLink.dialog({
            autoOpen: false,
            resizable: false,
            height:200,
            width:320,
            modal: true,
            closeOnEscape: false,
            buttons: {
                "Yes": function() {
                    deleteLinkAction();
                    deleteLink.dialog("close");
                },
                "No": function() {
                    deleteLink.dialog("close");
                }
            }
        });

       function deleteLinkAction()
       {
           $.ajax({
               type: 'delete',
               url: "/" + url_base + "links/"+linkid+"?api_key="+apikey
           }).done(function (data) {

                   window.location = "/" + url_base + "news";


           });
       }


        $(".deleteLinkAdmin").on('click', function() {
            deleteLink.dialog("open");

        });

        $(".deleteLinkAdmin").hover(function(){
            $(this).css('cursor', 'hand');
        }, function(){
            $(this).css('cursor', 'pointer');
        });



        editAction.hover(function(){
            $(this).css('cursor', 'hand');
        }, function(){
            $(this).css('cursor', 'pointer');
        });
        deleteAction.hover(function(){
            $(this).css('cursor', 'hand');
        }, function(){
            $(this).css('cursor', 'pointer');
        });

        var commentid=null;
        var commentidObj=null;
        var text=null;
            comments.on('click', '.edit', function(){

                text=$(this).parent().siblings(".comment").find(".commenttext").text().trim();
                commentidObj=$(this).siblings(".commentid");
                commentid=commentidObj.val();
                newComment.val(text);
                postComment.text("Edit");
                newComment.focus();
            });



            comments.on('click', '.delete', function(){
                deleteId=$(this).siblings(".commentid").val();
                dialog.dialog("open");

            });

            comments.on('click', '.deleteAdmin', function(){
                deleteId=$(this).siblings(".commentid").val();
                userComment=$(this).siblings(".commentUser").val();

                admin=true;
                dialog.dialog("open");
            });

            postComment.on('click', function(){
                var newtext=newComment.val();
                if(commentid)
                {

                    $.ajax({
                        type: 'PUT',
                        url: "/" + url_base + "links/" + linkid + "/comments/" + commentid,
                        data:{
                            comment_text: newtext,
                            link_id: linkid,
                            api_key: apikey,
                            user_id: userid
                        }
                    }).done(function (data) {

                        if(data == false)
                        {
                            alertBox.dialog('open');
                            message.text('You are not longer logged in!');
                        }
                        else {
                            $.each(data, function (inxed, element) {

                                $('.commentid[value=' + commentid + ']').parent().siblings(".comment").find(".commenttext").text(element.comment_text);
                                newComment.val("");
                                postComment.text("Post");
                                commentid = null;
                                commentidObj = null;


                            })
                        }

                    });


                }
                else
                {
                    $.ajax({
                        type: 'POST',
                        url: "/" + url_base + "links/" + linkid + "/comments",
                        data:{
                            comment_text: newtext,
                            link_id: linkid,
                            api_key: apikey,
                            user_id: userid
                        }
                    }).success(function (data) {

                        if(data == false)
                        {
                            alertBox.dialog('open');
                            message.text('You are not longer logged in!');
                        }
                        else {

                            $.each(data, function (index, element) {
                                newComment.val("");
                                postComment.text("Post");
                                $(".comments").append("<div class='container'> <div class='well col-xs-9 col-sm-9 col-md-7 col-md-offset-2 col-xs-offset-2 col-sm-offset-2 row'> <div class='row'><span class='badge col-xs-8 col-sm-10 col-md-4'>" + $("#username").val() + "</span><span class='glyphicon glyphicon-remove pull-right delete smallmargin' ></span><span class='glyphicon glyphicon-pencil pull-right smallmargin edit'></span><input type='hidden' class='commentid' value='" + element.comment_id + "'></div><div class='margincomment row comment'><div class='col-xs-17 col-sm-15 col-md-11 commenttext'>" + element.comment_text + "</div></div></div></div>");
                                commentid = null;
                                commentidObj = null;
                            })
                        }
                    });


                }


            });





    }

    $.ajax({
        type: "GET",
        url: "/" + url_base + "news/" + linkid + "/getVote",
        dataType: "json"
    }).done(function (data) {
        $.each(data, function (index, element) {
            if (element.vote == 1) {
                upPar.addClass("voted");
            }
            else if (element.vote == 2) {
                downPar.addClass("voted");
            }
        });


    });

    upPar.on("click", function () {

        if (downPar.hasClass("voted") & userid != 0) {
            $.ajax({
                type: 'PUT',
                url: "/" + url_base + "links/"+linkid+"/votes/" + userid,
                data:{
                    link_id: linkid,
                    user_id: userid,
                    api_key: apikey,
                    vote: 1
                }
            }).complete(function (data) {

                downPar.removeClass("voted");
                thumbsDown.text(parseInt(thumbsDown.text()) - 1);
                upPar.addClass("voted");
                thumbsUp.text(parseInt(thumbsUp.text()) + 1);
            });
        }
        else {
            if (userid != 0 & !upPar.hasClass("voted")) {

                $.ajax({
                    type: 'POST',
                    url: "/" + url_base + "links/"+linkid+"/votes/"+userid,
                    dataType: "json",
                    data: {
                        user_id: userid,
                        link_id: linkid,
                        vote: 1,
                        api_key: apikey
                    }
                }).complete(function (data) {

                    upPar.addClass("voted");

                    thumbsUp.text(parseInt(thumbsUp.text()) + 1);
                });


            }
            else if (userid != 0 & upPar.hasClass("voted")) {
                $.ajax({
                    type: 'DELETE',
                    url:  "/" + url_base + "links/"+ linkid +"/votes/"+ userid + "?api_key=" + apikey
                }).complete(function (data) {

                    upPar.removeClass("voted");
                    thumbsUp.text(parseInt(thumbsUp.text()) - 1);
                });


            }
            else if(userid == 0)
            {

                alertBox.dialog("open");
                message.text("You must be logged in to vote!");
            }
        }

    });


    downPar.on("click", function () {

        if (upPar.hasClass("voted") & userid != 0) {

            $.ajax({
                type: 'PUT',
                url: "/" + url_base + "links/"+linkid+"/votes/" + userid,
                data:{
                    link_id: linkid,
                    user_id: userid,
                    api_key: apikey,
                    vote: 2
                }
            }).complete(function (data) {

                upPar.removeClass("voted");
                thumbsUp.text(parseInt(thumbsUp.text()) - 1);
                downPar.addClass("voted");
                thumbsDown.text(parseInt(thumbsDown.text()) + 1);
            });
        }
        else {

            if (userid != 0 & !downPar.hasClass("voted")) {

                $.ajax({
                    type: 'POST',
                    url: "/" + url_base + "links/"+linkid+"/votes/"+userid,
                    dataType: "json",
                    data: {
                        user_id: userid,
                        link_id: linkid,
                        vote: 2,
                        api_key: apikey
                    }
                }).complete(function (data) {

                    downPar.addClass("voted");
                    thumbsDown.text(parseInt(thumbsDown.text()) + 1);
                });


            }
            else if (userid != 0 & downPar.hasClass("voted")) {
                $.ajax({
                    type: 'DELETE',
                    url:  "/" + url_base + "links/"+linkid+"/votes/"+userid + "?api_key=" + apikey
                }).complete(function (data) {

                    downPar.removeClass("voted");
                    thumbsDown.text(parseInt(thumbsDown.text()) - 1);
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
