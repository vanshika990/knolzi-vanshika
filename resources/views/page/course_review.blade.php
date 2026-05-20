@if(!$course_review->isEmpty())
@foreach($course_review as $review)
<div class="review-block">
    <div class="user-icon">
        <a href="javascript:void(0)">
            <img src="{{ asset('assets/front/images/user-img.png') }}" alt="{{ $review->user->name }}" width="50" height="50" class="img-fluid">
            <span>{{ $review->user->name }}</span>
        </a>
    </div>
    <p>{{ $review['review'] }}</p>
</div> 
@endforeach  
@endif