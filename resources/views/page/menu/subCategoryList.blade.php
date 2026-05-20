@if(!empty($subcategories))
<ul>
    @foreach($subcategories as $subcategory)
    <li><a href="{{ route('categorycourses',$subcategory->slug) }}">{{$subcategory->name}}<i class="@if(count($subcategory->subcategory) > 0) bi bi-chevron-right @endif"></i></a>
        @if(count($subcategory->subcategory))
        @include('page.menu.subCategoryList',['subcategories' => $subcategory->subcategory])
        @endif
    </li>
    @endforeach
</ul>
@endif
