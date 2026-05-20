<div id="experienceModalAdd" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden overflow-y-auto">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-bg-primary rounded-3xl p-8 shadow-2xl max-w-2xl w-full relative border border-border">
            <!-- Close Button -->
            <button onclick="closeExperienceModalAdd()" class="absolute top-4 right-4 text-text-light hover:text-text-secondary text-2xl transition-colors duration-200">✕</button>
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold mb-2 text-text-primary">Add Experience</h2>
                <p class="text-text-secondary">Enter your work experience details</p>
            </div>
            <!-- Form -->
            <form class="space-y-6" action="{{route('add-work-experience-post')}}" id="addexperience" name="addexperience" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium mb-2 text-text-secondary">Company Name</label>
                        <input type="text" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name" required>
                    </div>
                    <div>
                        <label for="experience" class="block text-sm font-medium mb-2 text-text-secondary">Experience</label>
                        <input type="text" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="experience" name="experience" value="{{ old('experience') }}" placeholder="Experience (e.g. 2 years)" required>
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-medium mb-2 text-text-secondary">Year</label>
                        <input type="number" maxlength="4" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="year" name="year" value="{{ old('year') }}" placeholder="Year" required>
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium mb-2 text-text-secondary">Role</label>
                        <input type="text" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="role" name="role" value="{{ old('role') }}" placeholder="Role" required>
                    </div>
                    <div>
                        <label for="designation" class="block text-sm font-medium mb-2 text-text-secondary">Designation</label>
                        <input type="text" class="w-full bg-bg-primary border border-border rounded-xl py-3 px-4 text-text-primary placeholder-text-light outline-none focus:border-primary focus:ring-2 focus:ring-primary-light transition-colors duration-200" id="designation" name="designation" value="{{ old('designation') }}" placeholder="Designation" required>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="flex justify-end mt-8 space-x-3">
                    <button type="button"
                        onclick="$('#experienceModalAdd, #experienceModalEdit').remove(); document.body.style.overflow='auto';"
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
        function closeExperienceModalAdd() {
            document.getElementById('experienceModalAdd').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        document.getElementById('experienceModalAdd').addEventListener('click', function(e) {
            if (e.target === this) {
                closeExperienceModalAdd();
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeExperienceModalAdd();
            }
        });
        $(document).ready(function() {
            $("#addexperience").validate({
                rules: {
                    'company_name': { required: true },
                    'experience': { required: true },
                    'year': { required: true },
                    'role': { required: true },
                    'designation': { required: true }
                },
                submitHandler: function(form) {
                    var _url = '{{route("add-work-experience-post")}}';
                    var data = new FormData(form);
                    $.ajax({
                        url: _url,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            closeExperienceModalAdd();
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
                    }).fail(function(xhr, textStatus, errorThrown) {
                        $('.text-danger').empty();
                        var errors = "";
                        if (xhr.status == 422) {
                            if (xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(i, val) {
                                    errors += "<b><p>" + val[0] + "</p></b>";
                                });
                                if (errors !== "") {
                                    swal({
                                        title: "Error!",
                                        text: errors,
                                        type: "error",
                                        html: true,
                                        confirmButtonColor: "#d33"
                                    });
                                    return false;
                                }
                            }
                        } else if (xhr.status == 500 || xhr.status == 404 || xhr.status == 400) {
                            swal({
                                title: "Error!",
                                text: "Server error",
                                type: "error",
                                html: true,
                                confirmButtonColor: "#d33"
                            });
                            return false;
                        } else {
                            swal({
                                title: "Error!",
                                text: "No internet Connection. Please check your internet connection.",
                                type: "error",
                                html: true,
                                confirmButtonColor: "#d33"
                            });
                            return false;
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</div>
