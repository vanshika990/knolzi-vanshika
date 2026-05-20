@if(!empty($subcategories))
<ul class="submenu collapse">
    @foreach($subcategories as $subcategory)
    <li><a href="{{ route('categorycourses',$subcategory->slug) }}" class="nav-link">{{$subcategory->name}}<i class="@if(count($subcategory->subcategory) > 0) bi bi-chevron-right @endif"></i></a>
        @if(count($subcategory->subcategory))
        @include('page.menu.subCategoryListMobile',['subcategories' => $subcategory->subcategory])
        @endif
    </li>
    @endforeach
</ul>
@endif