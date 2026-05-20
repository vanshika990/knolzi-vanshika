<div class="col-lg-12 pe-lg-0 pe-3 ps-3">
    <div class="left-side-del-panel">
        <div class="media-delivery-content">
            <form class="row g-3" action="#" name="submit_review_rate_complete_course" id="submit_review_rate_complete_course" method="POST">
                <div class="question-answer">
                    <div class="question-mcq">
                        How would you rate this course?
                    </div>
                    <div class="rate-this-course"></div><br>
                    <div class="question-mcq">
                        How would you rate the author?
                    </div>
                    <div class="rate-this-author"></div><br>
                    <div class="question-mcq">
                        Rating for new skills learned.
                    </div>
                    <div class="rate-this-skill"></div><br>
                    <div class="question-mcq">
                        How was your overall experience?
                    </div>
                    <div class="rate-overall"></div><br>
                    <div class="question-mcq">
                        Will you return for accessing more courses on knolzi?
                    </div>
                    <div class="rate-this-accessing"></div><br>
                    <div class="question-mcq">
                        Will you recommend this course & knolzi to your friends and colleagues?
                    </div>
                    <div class="rate-this-recommend"></div><br>
                    <div class="mb-3">
                        <input type="hidden" name="course_id" value="{{ $course_id }}"/>
                        <input type="hidden" name="attempt_id" value="{{ $attempt_id }}"/>
                        <input type="hidden" name="course_rate" class="course_rate" value=""/>
                        <input type="hidden" name="author_rate" class="author_rate" value=""/>
                        <input type="hidden" name="new_skill_rate" class="new_skill_rate" value=""/>
                        <input type="hidden" name="overall_rate" class="overall_rate" value=""/>
                        <input type="hidden" name="accessing_rate" class="accessing_rate" value=""/>
                        <input type="hidden" name="recommend_rate" class="recommend_rate" value=""/>
                        <button type="submit" class="btn btn-primary mb-3">Submit</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<!--------------------------------All Modal ----------------------------------->
</div>
</div>
</section>
<div class="loading" style="display:none">Loading&#8230;</div>
<script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/front/js/jquery.star-rating-svg.js') }}"></script>
<script>
$(".would-you-rate-this-course").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".user_rate").val(currentRating);
        $(".review").show();
    }
});
$(".my-rating").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".user_rate").val(currentRating);
        $(".review").show();
    }
});
/****************Rate / Review **********/
$("#submitrate").validate({
    rules: {
        review: "required",
        rate: "required"
    }, submitHandler: function(form) {
        $('.loading').show();
        var data = new FormData(form);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('submit-rate-review') }}",
            type: 'POST',
            contentType: false,
            data: data,
            processData: false,
            cache: false,
            success: function(response) {
                $('.loading').hide();
                swal({title: "Status!", text: response.message, type: "success"});
                $("#rate-review").html(response.html);
                $('#RateThisCourseModal').modal('hide');
                $(".modal-backdrop").remove();
                form.reset();
            }
        }).fail(function(xhr, textStatus, errorThrown) {
            $('.loading').hide();
            var errors = "";
            if (xhr.status == 422) {
                if (xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(i, val) {
                        errors += "<b><p style='color:red'>" + val[0] + "</p></b><br/>";
                    });
                    if (errors !== "") {
                        swal({title: "Error!", text: errors, type: "error", html: true});
                    }
                }
            } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                swal({title: "Error!", text: "Server error", type: "error", html: true});
                return false;
            } else {
                swal({title: "Error!", text: "No internet Connection. please check your internet connection.", type: "error", html: true});
                return false;
            }
        });
        return false;
    }
});

$(".rate-this-course").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".course_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-this-author").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".author_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-this-skill").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".new_skill_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-overall").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".overall_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-this-accessing").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".accessing_rate").val(currentRating);
        $(".review").show();
    }
});

$(".rate-this-recommend").starRating({
    initialRating: 0,
    strokeColor: '#894A00',
    strokeWidth: 10,
    starSize: 25,
    disableAfterRate: false,
    callback: function(currentRating, $el) {
        $(".recommend_rate").val(currentRating);
        $(".review").show();
    }
});


$("#submit_review_rate_complete_course").validate({
    rules: {
        review: "required",
        rate: "required"
    }, submitHandler: function(form) {
        $('.loading').show();
        var data = new FormData(form);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('submit-complete-question') }}",
            type: 'POST',
            contentType: false,
            data: data,
            processData: false,
            cache: false,
            success: function(response) {
                $('.loading').hide();
                swal({
                    title: "Status!",
                    text: response.message,
                    html: true,
                    type: "success"
                },
                function() {
                    window.location = "{{ route('homepage') }}";
                });
            }
        }).fail(function(xhr, textStatus, errorThrown) {
            $('.loading').hide();
            var errors = "";
            if (xhr.status == 422) {
                if (xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(i, val) {
                        errors += "<b><p style='color:red'>" + val[0] + "</p></b><br/>";
                    });
                    if (errors !== "") {
                        swal({title: "Error!", text: errors, type: "error", html: true});
                    }
                }
            } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                swal({title: "Error!", text: "Server error", type: "error", html: true});
                return false;
            } else {
                swal({title: "Error!", text: "No internet Connection. please check your internet connection.", type: "error", html: true});
                return false;
            }
        });
        return false;
    }
});
</script>
</body>
</html>
