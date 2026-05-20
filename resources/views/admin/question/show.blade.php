<div class="modal fade" id="kt_modal_view_question_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-md-50">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Question Detail</h2>
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
                <div class="peragraph_ex row">
                    <p><strong>{!! $questionData->question_name !!}</strong></p>
                    <div class="col-sm-12 mb-5">
                        @if( isset($questionData->questionanswer) && !empty($questionData->questionanswer) )
                        @for($i=0;$i<count($questionData->questionanswer);$i++)
                            <div class="form-check">
                                <input class="form-check-input" id="{{ $questionData->questionanswer[$i]->answer_name }}" type="checkbox" {{ $questionData->correct_question_ans == $i+1 ? 'checked' : 'disabled'}}>
                                    <label class="form-check-label" for="{{ $questionData->questionanswer[$i]->answer_name }}">{!! $questionData->questionanswer[$i]->answer_name !!}</label>
                            </div>
                            @endfor
                            @endif
                    </div>

                    <div class="mt-4 mb-2">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-10 gy-3">
                            @if ($questionData->question_type != "")
                            @if($questionData->question_type == 'single')
                            @php
                            $question_type = 'Single';
                            @endphp
                            @elseif($questionData->question_type == 'multi')
                            @php
                            $question_type = 'Multi';
                            @endphp
                            @else
                            @php
                            $question_type = 'User Input';
                            @endphp
                            @endif
                            <tr>
                                <th><b>Question type</b></th>
                                <td>{{ $question_type }}</td>
                            </tr>
                            @endif
                            @if ($questionData->que_level != "")
                            <tr>
                                <th><b>Question level</b></th>
                                <td>{{ $questionData->que_level }}</td>
                            </tr>
                            @endif
                            @if ($questionData->que_toc_no != "")
                            <tr>
                                <th><b>TOC No</b></th>
                                <td>{{ $questionData->que_toc_no }}</td>
                            </tr>
                            @endif
                            @if ($questionData->que_toc_text != "")
                            <tr>
                                <th><b>TOC text</b></th>
                                <td>{{ $questionData->que_toc_text }}</td>
                            </tr>
                            @endif
                            @if ($questionintent != "")
                            <tr>
                                <th><b>Question intent</b></th>
                                <td>{{ $questionintent }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th><b>Status</b></th>
                                <td><a href="#" class="btn btn-sm {{ ( $questionData->status == '0' ) ? 'btn-danger' : 'btn-success' }}" >{{ ( $questionData->status == '0' ) ? 'Deactive' : 'Active' }}</a></td>
                            </tr>
                        </table>	
                    </div>

                    @if(isset($questionData->question_desc))
                    <div class="col-sm-12 mb-5">
                        <div class="panel panel-bd" data-index="0">
                            <div class="panel-heading">
                                <div class="panel-title" style="max-width: calc(100% - 90px);">
                                    <h4>Question Description</h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="peragraph_ex">
                                    <p>{{ $questionData->question_desc }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(isset($questionData->question_media_type) && (!empty($questionData['question_media']) ||  !empty($questionData['question_media_multi'])))
                    <div class="col-sm-12">
                        <div class="panel panel-bd" data-index="0">
                            <div class="panel-heading">
                                <div class="panel-title" style="max-width: calc(100% - 90px);">
                                    <h4>Question Media</h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="peragraph_ex">
                                    <div class="card border mb-5 p-0">
                                        <div class="card-body">
                                            @if($questionData->question_media_type == 'single')
                                                @if (strpos($questionData['question_media'], '.mp4') !== false) 
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <video width="100%" class="embed-responsive-item" controls controlsList="nodownload" src="{{ $questionData['question_media'] }}"></video>
                                                </div>
                                                @else
                                                    <img src="{{ $questionData['question_media'] }}" alt="Question Media" class="thumb mt-0" />
                                                @endif
                                            @elseif($questionData->question_media_type == 'multi')
                                                @php $multi=explode(",",$questionData['question_media_multi']); @endphp
                                                @if(!empty($multi))
                                                <div id="displayimage">
                                                    @foreach($multi as $img)
                                                    <div class="col-md-2 col-sm-4 col-xs-12">
                                                        <div class="img-fix">
                                                            <img src="{{ $img }}" class="thumb selFile">
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                            @elseif($questionData->question_media_type == 'scorm')
                                                <iframe id="scorm_iframe" width="800" height="375" src="{{$questionData->question_media}}" frameborder="0" allowfullscreen></iframe>
                                            @else
                                            <p>{!! $questionData->question_media !!}</p>
                                            @endif
                                        </div>
                                                                                   

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($questionData->questionhashelp) || !$questionData['quehasimghelp']->isEmpty())
                    <div class="col-sm-12">
                        <div class="panel panel-bd" data-index="0">
                            <div class="panel-heading">
                                <div class="panel-title" style="max-width: calc(100% - 90px);">
                                    <h4>Question Help</h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                @if(!empty($questionData->questionhashelp))
                                @if(isset($questionData->questionhashelp['video']) && !empty(trim($questionData->questionhashelp['video'])) )
                                @php
                                $url = $questionData->questionhashelp['video'];
                                $urlArray = explode('.',$questionData->questionhashelp['video']);
                                if('youtube' == $urlArray[1]) {
                                $url = str_replace('watch?v=','embed/', $questionData->questionhashelp['video']);
                                }
                                @endphp
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">video help</h5>
                                        <iframe width="100%" height="315" src="{{ $url }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>
                                @endif
                                @if(isset($questionData->questionhashelp['audio']) && !empty(trim($questionData->questionhashelp['audio'])) )
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Audio help</h5>													
                                        <audio style="width:100%" controls>
                                            <source src="{{ $questionData->questionhashelp['audio'] }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                                @endif
                                @if(isset($questionData->questionhashelp['pdf']) && !empty(trim($questionData->questionhashelp['pdf'])) )
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Pdf help</h5>
                                        <embed src="{{ $questionData->questionhashelp['pdf'] }}" width="100%" height="750px" />
                                    </div>
                                </div>
                                @endif
                                @if(isset($questionData->questionhashelp['link']) && !empty(trim($questionData->questionhashelp['link'])) )
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Link help</h5>
                                        <a target="_blank" href="{{ $questionData->questionhashelp['link'] }}"> {{ $questionData->questionhashelp['link'] }} </a>
                                    </div>
                                </div>
                                @endif
                                @endif
                                @if(!$questionData['quehasimghelp']->isEmpty())
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Image help</h5>												
                                        <div id="displayimage">
                                            @foreach($questionData->quehasimghelp as $img)
                                            <div class="col-md-2 col-sm-4 col-xs-12">
                                                <div class="img-fix">
                                                    <img src="{{ $img->image }}" class="thumb selFile">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($questionData->questionhashint) || !$questionData['quehasimghint']->isEmpty())
                    <div class="col-sm-12">
                        <div class="panel panel-bd" data-index="0">
                            <div class="panel-heading">
                                <div class="panel-title" style="max-width: calc(100% - 90px);">
                                    <h4>Question Hint</h4>
                                </div>
                            </div>
                            <div class="panel-body">
                                @if(!empty($questionData->questionhashint))
                                @if(isset($questionData->questionhashint['video']) && !empty(trim($questionData->questionhashint['video'])) )
                                @php
                                $url = $questionData->questionhashint['video'];
                                $urlArray = explode('.',$questionData->questionhashint['video']);
                                if('youtube' == $urlArray[1]) {
                                $url = str_replace('watch?v=','embed/', $questionData->questionhashint['video']);
                                }
                                @endphp
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">video hint</h5>
                                        <iframe width="100%" height="315" src="{{ $url }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>
                                @endif
                                @if(isset($questionData->questionhashint['audio']) && !empty(trim($questionData->questionhashint['audio'])) )
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Audio hint</h5>													
                                        <audio style="width:100%" controls>
                                            <source src="{{ $questionData->questionhashint['audio'] }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                                @endif
                                @if(isset($questionData->questionhashint['pdf']) && !empty(trim($questionData->questionhashint['pdf'])) )
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Pdf hint</h5>
                                        <embed src="{{ $questionData->questionhashint['pdf'] }}" width="100%" height="750px" />
                                    </div>
                                </div>
                                @endif
                                @if(isset($questionData->questionhashint['link']) && !empty(trim($questionData->questionhashint['link'])) )
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Link hint</h5>
                                        <a target="_blank" href="{{ $questionData->questionhashint['link'] }}"> {{ $questionData->questionhashint['link'] }} </a>
                                    </div>
                                </div>
                                @endif
                                @endif
                                @if(!$questionData['quehasimghint']->isEmpty())
                                <div class="card border mb-5 p-0">
                                    <div class="card-body">
                                        <h5 class="card-title">Image hint</h5>												
                                        <div id="displayimage">
                                            @foreach($questionData->quehasimghint as $img)
                                            <div class="col-md-2 col-sm-4 col-xs-12">
                                                <div class="img-fix">
                                                    <img src="{{ $img->image }}" class="thumb selFile">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>


    <script type="text/javascript">
        $('#kt_modal_view_question_details').modal('show');
        $('#kt_modal_view_question_details').on('hidden.bs.modal', function() {
            $(".modal").remove();
            $('.modal-backdrop').remove();
        });
    </script>
</div>