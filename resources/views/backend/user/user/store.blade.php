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
    $url = ($config['method'] == 'create') ? route('user.store') : route('user.update', $user->id)
@endphp
<form action="{{ $url }}" method="POST" class="box">
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
                                    <input type="text" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ho Ten <span class="text-danger">(*)</span></label>
                                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>

                        </div>
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhom Thanh Vien <span class="text-danger">(*)</span></label>
                                    <select name="user_catalogue_id" id="" class="form-control setupSelect2">
                                        <option value="0">--Chon Nhom Thanh Vien--</option>
                                        <option value="1">Quan Tri Vien</option>
                                        <option value="2">Cong Tac Vien</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ngay Sinh </label>
                                    <input type="date" name="birthday" value="{{ old('birthday', (isset($user->birthday)) ? date('Y-m-d', strtotime($user->birthday)) : '') }}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    @if ($config['method'] == 'create')
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Mat Khau <span class="text-danger">(*)</span></label>
                                    <input type="password" name="password" value="" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Nhap lai Mat Khau <span class="text-danger">(*)</span> </label>
                                    <input type="password" name="re_password" value="" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>

                        </div>
                    @endif
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Anh dai dien</label>
                                    <input type="text" name="image" value="{{ old('image', $user->image ?? '') }}" class="form-control upload-image" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thong tin lien he</div>
                    <div class="panel-description">Nhap thong tin lien he cua nguoi su dung</div>
                </div>

            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Thanh Pho <span class="text-danger">(*)</span></label>
                                    <select name="province_id" class="form-control setupSelect2 province location" data-target="districts" id="">
                                        <option value="0">--Chon Thanh Pho--</option>
                                        @if (isset($provinces))
                                        @foreach ($provinces as $province)
                                        <option @if (old('province_id') == $province->code) selected @endif
                                         value="{{ $province->code }}">{{ $province->name }}</option>

                                        @endforeach

                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Quan/Huyen <span class="text-danger">(*)</span></label>
                                    <select name="district_id" class="form-control districts setupSelect2 location" data-target="wards" id="">
                                        <option value="0">--Chon Quan/Huyen--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Phuong/Xa <span class="text-danger">(*)</span></label>
                                    <select name="ward_id" class="form-control setupSelect2 wards" id="">
                                        <option value="0">--Chon Phuong/Xa--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Dia chi </label>
                                    <input type="text" name="address" class="form-control" placeholder="" value="{{ old('address', $user->address ?? '') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb10">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">So Dien Thoai </label>
                                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone ?? '') }}" autocomplete="off" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">Ghi Chu </label>
                                    <input type="text" name="description" class="form-control" placeholder="" value="{{ old('description', $user->description ?? '') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb10">
            <button class="btn btn-primary" type="submit" name="send" value="send">Luu Lai</button>
        </div>
    </div>
</form>

<script>
    var province_id = '{{ (isset($user->province_id)) ? $user->province_id : old('province_id') }}'
    var district_id = '{{ (isset($user->district_id)) ? $user->district_id : old('district_id') }}'
    var ward_id = '{{ (isset($user->ward_id)) ? $user->ward_id : old('ward_id') }}'
</script>
