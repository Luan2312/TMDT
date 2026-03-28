<table class="table table-striped table-bordered ">
    <thead>
    <tr>
        <th><input type="checkbox" value="" id="checkAll" class="input-checkbox"></th>
        <th>Permission Name</th>
        <th class="text-center">Canonical</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
        @if (isset($permissions) && is_object($permissions))
            @foreach ($permissions as $permission)
                <tr >
                    <td><input type="checkbox" value="{{ $permission->id }}" class="input-checkbox checkBoxItem"></td>
                    <td>
                        <div class="user-item name"><strong>{{ $permission->name }}</strong> </div>
                    </td>
                    <td class="text-center">
                        {{ $permission->canonical }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('permission.edit', $permission->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('permission.delete', $permission->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $permissions->links('pagination::bootstrap-4') }}
