$( document ).ready(function() {
    $( "body" ).on( "click",'div.forum-page-navigation > font > a', function(event) {
        event.preventDefault();
        var data_url = $(this).attr('href'), link = $(this);
                if(data_url)
                {
                    console.log($(this));
                    $.ajax({
                        url : data_url + '&AJAX_MODE=Y',
                        success : function(resp)
                        {
                            if (resp.length)
                            {
                                var dataResp = JSON.parse(resp);
                                if (dataResp.CONTENT)
                                {
                                    $("#body").empty();
                                    $("#body").append(dataResp.CONTENT);
                                    $("#navigation").empty();
                                    $("#navigation").append(dataResp.NAVIGATE);
                                }
                            }
                        },
                        error : function()
                        {
                            alert('AJAX FORM ERROR');
                        }
                    });
                }
    });
});