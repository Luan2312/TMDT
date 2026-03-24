<div class="ibox">
    <div class="ibox-title">
        <h5>Cau hinh SEO</h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">{{ (old('meta_title',($posts->meta_title) ?? 'Chua co tieu de SEO')) }}</div>
            <div class="canonical">{{ ($canonical = old('canonical', $posts->canonical ?? null)) ? config('app.url') . $canonical . config('apps.general.suffix') : 'https://duong-dan-chua-co.html'}}</div>
            <div class="meta-description">
                {{ (old('meta_description', ($posts->meta_description) ?? 'Chua co Content')) }}
            </div>
        </div>
        <div class="seo-wrapper">
            <div class="row mb10">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>Mo ta SEO</span>
                                <span class="count_meta-title">0 ky tu</span>
                            </div>
                        </label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', ($posts->meta_title) ?? '') }}" class="form-control" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb10">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>Tu khoa SEO</span>
                        </label>
                        <input type="text" name="meta_keyword" value="{{ old('meta_keyword', ($posts->meta_keyword) ?? '') }}" class="form-control" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb10">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>Mo ta SEO</span>
                                <span class="count_meta-description">0 ky tu</span>
                            </div>
                        </label>
                        <textarea type="text" name="meta_description" class="form-control" placeholder="" autocomplete="off">{{ old('meta_description', ($posts->meta_description) ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mb10">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>Duong dan <span class="text-danger">(*)</span></span>
                        </label>
                        <div class="input-wrapper">
                            <input type="text" name="canonical" value="{{ old('canonical', ($posts->canonical) ?? '') }}" class="form-control" placeholder="" autocomplete="off">
                            <span class="baseUrl">{{ config('app.url') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
