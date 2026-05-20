@extends('frontend.layouts.app')

@section('meta_title', 'Wishlist')
@section('meta_description', 'knolzi is The one-stop destination for learning and teaching online. Start learning today and grow. Make your Future brighter with us.')
@section('meta_image', asset('assets/front/images/logo.png'))

@section('content')
<!-- Wishlist Header -->
<section class="w-full bg-gradient-primary py-12 mb-8 relative">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-4xl md:text-5xl font-bold text-center text-white drop-shadow-lg">Wishlist</h1>
    </div>
</section>

<!-- Wishlist Content -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-8">
    @if($data->isEmpty())
        <div class="flex flex-col items-center justify-center py-24">
            <div class="glass-effect rounded-2xl p-8 text-center">
                <h2 class="text-2xl font-semibold mb-4 text-secondary">No courses in your wishlist yet!</h2>
                <a href="{{ url('/') }}" class="btn-primary px-8 py-3 rounded-full font-semibold">Browse Courses</a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($data as $row)
            <div class="bg-bg-primary/10 backdrop-blur-md rounded-2xl p-5 flex flex-col shadow-lg hover:scale-[1.03] transition-transform duration-200 relative min-h-[420px]">
                <div class="relative flex-shrink-0">
                    <a href="{{ route('coursedetails', $row->slug) }}">
                        <img src="{{ $row->course_image }}" alt="{{ $row->course_name }}" class="w-full h-40 object-contain bg-bg-primary rounded-xl mb-4 border border-border shadow-sm">
                    </a>
                    <button type="button" id="{{ $row->course_id }}" class="absolute top-3 right-3 bg-bg-primary hover:bg-error/10 text-error rounded-full p-2 shadow remove-to-wishlist remove-page-to-wishlist transition-colors flex items-center justify-center" title="Remove from wishlist">
                        <!-- Heart Icon for Remove from Wishlist -->
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 flex flex-col justify-between mt-2 px-3 pb-3 pt-2">
                    <div>
                        <h3 class="text-lg font-bold mb-2 leading-tight min-h-[48px] text-text-primary"><a href="{{ route('coursedetails', $row->slug) }}">{{ $row->course_name }}</a></h3>
                        <p class="text-xs text-secondary mb-3">By: {{ $row->author_name }}</p>
                        <div class="flex items-center mb-3">
                            @php
                                $rate = 0;
                                if($row->rate != 0){
                                    $rate = $row->rate / $row->total_record;
                                }
                                $fullStars = floor($rate);
                                $halfStar = ($rate - $fullStars) >= 0.5 ? 1 : 0;
                                $emptyStars = 5 - $fullStars - $halfStar;
                            @endphp
                            <span class="text-yellow-400 font-bold mr-2">{{ number_format((float)$rate, 1, '.', '') }}</span>
                            <div class="flex items-center">
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <!-- Full Star -->
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.049 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                @endfor
                                @if ($halfStar)
                                    <!-- Half Star -->
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.049 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <!-- Empty Star -->
                                    <svg class="w-4 h-4 text-text-light" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.049 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xl font-bold text-text-primary">{{ getCurrencySymbol() . currencyConvert($row->course_price) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex justify-center mt-10">
            {!! $data->links() !!}
        </div>
    @endif
</section>
@endsection

@push('scripts')
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
                    var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 1111"> <div id="' + id + '" class="toast hide align-items-center text-white bg-primary border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + response.message + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
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
                var toast = '<div class="position-fixed top-0 end-0 p-3" style="z-index: 1111"> <div id="' + id + '" class="toast hide align-items-center text-white bg-danger border-0" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true"> <div class="d-flex"> <div class="toast-body">' + html + '</div><button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> </div></div></div>';
                $('body').append(toast);
                new bootstrap.Toast(document.querySelector('#' + id)).show();
                window.location.reload();
                return false;
            }
        });
    });
</script>
@endpush
