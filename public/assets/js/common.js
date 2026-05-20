function GetCallAjax(_url) {
    $(".loading").show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: _url,
        type: "GET",
        success: function(response) {
            if (response) {
                $(".modal").remove();
//                $('body').after(response);
                $(response).appendTo("body")
                $(".loading").hide();
            }
            $('body').removeAttr('style').css({"--kt-toolbar-height": "55px", "--kt-toolbar-height-tablet-and-mobile": "55px"});
        },
        error: function(data) {
            var response = data.responseJSON.errors;
            var html = '';
            $.each(response, function(i, val) {
                html += '<p>' + val[0] + '</p>';
            });
            $(".loading").hide();
            if (html == '') {
                html = 'Something went wrong!';
            }
            swal({html: true, title: "Error!", text: html, icon: "error", type: "error"});
        }
    });
}
function GetPopupCallAjax(_url) {
    $(".loading").show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: _url,
        type: "GET",
        success: function(response) {
            if (response) {
//                $('body').after(response);
                $(response).appendTo("body")
                $(".loading").hide();
            }
            $('body').removeAttr('style').css({"--kt-toolbar-height": "55px", "--kt-toolbar-height-tablet-and-mobile": "55px"});
        },
        error: function(data) {
            var response = data.responseJSON.errors;
            var html = '';
            $.each(response, function(i, val) {
                html += '<p>' + val[0] + '</p>';
            });
            $(".loading").hide();
            if (html == '') {
                html = 'Something went wrong!';
            }
            swal({html: true, title: "Error!", text: html, icon: "error", type: "error"});
        }
    });
}

function PostPutAjaxCall(_url, _method, data) {
    $(".loading").show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: _url,
        data: data,
        type: _method,
        success: function(response) {
            if (response) {
                $(".modal,.fade.show").remove();
                $(".loading").hide();
                //$('.data-table').DataTable().ajax.reload();
                $.each(window.LaravelDataTables, function(key, value) {
                    window.LaravelDataTables[key].ajax.reload()
                });
                swal({
                    title: "Status!",
                    text: response.message,
                    type: "success"
                });
                $('body').removeAttr('style').css({"--kt-toolbar-height": "55px", "--kt-toolbar-height-tablet-and-mobile": "55px"});
            }
            $(".loading").hide();
        },
        error: function(data) {
            var response = data.responseJSON.errors;
            var html = '';
            $.each(response, function(i, val) {
                html += '<p>' + val[0] + '</p>';
            });
            $(".loading").hide();
            if (html == '') {
                html = 'Something went wrong!';
            }
            swal({html: true, title: "Error!", text: html, icon: "error", type: "error"});
        }
    });
}

function PostPutPopupAjaxCall(_url, _method, data) {
    $(".loading").show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: _url,
        data: data,
        type: _method,
        success: function(response) {
            if (response) {
                $(".loading").hide();
                $.each(window.LaravelDataTables, function(key, value) {
                    window.LaravelDataTables[key].ajax.reload()
                });
                swal({
                    title: "Status!",
                    text: response.message,
                    type: "success"
                });
                $('body').removeAttr('style').css({"--kt-toolbar-height": "55px", "--kt-toolbar-height-tablet-and-mobile": "55px"});
            }
            $(".loading").hide();
        },
        error: function(data) {
            var response = data.responseJSON.errors;
            var html = '';
            $.each(response, function(i, val) {
                html += '<p>' + val[0] + '</p>';
            });
            $(".loading").hide();
            if (html == '') {
                html = 'Something went wrong!';
            }
            swal({html: true, title: "Error!", text: html, icon: "error", type: "error"});
        }
    });
}

function ShowDataPostAjaxCall(_url, _method, data) {
    $(".loading").show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: _url,
        data: data,
        type: _method,
        success: function(response) {
            if (response) {
                $(".modal,.fade.show").remove();
//                $('body').after(response);
                $(response).appendTo("body")
                $('body').removeAttr('style').css({"--kt-toolbar-height": "55px", "--kt-toolbar-height-tablet-and-mobile": "55px"});
                $(".loading").hide();
            }
        },
        error: function(data) {
            var response = data.responseJSON.errors;
            var html = '';
            $.each(response, function(i, val) {
                html += '<p>' + val[0] + '</p>';
            });
            $(".loading").hide();
            if (html == '') {
                html = 'Something went wrong!';
            }
            swal({html: true, title: "Error!", text: html, icon: "error", type: "error"});
        }
    });
}

function DeleteAjaxCall(_url) {
    $(".loading").show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: _url,
        type: 'DELETE',
        success: function(response) {
            if (response) {
                $(".loading").hide();
//                $('.data-table').DataTable().ajax.reload();
                $.each(window.LaravelDataTables, function(key, value) {
                    window.LaravelDataTables[key].ajax.reload()
                });

                if (response.success === true) {
                    swal({
                        title: "Status!",
                        text: response.message,
                        type: "success"
                    });
                }
                else {
                    swal({
                        title: "Error!",
                        text: response.message,
                        type: "error"
                    });
                }

                $('body').removeAttr('style').css({"--kt-toolbar-height": "55px", "--kt-toolbar-height-tablet-and-mobile": "55px"});
            }
            $(".loading").hide();
        },
        error: function(data) {
            var response = data.responseJSON.errors;
            var html = '';
            $.each(response, function(i, val) {
                html += '<p>' + val[0] + '</p>';
            });
            $(".loading").hide();
            if (html == '') {
                html = 'Something went wrong!';
            }
            swal({html: true, title: "Error!", text: html, icon: "error", type: "error"});
        }
    });
}

//$.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
//    swal({html: true, title: "Error!", text: message, icon: "error", type: "error"});
//};
$.fn.dataTable.ext.errMode = function(settings, helpPage, message) {
    swal({html: true, title: "Error!", text: message, icon: "error", type: "error"});
};
$.extend(true, $.fn.dataTable.defaults, {
    "language": {
        "processing": "<span class='fa-stack fa-lg'>\n\<i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\</span>&emsp;Processing ...",
    }
});
$(document).ajaxComplete(function() {
    $(document).find('[data-bs-toggle="tooltip"]').tooltip();
});