@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['create']['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@php
    $url = ($config['method'] == 'create') ? route('language.store') : route('language.update', $languages->id)
@endphp
<form action="{{ $url }}" method="POST" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thong tin chung</div>
                    <div class="panel-description">Nhap thong tin chung cua Ngon Ngu su dung</div>
                </div>

            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Language Name <span class="text-danger">(*)</span></label>
                                    <input type="text" name="name" value="{{ old('name', $languages->name ?? '') }}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Canonical <span class="text-danger">(*)</span></label>
                                    <input type="text" name="canonical" value="{{ old('canonical', $languages->canonical ?? '') }}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>

                        </div>
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Avatar <span class="text-danger">(*)</span></label>
                                    <input type="text" name="image" value="{{ old('image', $languages->image ?? '') }}" class="form-control upload-image" placeholder="" autocomplete="off" data-type="Images">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Note</label>
                                    <input type="text" name="description" value="{{ old('description', $languages->description ?? '') }}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb10">
            <button class="btn btn-primary" type="submit" name="send" value="send">Save</button>
        </div>
    </div>
</form>

