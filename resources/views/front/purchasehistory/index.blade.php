<x-layout-front-base>
    @section('meta_title', 'Purchase History')
    @section('meta_description', 'Purchase History')
    @section('meta_image',asset('assets/front/images/logo.png'))
    @section('content')
    @section('content')
    <!-- static page header start -->
    <section class="static-page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1>Purchase History</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- static page header end -->
    <section class="shop-cart-sec mb-5 mt-5">
        <div class="container">
            <div class="row">
                @if($payment_data->isEmpty())
                <!-- no course - no details found section start -->
                <div class="no-found">
                    <div class="container">
                        <div class="no-found-text">
                            <p>You don't have any purchase history</p>
                        </div>
                    </div>
                </div>
                <!-- no course - no details found section end -->
                @else
                <table class="table" id="purchasehistory">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Order Id</th>
                            <th>Date</th>
                            <th>Total Price</th>
                            <th>Payment Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payment_data as $payment)
                        @php 
                        @endphp
                        @if(isset($payment['subscription'][0]))
                        <tr style="border-bottom:1px solid lightgray">
                            @if($payment['subscription']->count() > 1)
                            <td> <div>{{ $payment['subscription']->count() }} courses purchased</div> <small> <a href="#" class="toggler" data-id="hideshow{{$payment['id']}}">View all courses <i class="bi bi-chevron-down"></i> </a> </small> </td>
                            @else                                
                            <td>{{ $payment['subscription'][0]['course']['course_name'] }}</td>
                            @endif
                            <td>{{ $payment['order_id'] }}</td>
                            <td>{{ $payment['created_at'] }}</td>
                            <td>{{ $payment['amount_to_be_paid'] }}</td>
                            <td>{{ $payment['payment_mode'] }}</td>
                        </tr>
                        @if($payment['subscription']->count() > 1)
                        @foreach($payment['subscription'] as $course)
                        <tr class="table-primary hideshow{{$payment['id']}}" style="border-bottom:1px solid lightgray; display:none;">
                            <td>{{ $course['course']['course_name'] }}</td>
                            <td></td>
                            <td></td>
                            <td>{{ $course['amount_to_be_paid'] }}</td>
                            <td></td>
                        </tr>
                        @endforeach
                        @endif
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$payment_data->links("pagination::bootstrap-4")}}
            @endif
        </div>
    </section>

    @section('script')
    <x-additional-js-css/>
    <script type="text/javascript">

        $(document).ready(function() {
            $(".toggler").click(function(e) {
                e.preventDefault();
                $('.' + $(this).data('id')).toggle();
                $("i", this).toggleClass("bi bi-chevron-up bi bi-chevron-down");
            });
        });

    </script>
    @endsection
    @stop
</x-layout-front-base>