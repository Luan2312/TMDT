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
<form action="{{ route('post.destroy', $posts->id) }}" method="POST" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thong tin chung</div>
                    <div class="panel-description">Xoa thong tin chung cua nhom su dung</div>
                </div>

            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Name <span class="text-danger">(*)</span></label>
                                    <input type="text" name="name" value="{{ old('name', $posts->name ?? '') }}" class="form-control" placeholder="" autocomplete="off" readonly>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb10">
            <button class="btn btn-danger" type="submit" name="send" value="send">Delete</button>
        </div>
    </div>
</form>

