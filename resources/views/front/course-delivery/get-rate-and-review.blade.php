<div class="modal-header">
    <h5 class="modal-title" id="RateThisCourseModalToggleLabel">Your Review</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body" id="rate-review">
    <div class="course-rating">
        <div class="star-rating">
            <span class="star-rating__fill" style="width: {{ $course_review['rate'] * 20 }}%">
            </span>
        </div>
    </div>
    <p>{{ $course_review['review'] }}</p>
</div>
