<html>

@foreach($users as $user)
    {{$user->name}} - {{$user->email}}
@endforeach

</html>
