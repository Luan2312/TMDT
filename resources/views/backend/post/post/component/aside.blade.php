<div class="ibox">
    <div class="ibox-content">
        <div class="row mb10">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">Chon danh muc cha <span class="text-danger">(*)</span></label>
                    <span class="text-danger notice"> *Chon Root neu khong co danh muc cha</span>
                    <select name="post_catalogue_id" class="form-control setupSelect2" id="">
                        @foreach ($dropdown as $key => $val)
                            <option {{
                                    $key == old('post_catalogue_id', (isset($posts->post_catalogue_id))? $posts->post_catalogue_id : '') ? 'selected' : ''
                                }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @php
            $catalogue = [];
            if(isset($posts)){
                foreach($posts->post_catalogues as $key => $val){
                    $catalogue[] = $val->id;
                }
            }
        @endphp
        <div class="row mb10">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">Danh muc phu <span class="text-danger">(*)</span></label>
                    <select multiple name="catalogue[]" class="form-control setupSelect2" id="">
                        @foreach ($dropdown as $key => $val)
                            <option
                            @if (is_array(old('catalogue', (isset($catalogue) && count($catalogue)) ?
                            $catalogue : [])) && isset($posts) && $key !== $posts->post_catalogue_id && in_array($key, old('catalogue',
                            (isset($catalogue)) ? $catalogue : []))) selected

                            @endif
                            value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title"><h5>Chon anh dai dien</h5></div>
    <div class="ibox-content">
        <div class="row mb10">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target"><img src="{{ old('image',($posts->image) ?? 'backend/img/not_found.jpg') }}" alt=""></span>
                    <input type="hidden" name="image" value="{{ old('image', ($posts->image) ?? 'backend/img/not_found.jpg') }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title"><h5>Cau hinh nang cao</h5></div>
    <div class="ibox-content">
        <div class="row mb10">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="mb10">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach (__('messages.publish') as $key => $val )
                                <option {{
                                    $key == old('publish', (isset($posts->publish))? $posts->publish : '') ? 'selected' : ''
                                }} value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" class="form-control setupSelect2" id="">
                        @foreach (__('messages.follow') as $key => $val )
                            <option {{
                                    $key == old('follow', (isset($posts->follow))? $posts->follow : '') ? 'selected' : ''
                                }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

