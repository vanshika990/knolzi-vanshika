@if(!empty($subcategories))
    <ul class="py-2 text-sm text-gray-700">
        @foreach($subcategories as $subcategory)
            <li class="relative group">
                <a href="{{ route('categorycourses', $subcategory->slug) }}"
                   class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 hover:text-blue-600 transition duration-150 ease-in-out">
                    {{ $subcategory->name }}
                    @if(count($subcategory->subcategory))
                        <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif
                </a>

                @if(count($subcategory->subcategory))
                    <div class="absolute left-full top-0 ml-1 w-72 bg-white border border-gray-200 rounded-lg shadow-lg z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200">
                        @include('page.menu.subCategoryList', ['subcategories' => $subcategory->subcategory])
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
@endif
