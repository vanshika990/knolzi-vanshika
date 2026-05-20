<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if(!$categorys->isEmpty())
        @foreach($categorys as $category)
            <url>
                <loc>{{ route('categorycourses',$category->slug) }}</loc>
                <lastmod>{{ $category->created_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
        @endforeach
    @endif
    @if(!$courses->isEmpty())
        @foreach($courses as $course)
            <url>
                <loc>{{ route('coursedetails',$course->slug) }}</loc>
                <lastmod>{{ $course->created_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
        @endforeach
    @endif
</urlset>