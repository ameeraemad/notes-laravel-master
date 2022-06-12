@component('mail::message')
# Hi {{$student->name}},
# Welcome in Notes System

A new user registered using Application.

@component('mail::panel')
# User name<br>
{{$user->first_name." ".$user->last_name}}
@endcomponent

@component('mail::button', ['url' => 'http://notes.mr-dev.tech/cms/student/login'])
Notes Student CMS
@endcomponent

Thanks,<br>
# Momen M.Reyad Sisalem,<br>
# {{ config('app.name') }}
@endcomponent