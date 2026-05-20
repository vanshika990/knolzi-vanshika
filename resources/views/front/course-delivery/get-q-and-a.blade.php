@if(!empty($course_qa))
@foreach($course_qa as $key => $qa)
<div class="accordion-item">
    <h2 class="accordion-header" id="heading{{ md5($key) }}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ md5($key) }}" aria-expanded="false" aria-controls="collapse{{ md5($key) }}">
            {{ $qa['question_name'] }}
        </button>
    </h2>
    <div id="collapse{{ md5($key) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ md5($key) }}" data-bs-parent="#accordionQA">
        <div class="accordion-body">
            {{ $qa['answer'] }}
        </div>
    </div>
</div>
@endforeach
@else
<p>No any Q & A for this course</p>
@endif
<div class="add-new-q-a">
    <button type="button" class="btn btn-success" data-bs-toggle="modal" href="#accordionQAModal" role="button">Add New Q & A</button>
</div>
