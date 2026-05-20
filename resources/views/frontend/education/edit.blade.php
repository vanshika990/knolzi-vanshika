<div id="educationModalEdit" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-bg-primary rounded-3xl p-8 shadow-2xl max-w-2xl w-full relative border border-border">
            <!-- Close Button -->
            <button onclick="closeEducationModalEdit()" class="absolute top-4 right-4 text-text-light hover:text-text-secondary text-2xl transition-colors duration-200">✕</button>
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold mb-2 text-text-primary">Edit Education</h2>
                <p class="text-text-secondary">Update your educational details</p>
            </div>
            <!-- Form -->
            <form class="space-y-6" action="{{route('update-edu-qua')}}" id="updateeducation" name="updateeducation" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ isset($education) ? encrypt($education->id) : '' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="degree" class="block text-sm font-medium mb-2 text-text-secondary">Education Background</label>
                        <input type="text" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="degree" name="degree" value="{{ $education->degree ?? '' }}" placeholder="Your Education Background" required>
                        @if ($errors->has('degree'))
                            <span class="text-error text-sm mt-1 block">{{ $errors->first('degree') }}</span>
                        @endif
                    </div>
                    <div>
                        <label for="university" class="block text-sm font-medium mb-2 text-text-secondary">University</label>
                        <input type="text" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="university" name="university" value="{{ $education->university ?? '' }}" placeholder="Your University" required>
                        @if ($errors->has('university'))
                            <span class="text-error text-sm mt-1 block">{{ $errors->first('university') }}</span>
                        @endif
                    </div>
                    <div>
                        <label for="institute" class="block text-sm font-medium mb-2 text-text-secondary">Institute</label>
                        <input type="text" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="institute" name="institute" value="{{ $education->institute ?? '' }}" placeholder="Institute" required>
                        @if ($errors->has('institute'))
                            <span class="text-error text-sm mt-1 block">{{ $errors->first('institute') }}</span>
                        @endif
                    </div>
                    <div>
                        <label for="stream" class="block text-sm font-medium mb-2 text-text-secondary">Stream</label>
                        <input type="text" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="stream" name="stream" value="{{ $education->stream ?? '' }}" placeholder="Your stream" required>
                        @if ($errors->has('stream'))
                            <span class="text-error text-sm mt-1 block">{{ $errors->first('stream') }}</span>
                        @endif
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-medium mb-2 text-text-secondary">Year</label>
                        <input type="number" maxlength="4" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="year" name="year" value="{{ $education->year ?? '' }}" placeholder="Your Education Year" required>
                        @if ($errors->has('year'))
                            <span class="text-error text-sm mt-1 block">{{ $errors->first('year') }}</span>
                        @endif
                    </div>
                    <div>
                        <label for="grade" class="block text-sm font-medium mb-2 text-text-secondary">Grade</label>
                        <input type="number" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="grade" name="grade" value="{{ $education->grade ?? '' }}" placeholder="Your Grade" required>
                        @if ($errors->has('grade'))
                            <span class="text-error text-sm mt-1 block">{{ $errors->first('grade') }}</span>
                        @endif
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="flex justify-end mt-8 space-x-3">
                    <button type="button"
                        onclick="$('#educationModalAdd, #educationModalEdit').remove(); document.body.style.overflow='auto';"
                        class="px-6 py-3 rounded-xl bg-bg-light text-text-secondary hover:bg-light font-semibold transition-colors duration-200 border border-border shadow-sm">
                        Close
                    </button>
                    <button type="submit"
                        class="btn-primary px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        function closeEducationModalEdit() {
            document.getElementById('educationModalEdit').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        document.getElementById('educationModalEdit').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEducationModalEdit();
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEducationModalEdit();
            }
        });
        $(document).ready(function() {
            $("#updateeducation").validate({
                rules: {
                    'degree': { required: true },
                    'university': { required: true },
                    'institute': { required: true },
                    'stream': { required: true },
                    'year': { required: true },
                    'grade': { required: true }
                },
                submitHandler: function(form) {
                    var _url = '{{route("update-edu-qua")}}';
                    var data = new FormData(form);
                    $.ajax({
                        url: _url,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            closeEducationModalEdit();
                            if (response) {
                                swal({
                                    title: "Success!",
                                    text: response.message,
                                    type: "success",
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "OK"
                                }, function(){
                                    location.reload();
                                });
                            }
                        },
                    }).fail(function(xhr) {
                        var errors = "";
                        if (xhr.status == 422 && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(i, val) {
                                errors += "<b><p>" + val[0] + "</p></b>";
                            });
                        } else {
                            errors = 'Server error or no internet connection.';
                        }
                        swal({
                            title: "Error!",
                            text: errors,
                            type: "error",
                            html: true,
                            confirmButtonColor: "#d33"
                        });
                    });
                    return false;
                }
            });
        });
    </script>
</div>
