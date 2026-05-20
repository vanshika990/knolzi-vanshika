@if(!empty($question))
<script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/front/js/jquery.star-rating-svg.js') }}"></script>
<script src="{{ asset('assets/front/js/owl.carousel.js') }}"></script>
<style>
    #carousel .item img{
        display: block;
        width: 100%;
        height: auto;
    }
</style>
<script>
$(document).ready(function() {
    $(".owl-carousel").owlCarousel({
        nav: true,
        singleItem: true,
        responsiveClass: true,
        autoHeight: true,
    });

    var usable_help = [];
    var videoTimer = null, interval = 1000, videoValue = 0;
    var audioTimer = null, audioValue = 0;
    var pdfTimer = null, pdfValue = 0;
    var imgTimer = null, imgValue = 0;
    var helpVideoTimer = null, helpVideoValue = 0;
    var helpAudioTimer = null, helpAudioValue = 0;
    var helpPdfTimer = null, helpPdfValue = 0;
    var helpImgTimer = null, helpImgValue = 0;

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

    $(".link_hint").click(function() {
        usable_help.push("link_hint");
    })
    $(".link_help").click(function() {
        usable_help.push("link_help");
    })
    $('#VideoHintModal').on('shown.bs.modal', function(e) {
        if (videoTimer !== null)
            return;
        videoTimer = setInterval(function() {
            $("#cntVideo").val(++videoValue);
        }, interval);

    });

    $("#VideoHintModal").on("hidden.bs.modal", function() {
        clearInterval(videoTimer);
        videoTimer = null;
    });
    $('#AudioHintModal').on('shown.bs.modal', function(e) {
        if (audioTimer !== null)
            return;
        audioTimer = setInterval(function() {
            $("#cntAudio").val(++audioValue);
        }, interval);
    });
    $("#AudioHintModal").on("hidden.bs.modal", function() {
        clearInterval(audioTimer);
        audioTimer = null;
    });
    $('#DocumentHintModal').on('shown.bs.modal', function(e) {
        if (pdfTimer !== null)
            return;
        pdfTimer = setInterval(function() {
            $("#cntPdf").val(++pdfValue);
        }, interval);
    });
    $("#DocumentHintModal").on("hidden.bs.modal", function() {
        clearInterval(pdfTimer);
        pdfTimer = null;
    });
    $('#ImageHintModal').on('shown.bs.modal', function(e) {
        if (imgTimer !== null)
            return;
        imgTimer = setInterval(function() {
            $("#cntImage").val(++imgValue);
        }, interval);
    });
    $("#ImageHintModal").on("hidden.bs.modal", function() {
        clearInterval(imgTimer);
        imgTimer = null;
    });
    $('#VideoHelpModal').on('shown.bs.modal', function(e) {
        if (helpVideoTimer !== null)
            return;
        helpVideoTimer = setInterval(function() {
            $("#helpVideo").val(++helpVideoValue);
        }, interval);

    });

    $("#VideoHelpModal").on("hidden.bs.modal", function() {
        clearInterval(helpVideoTimer);
        helpVideoTimer = null;
    });
    $('#AudioHelpModal').on('shown.bs.modal', function(e) {
        if (helpAudioTimer !== null)
            return;
        helpAudioTimer = setInterval(function() {
            $("#helpAudio").val(++helpAudioValue);
        }, interval);
    });
    $("#AudioHelpModal").on("hidden.bs.modal", function() {
        clearInterval(helpAudioTimer);
        helpAudioTimer = null;
    });
    $('#DocumentHelpModal').on('shown.bs.modal', function(e) {
        if (helpPdfTimer !== null)
            return;
        helpPdfTimer = setInterval(function() {
            $("#helpPdf").val(++helpPdfValue);
        }, interval);
    });
    $("#DocumentHelpModal").on("hidden.bs.modal", function() {
        clearInterval(helpPdfTimer);
        helpPdfTimer = null;
    });
    $('#ImageHelpModal').on('shown.bs.modal', function(e) {
        if (helpImgTimer !== null)
            return;
        helpImgTimer = setInterval(function() {
            $("#helpImage").val(++helpImgValue);
        }, interval);
    });
    $("#ImageHelpModal").on("hidden.bs.modal", function() {
        clearInterval(helpImgTimer);
        helpImgTimer = null;
    });

    $(".modal").on("hidden.bs.modal", function() {
        $('audio,video').each(function() {
            this.pause();
            this.currentTime = 0;
        });
    });
    $(".next-que").click(function() {
        $('.loading').show();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('review-next-question') }}",
            type: 'POST',
            data: {course_id: "{{ encrypt($question['course_id']) }}"},
            success: function(response) {
                $('.loading').hide();
                $(document).find("#completed-question").html(response.html);
                $(document).find(".your-progress-header").html(response.progress_html);
                countDownTime();
                usable_help = [];
                LoadProgress();
                if ($(".rate-this-course").length) {
                    // Change the class of the target div (e.g., 'myDiv')
                    $("#content_delivery_div").removeClass("fluid-container pe-5 ps-5").addClass("container");
                }
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

    });
    /****************Submit Question **********/
    $("#submitquestion").validate({
        errorPlacement: function(error, element) {
            if (element.attr("data-type") == "radio") {
                error.insertAfter("#ans");
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            answer: "required"
        }, submitHandler: function(form) {
            if (document.body.contains(document.getElementById('cntVideo'))) {
                var vTime = document.getElementById("cntVideo").value;
                if (vTime > 0) {
                    usable_help.push({"hint": "video_hint", "time": vTime});
                }
            }
            if (document.body.contains(document.getElementById('cntAudio'))) {
                var aTime = document.getElementById("cntAudio").value;
                if (aTime > 0) {
                    usable_help.push({"hint": "audio_hint", "time": aTime});
                }
            }
            if (document.body.contains(document.getElementById('cntPdf'))) {
                var pTime = document.getElementById("cntPdf").value;
                if (pTime > 0) {
                    usable_help.push({"hint": "pdf_hint", "time": pTime});
                }
            }
            if (document.body.contains(document.getElementById('cntImage'))) {
                var imgTime = document.getElementById("cntImage").value;
                if (imgTime > 0) {
                    usable_help.push({"hint": "image_hint", "time": imgTime});
                }
            }
            if (document.body.contains(document.getElementById('helpVideo'))) {
                var vidTime = document.getElementById("helpVideo").value;
                if (vidTime > 0) {
                    usable_help.push({"help": "video_help", "time": vidTime});
                }
            }
            if (document.body.contains(document.getElementById('helpAudio'))) {
                var audTime = document.getElementById("helpAudio").value;
                if (audTime > 0) {
                    usable_help.push({"help": "audio_help", "time": audTime});
                }
            }
            if (document.body.contains(document.getElementById('helpPdf'))) {
                var pdfTime = document.getElementById("helpPdf").value;
                if (pdfTime > 0) {
                    usable_help.push({"help": "pdf_help", "time": pdfTime});
                }
            }
            if (document.body.contains(document.getElementById('helpImage'))) {
                var imgTime = document.getElementById("helpImage").value;
                if (imgTime > 0) {
                    usable_help.push({"help": "image_help", "time": imgTime});
                }
            }

            $('.loading').show();
            var data = new FormData(form);
            data.append('choice', parseInt($('input[name=answer]:checked').attr("data-key")) + 1);
            data.append('usable_help', JSON.stringify(usable_help));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('review-submit-question-answer') }}",
                type: 'POST',
                contentType: false,
                data: data,
                processData: false,
                cache: false,
                success: function(response) {
                    $('.loading').hide();
                    if(response.is_complete == '1'){
                        swal({
                            title: "Status!",
                            text: response.message,
                            html: true,
                            type: "success"
                        },
                        function() {
                            window.location = "{{ route('getreviewercourse') }}";
                        });
                    } else {
                        swal({title: "Status!", text: response.message, type: response.status});
                        $(".next-que").removeAttr("disabled");
                        $(".submit-que").attr("disabled", "disabled");
                        form.reset();
                    }
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
});
</script>
<script>
    LoadProgress();
    function LoadProgress() {
        var pie = document.querySelectorAll('.pie{{ $rand_progress }}');
        var range = document.querySelector('[type="range"]');

        // start the animation when the element is in the page view
        var elements = [].slice.call(document.querySelectorAll('.pie{{ $rand_progress }}'));
        var circle = new CircularProgressBar('pie{{ $rand_progress }}');

        if ('IntersectionObserver' in window) {
          var config = {
            root: null,
            rootMargin: '0px',
            threshold: 0.75,
          };

          var ovserver = new IntersectionObserver((entries, observer) => {
            entries.map((entry) => {
              if (entry.isIntersecting && entry.intersectionRatio > 0.75) {
                circle.initial(entry.target);
                observer.unobserve(entry.target);
              }
            });
          }, config);

          elements.map((item) => {
            ovserver.observe(item);
          });
        } else {
          elements.map((element) => {
            circle.initial(element);
          });
        }
    }
</script>
@endif
