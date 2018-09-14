<html>

@foreach($users as $user)
    {{$user->name}} - {{$user->email}}
@endforeach

<example-component></example-component>
</html>
