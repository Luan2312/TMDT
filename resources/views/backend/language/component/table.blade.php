<table class="table table-striped table-bordered ">
    <thead>
    <tr>
        <th><input type="checkbox" value="" id="checkAll" class="input-checkbox"></th>
        <th style="width:100px;">Avatar</th>
        <th>Language Name</th>
        <th class="text-center">Canonical</th>
        <th>Content</th>
        <th class="text-center">Active</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
        @if (isset($languages) && is_object($languages))
            @foreach ($languages as $language)
                <tr >
                    <td><input type="checkbox" value="{{ $language->id }}" class="input-checkbox checkBoxItem"></td>
                    <td>
                        <span class="image img-cover"><img src="{{ $language->image }}" alt=""></span>
                    </td>
                    <td>
                        <div class="user-item name"><strong>{{ $language->name }}</strong> </div>
                    </td>
                    <td class="text-center">
                        {{ $language->canonical }}
                    </td>
                    <td>
                        <div class="user-item description"><strong>{{ $language->description }}</strong> </div>
                    </td>
                    <td class="text-center js-switch-{{ $language->id }}">
                        <input type="checkbox" value="{{ $language->publish }}" class="js-switch status" data-field="publish" data-model="language" {{ ($language->publish == 2) ? 'checked': '' }} data-modelId="{{ $language->id }}">
                    </td>
                    <td class="text-center">
                        <a href="{{ route('language.edit', $language->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('language.delete', $language->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $languages->links('pagination::bootstrap-4') }}
