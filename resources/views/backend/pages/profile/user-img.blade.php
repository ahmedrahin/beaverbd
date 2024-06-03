@if( !is_null(Auth::user()->image) )
    <div class="img-box">
        <img src="{{asset(Auth::user()->image)}}" alt="" id="upImg">
        <input type="file" name="image" class="upImg">
        <i class="ri-delete-bin-7-fill hasRemove"></i>
        <input type="hidden" name="hasRemove" id="hasRemove">
        <i class="fas fa-camera"></i>
    </div>
@else
    <div class="img-box">
        <img src="{{asset('backend/images/user.jpg')}}" alt="" id="upImg">
        <input type="file" name="image" class="upImg">
        <input type="hidden" name="hasRemove" id="hasRemove">
        <i class="fas fa-camera"></i>
        <i class="ri-delete-bin-7-fill"></i>
    </div>
@endif