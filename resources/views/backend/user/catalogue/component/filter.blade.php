<form action="{{ route('user.catalogue.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="perpage">
                @php
                    $perpage = request('perpage') ?: old('perpage');
                @endphp
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <select name="perpage" class="form-control input-sm perpage filter mr10" id="">
                        @for ($i =20;$i <= 200;$i+=20)
                            <option value="{{ $i }}" {{ ($perpage == $i) ? 'selected' : '' }}>{{ $i }} ban ghi</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @php

                        $publish = request('publish') ?: old('publish');
                    @endphp
                    <select name="publish" class="form-control setupSelect2" id="">

                        @foreach (config('apps.general.publish') as $key => $val )
                            <option {{ ($publish == $key) ? 'selected': '' }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach

                    </select>
                    {{-- <select name="user_catalogue_id" class="form-control mr10 setupSelect2" id="">
                        <option value="0" selected="selected">--Chon nhom thanh vien</option>
                        <option value="1">Quan Tri Vien</option>
                    </select> --}}
                    <div class="uk-search uk-flex uk-flex-middle mr10">
                        <div class="input-group">
                            <input type="text" name="keyword" value="{{ request('keyword') ?: old('keyword') }}" placeholder="Nhap tu khoa can tim..." class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" name="search" value="search" class="btn btn-primary mb0 btn-sm">
                                    Search
                                </button>
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('user.catalogue.create') }}" class="btn btn-danger"><i class="fa fa-plus mr5"></i>Add New Group</a>
                </div>

            </div>
        </div>

    </div>

</form>
