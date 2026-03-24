@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])

<form action="{{ route('user.destroy', $user->id) }}" method="POST" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thong tin chung</div>
                    <div class="panel-description">Nhap thong tin chung cua nguoi su dung</div>
                </div>

            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Email <span class="text-danger">(*)</span></label>
                                    <input type="text" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" placeholder="" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ho Ten <span class="text-danger">(*)</span></label>
                                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" placeholder="" autocomplete="off" readonly>
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

