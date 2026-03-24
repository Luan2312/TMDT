<div class="ibox">
    <div class="ibox-title">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <h5>ALbum Image</h5>
            <div class="upload-album">
                <a href="" class="upload-picture">Select Image</a>
            </div>
        </div>
    </div>
    <div class="ibox-content">
        @php
            $gallery = (isset($album) && count($album)) ? $album : old('album');
        @endphp
        <div class="row">
            <div class="col-lg-12">
                @if(!isset($gallery) || count($gallery) == 0)
                <div class="click-to-upload mb30">
                    <div class="icon">
                        <a href="" class="upload-picture">
                            <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="80"
                            height="80"
                            viewBox="0 0 64 64"
                            fill="none"
                            stroke="#cfd4da"
                            stroke-width="2"
                            stroke-linejoin="round"
                            stroke-linecap="round"
                            >
                            <rect x="8" y="10" width="40" height="30" rx="2" ry="2"/>

                            <rect x="12" y="14" width="40" height="30" rx="2" ry="2"/>

                            <rect x="16" y="18" width="40" height="30" rx="2" ry="2"/>

                            <circle cx="26" cy="28" r="3"/>
                            <path d="M20 42l8-8 6 6 6-6 8 8"/>
                            </svg>

                        </a>
                    </div>
                    <div class="small-text">Su dung nut chon hinh hoac click vao day de them hinh anh</div>
                </div>
                @endif


                <div class="upload-list {{ (isset($gallery) && count($gallery)) ? '' : 'hidden' }}">
                    <ul id="sortable" class="clearfix data-album sortui ui-sortable">
                        @if(isset($gallery) && count($gallery))
                            @foreach ($gallery as $key => $val)
                            <li class="ui-state-default">
                                <div class="thumb">
                                    <span class="span image img-scaledown">
                                        <img src="{{ $val }}" alt="{{ $val }}">
                                        <input type="hidden" name="album[]" value="{{ $val }}">
                                    </span>
                                    <button class="delete-image"><i class="fa fa-trash"></i></button>
                                </div>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
