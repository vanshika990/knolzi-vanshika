<x-layout-front-base>
    @section('meta_title', 'Wishlist')
    @section('meta_description', 'knolzi is The one-stop destination for learning and teaching online. Start learning today and grow. Make your Future brighter with us.')
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Wishlist</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <!-- wishlist page content start -->
    <section class="wishlist-course mt-5 mb-5">
        <div class="container">
            <div class="row">
                @if($data->isEmpty())
                <div class="col-md-12">
                    <!-- no course - no details found section start -->
                    <div class="no-found">
                        <div class="container">
                            <div class="no-found-text">
                                <a href="{{ url('/')}}" class="btn btn-primary">Browse Courses?</a>
                            </div>
                        </div>
                    </div>
                    <!-- no course - no details found section end -->
                </div>
                @else
                @foreach($data as $row)
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="course-block">
                        <div class="course-image">
                            <a href="{{ route('coursedetails', $row->slug) }}">
                                <img src="{{ $row->course_image }}" alt="{{ $row->course_name }}" class="img-fluid">
                            </a>
                        </div>
                        <div class="wishlist-hover">
                            <a href="javascript:void(0)" id="{{ $row->course_id }}" class="btn remove-to-wishlist remove-page-to-wishlist"><i class="icon-favourite"></i></a>
                        </div>
                        <div class="course-title"><a href="{{ route('coursedetails', $row->slug) }}">{{ $row->course_name }}</a></div>
                        <small>{{ $row->author_name }}</small>
                        <div class="course-rating">
                            @php
                            $rate =0;
                            if($row->rate != 0){
                            $rate = $row->rate / $row->total_record;
                            }
                            @endphp
                            <strong>{{ number_format((float)$rate, 1, '.', '') }}</strong>
                            <div class="star-rating">
                                <span class="star-rating__fill" style="width:  {{ $rate * 20 }}%">
                                </span>
                            </div>
                        </div>
                        <div class="course-price">
                            <i class="fas fa-rupee-sign"></i>
                            <span>{{ $row->course_price }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
                <div class="container d-flex justify-content-center mt-5">
                    {!! $data->links() !!}
                </div>
            </div>
        </div>
    </section>
    <!-- wishlist page content end -->

    @section('script')
    <script type="text/javascript">
        $(document).on("click", ".remove-page-to-wishlist", function() {
            var course_id = $(this).attr("id");
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
                        var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="' + id + '" class="toast hide align-items-center text-white bg-primary border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + response.message + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                        $('body').append(toast);
                        new bootstrap.Toast(document.querySelector('#' + id)).show();
                        $(".cartdetails").html(response.html);
                        window.location.reload();
                        return false;
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
                    var id = makeid(10);
                    var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 11"> <div id="' + id + '" class="toast hide align-items-center text-white bg-primary border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + html + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                    $('body').append(toast);
                    new bootstrap.Toast(document.querySelector('#' + id)).show();
                    window.location.reload();
                    return false;
                }
            });
        });
    </script>
    @endsection
    @stop
</x-layout-front-base>
