<div class="ibox">
    <div class="ibox-content">
        <div class="row mb10">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">{{ __('messages.parentId') }}<span class="text-danger">(*)</span></label>
                    <span class="text-danger notice">{{ __('messages.parentNotice') }}</span>
                    <select name="parent_id" class="form-control setupSelect2" id="">
                        @foreach ($dropdown as $key => $val)
                            <option {{
                                    $key == old('parent_id', (isset($postCatalogues->parent_id))? $postCatalogues->parent_id : '') ? 'selected' : ''
                                }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title"><h5>{{ __('messages.image') }}</h5></div>
    <div class="ibox-content">
        <div class="row mb10">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target"><img src="{{ old('image',($postCatalogues->image) ?? 'backend/img/not_found.jpg') }}" alt=""></span>
                    <input type="hidden" name="image" value="{{ old('image', ($postCatalogues->image) ?? 'backend/img/not_found.jpg') }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title"><h5>{{ __('messages.advange') }}</h5></div>
    <div class="ibox-content">
        <div class="row mb10">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="mb10">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach (__('messages.publish') as $key => $val )
                                <option {{
                                    $key == old('publish', (isset($postCatalogues->publish))? $postCatalogues->publish : '') ? 'selected' : ''
                                }} value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" class="form-control setupSelect2" id="">
                        @foreach (__('messages.follow') as $key => $val )
                            <option {{
                                    $key == old('follow', (isset($postCatalogues->follow))? $postCatalogues->follow : '') ? 'selected' : ''
                                }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

