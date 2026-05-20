$(document).ready(function() {
    $('body').on('click', '.st-showmore', function(e) {
        $('.more').slideToggle();
        $(this).find('a').text("Show Less");
        $(this).removeClass('st-showmore').addClass('st-showless');
    });
    $('body').on('click', '.st-showless', function(e) {
        $('.more').slideToggle();
        $(this).find('a').text("Show More");
        $(this).removeClass('st-showless').addClass('st-showmore');
    });

    // Description
    $('.showless').click(function() {
        $('.showmore').show();
        $('.showless').hide();
        $('.description-para').css({"max-height": "300px", "overflow": "hidden"});
    });
    $('.showmore').click(function() {
        $('.showless').show();
        $('.showmore').hide();
        $('.description-para').css({"max-height": "none", "overflow": "visible"});
    });
    $('[data-toggle=search-form]').click(function() {
        $('.search-form-wrapper').toggleClass('open');
        $('.search-form-wrapper .search').focus();
        $('html').toggleClass('search-form-open');
    });
    $('[data-toggle=search-form-close]').click(function() {
        $('.search-form-wrapper').removeClass('open');
        $('html').removeClass('search-form-open');
    });
    $('.search-form-wrapper .search').keypress(function(event) {
        if ($(this).val() == "Search")
            $(this).val("");
    });

    $('.search-close').click(function(event) {
        $('.search-form-wrapper').removeClass('open');
        $('html').removeClass('search-form-open');
    });

    $(function() {
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
    });


    $('.board-course-carousel').owlCarousel({
        loop: true,
        margin: 20,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: false,
                dots: true
            },
            600: {
                items: 3,
                nav: false,
                dots: true
            },
            1000: {
                items: 4,
                nav: true,
                dots: false,
                loop: false
            },
            1920: {
                items: 5,
                nav: true,
                dots: false,
                loop: false
            }
        }
    })
    $('.course-carousel').owlCarousel({
        loop: true,
        margin: 20,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: false,
                dots: true
            },
            600: {
                items: 3,
                nav: false,
                dots: true
            },
            1000: {
                items: 4,
                nav: true,
                dots: false,
                loop: false
            },
            1920: {
                items: 5,
                nav: true,
                dots: false,
                loop: false
            }
        }
    });
    $('.stud-alsiview-course').owlCarousel({
        loop: true,
        margin: 20,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: false,
                dots: true
            },
            600: {
                items: 3,
                nav: false,
                dots: true
            },
            1000: {
                items: 4,
                nav: true,
                dots: false,
                loop: false
            },
            1920: {
                items: 5,
                nav: true,
                dots: false,
                loop: false
            }
        }
    });

    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $('[data-bs-toggle="popover"]').popover('disable');
    }

    /*course block popover*/
    $('[data-bs-toggle="popover"]').popover({
        trigger: 'manual',
        placement: 'auto',
    }).on("mouseenter", function() {
        var _this = this;
        $(this).popover("show");
        $(".popover").on("mouseleave", function() {
            $(_this).popover('hide');
        });
    }).on("mouseleave", function() {
        var _this = this;
        setTimeout(function() {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide")
            }
        }, 100);
    });
    $(document).on("click", ".add-to-cart", function() {
        $(this).text("Loading..");
        var course_id = $(this).attr("id");
        var content = $(document).find('.course-' + course_id).attr('data-bs-content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/shopping-carts/me/cart',
            data: {'course_id': course_id},
            type: 'POST',
            success: function(response) {
                var html = "";
                if (typeof content !== "undefined") {
                    content = content.replace('<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>', '<a href="/cart" class="btn btn-primary goto-cart-' + course_id + '">Go to cart</a>');
                    var content2 = content.replace('<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning  remove-to-wishlist add "><i class="icon-favourite"></i></a>', '<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning  add-to-wishlist "><i class="icon-favourite"></i></a>');
                    html = content2.replace('<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning remove-to-wishlist add "><i class="icon-favourite"></i></a>', '<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning  add-to-wishlist "><i class="icon-favourite"></i></a>');
                }
                $(document).find('.course-' + course_id).attr('data-bs-content', html);
                $(document).find('.add-to-cart').attr("href", '/cart').text('Go to cart').removeClass("add-to-cart");
                $(document).find('.cart-wish-' + course_id + ' a:last-child').removeClass("add-to-wishlist").addClass("remove-to-wishlist add");
                $(document).find('.cart-wish-' + course_id + ' a:last-child').removeClass("remove-to-wishlist add").addClass("add-to-wishlist");
                $(this).attr("href", '/cart').text('Go to cart').removeClass("add-to-cart");
                $(".cartdetails").html(response.html);
                $('.cart_count').html(response.cart_count + '<span class="visually-hidden">unread </span>').show();

            },
            error: function(data) {
                $(document).find('.add-to-cart').text('Add to cart');
                var response = data.responseJSON.errors;
                var html = '';
                $.each(response, function(i, val) {
                    html += '<p>' + val[0] + '</p>';
                });
                $(".loading").hide();
                if (html == '') {
                    html = 'Something went wrong!';
                }
                swal({
                    title: "Error!",
                    text: html,
                    html: true,
                    type: "error"
                },
                function() {
                    location.reload();
                });
                return false;
            }
        });
    });
    $(document).on("click", ".add-all-to-cart", function() {
        $(this).text("Loading..");
        var course_id = $(this).attr("id");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/shopping-carts/me/cart',
            data: {'course_id': course_id},
            type: 'POST',
            success: function(response) {
                $(document).find('.add-all-to-cart').attr("href", '/cart').text('Go to cart').removeClass("add-all-to-cart");
                $(this).attr("href", '/cart').text('Go to cart').removeClass("add-to-cart");
                $(".cartdetails").html(response.html);
                $('.cart_count').html(response.cart_count + '<span class="visually-hidden">unread </span>').show();
            },
            error: function(data) {
                $(document).find('.add-all-to-cart').text('Add to cart');
                var response = data.responseJSON.errors;
                var html = '';
                $.each(response, function(i, val) {
                    html += '<p>' + val[0] + '</p>';
                });
                $(".loading").hide();
                if (html == '') {
                    html = 'Something went wrong!';
                }
                swal({
                    title: "Error!",
                    text: html,
                    html: true,
                    type: "error"
                },
                function() {
                    location.reload();
                });
                /*var id = makeid(10);
                 var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="' + id + '" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + html + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                 $('body').append(toast);
                 new bootstrap.Toast(document.querySelector('#' + id)).show();
                 window.location.reload();*/
                return false;
            }
        });
    });

    $(document).on("click", ".add-to-wishlist", function() {
        var course_id = $(this).attr("id");
        var content = $(document).find('.course-' + course_id).attr('data-bs-content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/shopping-carts/me/wishlist',
            data: {'course_id': course_id},
            type: 'POST',
            success: function(response) {
                if (response.login == false) {
                    window.location.href = response.url;
                    return false;
                } else {
                    var id = makeid(10);
                    var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="' + id + '" class="toast hide align-items-center text-white bg-success border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + response.message + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                    $('body').append(toast);
                    new bootstrap.Toast(document.querySelector('#' + id)).show();
                    $(document).find('.cart-wish-' + course_id + ' a:first-child').attr("href", 'javascript:void(0)').html('<i class="icon-cart"></i> Add to cart').addClass("add-to-cart").attr("id", course_id);
                    $(document).find('.cart-wish-' + course_id + ' a:last-child').removeClass("add-to-wishlist").addClass("remove-to-wishlist add");
                    if (typeof content !== "undefined") {
                        var contents = content.replace('<a href="/cart" class="btn btn-primary goto-cart-' + course_id + '">Go to cart</a>', '<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>')
                        var final_contents = contents.replace('<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning  add-to-wishlist "><i class="icon-favourite"></i></a>', '<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning remove-to-wishlist add "><i class="icon-favourite"></i></a>')
                        $(document).find('.course-' + course_id).attr('data-bs-content', final_contents);
                    }
                    $(".cartdetails").html(response.html.html);
                    if (response.html.count == 0) {
                        $(".cart_count").html('<span class="visually-hidden">unread </span>').hide();
                    } else {
                        $(".cart_count").html(response.html.count + '<span class="visually-hidden">unread </span>');
                    }

                }
            },
            error: function(data) {
                $(document).find('.add-to-cart').text('Add to cart');
                var response = data.responseJSON.errors;
                var html = '';
                $.each(response, function(i, val) {
                    html += '<p>' + val[0] + '</p>';
                });
                $(".loading").hide();
                if (html == '') {
                    html = 'Something went wrong!';
                }
                swal({
                    title: "Error!",
                    text: html,
                    html: true,
                    type: "error"
                },
                function() {
                    location.reload();
                });
                /*var id = makeid(10);
                 var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="' + id + '" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + html + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                 $('body').append(toast);
                 new bootstrap.Toast(document.querySelector('#' + id)).show();
                 window.location.reload();*/
                return false;
            }
        });
    });
    $(document).on("click", ".remove-to-wishlist", function() {
        var course_id = $(this).attr("id");
        var content = $(document).find('.course-' + course_id).attr('data-bs-content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/user/shopping-carts/me/remove-wishlist',
            data: {'course_id': course_id},
            type: 'POST',
            success: function(response) {
                if (response.login == false) {
                    window.location.href = response.url;
                    return false;
                } else {
                    var id = makeid(10);
                    var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="' + id + '" class="toast hide align-items-center text-white bg-success border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + response.message + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                    $('body').append(toast);
                    new bootstrap.Toast(document.querySelector('#' + id)).show();
                    $(document).find('.cart-wish-' + course_id + ' a:first-child').attr("href", 'javascript:void(0)').html('<i class="icon-cart"></i> Add to cart').addClass("add-to-cart").attr("id", course_id);
                    $(document).find('.cart-wish-' + course_id + ' a:last-child').removeClass("remove-to-wishlist add").addClass("add-to-wishlist");
                    if (typeof content !== "undefined") {
                        var contents = content.replace('<a href="/cart" class="btn btn-primary goto-cart-' + course_id + '">Go to cart</a>', '<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-dblue add-to-cart"><i class="icon-cart"></i> Add to cart</a>')
                        var contents2 = contents.replace('<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning  remove-to-wishlist add "><i class="icon-favourite"></i></a>', '<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning  add-to-wishlist "><i class="icon-favourite"></i></a>')
                        var final_contents = contents2.replace('<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning remove-to-wishlist add "><i class="icon-favourite"></i></a>', '<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-warning  add-to-wishlist "><i class="icon-favourite"></i></a>')
                        $(document).find('.course-' + course_id).attr('data-bs-content', final_contents);
                    }
                    $(".cartdetails").html(response.html);
                }
            },
            error: function(data) {
                $(document).find('.add-to-cart').text('Add to cart');
                var response = data.responseJSON.errors;
                var html = '';
                $.each(response, function(i, val) {
                    html += '<p>' + val[0] + '</p>';
                });
                $(".loading").hide();
                if (html == '') {
                    html = 'Something went wrong!';
                }
                swal({
                    title: "Error!",
                    text: html,
                    html: true,
                    type: "error"
                },
                function() {
                    location.reload();
                });
                /*var id = makeid(10);
                 var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="' + id + '" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + html + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                 $('body').append(toast);
                 new bootstrap.Toast(document.querySelector('#' + id)).show();
                 window.location.reload();*/
                return false;
            }
        });
    });

    $(document).on("click", ".show-more-review", function() {
        var page_item = $(document).find('.show-more-review').attr('data-page');
        $(this).attr('data-page', parseInt(page_item) + 1);
        $('.show-more-review').find('button').html("Loading..");
        $.ajax({
            url: '?page=' + page_item,
            type: "get",
        }).done(function(data)
        {
            if (data.finish == 1) {
                $('.show-more-review').hide();
            }
            if (data.html == " ") {
                $('.show-more-review').find('button').html("No more records found");
                return;
            }
            $(document).find('.show-more-review').find('button').html("Show more");
            $(".student-review").append(data.html);
        }).fail(function(jqXHR, ajaxOptions, thrownError)
        {
            alert('server not responding...');
        });
    });
});


/*mobile sidebar accordian script*/
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.sidebar .nav-link').forEach(function(element) {
        element.addEventListener('click', function(e) {
            var nextEl = element.nextElementSibling;
            var parentEl = element.parentElement;
            if (nextEl) {
                e.preventDefault();
                var mycollapse = new bootstrap.Collapse(nextEl);
                if (nextEl.classList.contains('show')) {
                    mycollapse.hide();
                } else {
                    mycollapse.show();
                    // find other submenus with class=show
                    var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                    // if it exists, then close all of them
                    if (opened_submenu) {
                        new bootstrap.Collapse(opened_submenu);
                    }
                }
            }
        }); // addEventListener
    }) // forEach
});
// DOMContentLoaded  end

function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
    }
    return  'toast-' + result;
}
