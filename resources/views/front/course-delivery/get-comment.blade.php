@if(!empty($course_comment))
<ul>
    @foreach($course_comment as $key => $comment)
    <li>{{ $comment['comment'] }}</li>
    @endforeach
</ul>
@else
<p>You have not any comments for this course</p>
@endif
<div class="add-new-q-a">
    <button type="button" class="btn btn-success" data-bs-toggle="modal" href="#CourseAddNewCommentModal" role="button">Add New Comment</button>
</div>
