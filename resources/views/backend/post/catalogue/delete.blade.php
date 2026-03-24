@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('post.catalogue.destroy', $postCatalogues->id) }}" method="POST" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">{{ __('messages.generalTitle') }}</div>
                    <div class="panel-description">{{ __('messages.generalDescription') }}</div>
                </div>

            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">{{ __('messages.generalName') }} <span class="text-danger">(*)</span></label>
                                    <input type="text" name="name" value="{{ old('name', $postCatalogues->name ?? '') }}" class="form-control" placeholder="" autocomplete="off" readonly>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb10">
            <button class="btn btn-danger" type="submit" name="send" value="send">{{ __('messages.postCatalogue.delete.title') }}</button>
        </div>
    </div>
</form>

