@extends('frontend.layouts.app')

@section('meta_title', 'Purchase History')
@section('meta_description', 'Purchase History')
@section('meta_image', asset('assets/front/images/logo.png'))

@section('content')
<!-- Page Header -->
<section class="relative z-10 max-w-7xl mx-auto px-6 py-12">
    <div class="text-center mb-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-2 leading-tight gradient-text">Purchase History</h1>
        <p class="text-lg text-secondary">View all your course purchases and payment details</p>
    </div>
</section>

<section class="relative z-10 max-w-7xl mx-auto px-6 py-8">
    <div class="glass-effect-subtle rounded-3xl p-8 shadow-2xl bg-bg-primary border border-border">
        @if($payment_data->isEmpty())
            <div class="flex flex-col items-center justify-center py-16">
                <svg class="w-16 h-16 text-text-light mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 018 0v2m-4-4a4 4 0 100-8 4 4 0 000 8zm0 0v2m0 4h.01" />
                </svg>
                <p class="text-xl text-secondary font-semibold">You don't have any purchase history</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse rounded-xl overflow-hidden shadow-lg">
                    <thead class="bg-gradient-primary">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-bold text-text-white uppercase tracking-wider border-b border-primary-dark">Title</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-text-white uppercase tracking-wider border-b border-primary-dark">Order Id</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-text-white uppercase tracking-wider border-b border-primary-dark">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-text-white uppercase tracking-wider border-b border-primary-dark">Total Price</th>
                            <th class="px-4 py-3 text-left text-sm font-bold text-text-white uppercase tracking-wider border-b border-primary-dark">Payment Type</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($payment_data as $payment)
                            @if(isset($payment['subscription'][0]))
                                <tr class="border-b border-border bg-bg-light hover:bg-primary/10 transition-colors duration-200">
                                    @if($payment['subscription']->count() > 1)
                                        <td class="px-4 py-3 text-text-primary">
                                            <div>{{ $payment['subscription']->count() }} courses purchased</div>
                                            <small>
                                                <a href="#" class="toggler text-primary hover:underline" data-id="hideshow{{$payment['id']}}">
                                                    View all courses <i class="bi bi-chevron-down"></i>
                                                </a>
                                            </small>
                                        </td>
                                    @else
                                        <td class="px-4 py-3 text-text-primary">{{ $payment['subscription'][0]['course']['course_name'] }}</td>
                                    @endif
                                                    <td class="px-4 py-3 text-secondary">{{ $payment['order_id'] }}</td>
                <td class="px-4 py-3 text-secondary">{{ $payment['created_at'] }}</td>
                                    <td class="px-4 py-3 text-success font-semibold">{{ $payment['amount_to_be_paid'] }}</td>
                                    <td class="px-4 py-3 text-primary">{{ $payment['payment_mode'] }}</td>
                                </tr>
                                @if($payment['subscription']->count() > 1)
                                    @foreach($payment['subscription'] as $course)
                                        <tr class="bg-bg-secondary hideshow{{$payment['id']}} border-b border-border hover:bg-primary/5 transition-colors duration-200" style="display:none;">
                                            <td class="px-4 py-3 text-text-primary">{{ $course['course']['course_name'] }}</td>
                                            <td class="px-4 py-3"></td>
                                            <td class="px-4 py-3"></td>
                                            <td class="px-4 py-3 text-success font-semibold">{{ $course['amount_to_be_paid'] }}</td>
                                            <td class="px-4 py-3"></td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{$payment_data->links("pagination::bootstrap-4")}}
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $(".toggler").click(function(e) {
            e.preventDefault();
            $('.' + $(this).data('id')).toggle();
            $("i", this).toggleClass("bi bi-chevron-up bi bi-chevron-down");
        });
    });
</script>
@endpush

