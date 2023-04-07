@php
$allRootCategoriesWithBlogsCount = allRootCategoriesWithBlogsCount();
@endphp
<!--Offcanvas sidebar-->
<aside id="sidebar-wrapper" class="custom-scrollbar offcanvas-sidebar">
    <button class="off-canvas-close"><i class="athena-cancel"></i></button>
    <div class="sidebar-inner">
        <div class="sidebar-widget widget-creative-menu">
            <ul>
                @forelse($allRootCategoriesWithBlogsCount as $cat)
                <li><a href="{{ route('category.posts', $cat->slug) }}">{{ $cat->name }}<sup>{{ $cat->blogs_count }}</sup></a></li>
                @empty
                @endforelse
            </ul>
        </div>
        <div class="sidebar-widget widget-menu pt-30">
            <div class="widget-header-3 font-primary mb-20">
                <h5 class="widget-title text-uppercase text-white">Follow</h5>
            </div>
            <ul>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Instagram</a></li>
                <li><a href="#">Behance</a></li>
            </ul>
        </div>
        {{-- <div class="offcanvas-copyright">
            <p class="text-muted text-uppercase mb-0">{{ config('app.name') }}</p>
            <p class="text-muted mb-0 font-medium">123 Main Street</p>
            <p class="text-muted mb-0 font-medium">New York, NY 10001</p>
        </div> --}}
    </div>
</aside>