<li class="pt-2 pb-2" style="list-style-type:none;">
  <div class="icheck-success d-inline mr-5">
    <input type="radio" name="parent_category" id="parent_category_{{ $categoryT['id'] }}" value="{{ $categoryT['id'] }}" @if($p_id == $categoryT['id']) checked @endif>
    <label for="parent_category_{{ $categoryT['id'] }}" style="font-weight: normal;">{{ $categoryT['name'] }}</label>
  </div>
</li>
{{--
@if (count($categoryT['descendants']) > 0)
  <ul>
  @foreach($categoryT['descendants'] as $categoryT)
    @include('admin.category.tree-dropdown', $categoryT)
  @endforeach
  </ul>
@endif
--}}