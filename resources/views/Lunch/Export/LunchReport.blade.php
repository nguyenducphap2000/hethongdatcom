<table class="table">
        <thead>
            <tr>
            <th scope="col">STT</th>
            <th scope="col">Tên</th>
            <th scope="col">Email</th>
            <th scope="col">Roles</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $value)
            <tr>
                <th scope="row">{{ $loop->index+1}}</th>
                <td>{{ $value->user->name }}</td>
                <td>{{ $value->user->email }}</td>
                @if ($value->user->isAdmin == 1)
                    <td>Admin</td>
                @else
                    <td>User</td>
                @endif
            </tr>
            @endforeach
        </tbody>
</table>