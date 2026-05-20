<div class="modal fade" id="kt_modal_view_quesans" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-xxl-75">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Course Questions</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
                                <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1" />
                                <rect fill="#000000" opacity="0.5" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)" x="0" y="7" width="16" height="2" rx="1" />
                            </g>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <div class="card mb-xl-8">
                    <div class="card-body p-3">
                        <div class="peragraph_ex">
                            @if( isset($coursequestion) && !empty($coursequestion) )
                                @for($i=0;$i<count($coursequestion);$i++)
                                    <p><strong>{{ $coursequestion[$i]->question_name }}</strong></p>
                                    @for($j=0;$j<count($coursequestion[$i]->questionanswer);$j++)
                                        <div class="checkbox checkbox-primary checkbox-circle">
                                            <input id="{{ $coursequestion[$i]->questionanswer[$j]->answer_name }}" type="checkbox" {{ $coursequestion[$i]->correct_question_ans == $j+1 ? 'checked' : 'disabled' }}>
                                            <label for="{{ $coursequestion[$i]->questionanswer[$j]->answer_name }}">{!! $coursequestion[$i]->questionanswer[$j]->answer_name !!}</label>
                                        </div>
                                    @endfor
                                @endfor
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $('#kt_modal_view_quesans').modal('show');
    $('#kt_modal_view_quesans').on('hidden.bs.modal', function() {
        $(".modal").remove();
        $('.modal-backdrop').remove();
    });
</script>

</div>
