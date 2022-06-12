@component('mail::message')
# Hi {{$student->name}},
# Welcome in Notes System

You can start working using your CMS to manage API actions.

@component('mail::panel')
    # Your API Key is:<br>
    {{$student->api_uuid}}
@endcomponent

@component('mail::button', ['url' => 'http://notes.mr-dev.tech/cms/student/login'])
    Notes Student CMS
@endcomponent

Thanks,<br>
# Momen M.Reyad Sisalem,<br>
# {{ config('app.name') }}
@endcomponent
