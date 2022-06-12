@component('mail::message')
# Hi {{$student->name}},
# Welcome in Notes System

Regarding to your request, password has been reset.

@component('mail::panel')
# Your New Password is:<br>
{{$newPassword}}
@endcomponent

@component('mail::button', ['url' => 'http://notes.mr-dev.tech/cms/student/login'])
Notes Student CMS
@endcomponent

Thanks,<br>
# {{ config('app.name') }}
@endcomponent