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
				dots:true
            },
            600: {
                items: 3,
                nav: false,
				dots:true
            },
            1000: {
                items: 4,
                nav: true,
				dots:false,
                loop: false
            },
            1920: {
                items: 5,
                nav: true,
				dots:false,
                loop: false
            }
        }
    })
    $('.stud-alsiview-course').owlCarousel({
        loop: true,
        margin: 20,
        responsiveClass: true,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 1,
                nav: false,
				dots:true
            },
            600: {
                items: 3,
                nav: false,
				dots:true
            },
            1000: {
                items: 4,
                nav: true,
				dots:false,
                loop: false
            },
            1920: {
                items: 5,
                nav: true,
				dots:false,
                loop: false
            }
        }
    });
	$('.course-carousel').owlCarousel({
        loop:true,
        margin:20,
        responsiveClass:true,
        nav:false,
        dots:false,
        responsive:{
            0: {
                items: 1,
                nav: false,
				dots:true
            },
            600: {
                items: 3,
                nav: false,
				dots:true
            },
            1000: {
                items: 4,
                nav: true,
				dots:false,
                loop: false
            },
            1920: {
                items: 5,
                nav: true,
				dots:false,
                loop: false
            }
        }
    });

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
            url: '/shopping-carts/me/cart/',
            data: {'course_id': course_id},
            type: 'POST',
            success: function(response) {
                if (typeof content !== "undefined") {
                    var html = content.replace('<a href="javascript:void(0)" id="' + course_id + '" class="btn btn-primary add-to-cart"><i class="icon-cart"></i> Add to Cart</a>', '<a href="/cart" class="btn btn-primary">Go to Cart</a>');
                }
                $(document).find('.course-' + course_id).attr('data-bs-content', html);
                $(document).find('.add-to-cart').attr("href", '/cart').text('Go to Cart').removeClass("add-to-cart");
                $(this).attr("href", '/cart').text('Go to Cart').removeClass("add-to-cart");
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

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

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