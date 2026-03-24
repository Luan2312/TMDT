<div class="row mb10">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('messages.title') }}<span class="text-danger">(*)</span></label>
            <input type="text"  name="name" value="{{ old('name', $postCatalogues->name ?? '') }}" class="form-control" placeholder="" autocomplete="off">
        </div>
    </div>
</div>
<div class="row mb30">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('messages.description') }}<span class="text-danger">(*)</span></label>
            <textarea id="description" type="text" name="description" value="" class="form-control ckeditor" placeholder="" autocomplete="off" data-height="100">
                {{ old('description', $postCatalogues->description ?? '') }}
            </textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="form-row">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <label for="" class="control-label text-left">{{ __('messages.content') }}<span class="text-danger">(*)</span></label>
                <a href="" class="multipleUploadImageCkeditor" data-target="content">{{ __('messages.uploadImages') }}</a>
            </div>
            <textarea id="content" type="text" name="content" value="" class="form-control ckeditor" placeholder="" autocomplete="off" data-height="500">{{ old('content', $postCatalogues->content ?? '') }}</textarea>
        </div>
    </div>
</div>
