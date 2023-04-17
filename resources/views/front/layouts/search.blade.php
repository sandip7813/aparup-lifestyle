<!--Start search form-->
<div class="main-search-form">
    <div class="container">
        <div class="pt-150 pb-50 ">
            <div class="row mb-20">
                <div class="col-12 align-self-center main-search-form-cover m-auto">
                    <button class="search-icon search-icon-close -md-inline">
                        <i class="athena-cancel mr-5"></i>
                    </button>
                    <p><span class="search-text-bg font-secondary">Search</span></p>
                    <form action="{{ route('search.results') }}" class="search-header" method="get" id="search-form">
                        <div class="input-group w-100">
                            <input type="text" class="form-control" name="keyword" placeholder="Enter post title and hit Enter">
                            <div class="input-group-append">
                                <button class="btn btn-search bg-white" type="submit" id="search-btn">
                                    <i class="athena-search mr-5"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    {{-- <div class="search-suggested mt-80">
                        <h5 class="suggested font-heading mb-20"> <strong>Suggested:</strong></h5>
                        <ul class="list-inline d-inline-block">
                            <li class="list-inline-item"><a href="category.html">Events</a></li>
                            <li class="list-inline-item"><a href="category-2.html">Shop</a></li>
                            <li class="list-inline-item"><a href="category-3.html">Tech</a></li>
                            <li class="list-inline-item"><a href="category-4.html">Fashion</a></li>
                            <li class="list-inline-item"><a href="category.html">Books</a></li>
                            <li class="list-inline-item"><a href="category-2.html">Travel</a></li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>