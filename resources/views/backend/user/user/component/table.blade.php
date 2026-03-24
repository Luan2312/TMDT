<table class="table table-striped table-bordered ">
    <thead>
    <tr>
        <th><input type="checkbox" value="" id="checkAll" class="input-checkbox"></th>
        <th style="width: 90px;">Avatar</th>
        <th>User</th>
        <th>Email</th>
        <th>Address</th>
        <th>Phone</th>
        <th class="text-center">Group Name</th>
        <th class="text-center">Active</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
        @if (isset($users) && is_object($users))
            @foreach ($users as $user)
                <tr >
                    <td><input type="checkbox" value="{{ $user->id }}" class="input-checkbox checkBoxItem"></td>
                    <td>
                        <span class="image img-cover"><img src="{{ $user->image }}" alt=""></span>
                    </td>
                    <td>
                        <div class="user-item name"><strong>{{ $user->name }}</strong> </div>
                    </td>
                    <td>
                        <div class="email-item name"><strong>{{ $user->email }}</strong> </div>
                    </td>
                    <td>
                        <div class="address-item name"><strong>{{ $user->address }}</strong></div>
                    </td>
                    <td>
                        <div class="phone-item name"><strong>{{ $user->phone }}</strong></div>
                    </td>
                    <td class="text-center">
                        {{ $user->user_catalogues->name }}
                    </td>
                    <td class="text-center js-switch-{{ $user->id }}">
                        <input type="checkbox" value="{{ $user->publish }}" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($user->publish == 2) ? 'checked': '' }} data-modelId="{{ $user->id }}">
                    </td>
                    <td class="text-center">
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('user.delete', $user->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $users->links('pagination::bootstrap-4') }}
