@php
$allCategories = categoriesWithDescendants();
@endphp
@forelse($allCategories as $mainMenu)
    @php $descendants = $mainMenu->descendants; @endphp
    <li @if( count($descendants) > 0 ) class="menu-item-has-children" @endif>
        <a href="javascript:void(0);">{{ $mainMenu->name }}</a>
        
        @if( count($descendants) > 0 )
            <ul class="sub-menu">
            @foreach($descendants as $subMenu)
                <li><a href="javascript:void(0);">{{ $subMenu->name }}</a></li>
            @endforeach
            </ul>
        @endif
    </li>
@empty
@endforelse
