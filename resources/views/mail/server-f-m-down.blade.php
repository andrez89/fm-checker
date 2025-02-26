<x-mail::message>
    # {{$database->name}} is down.

    FM Server <a href="https://{{$database->host}}" target="_blank">{{$database->host}}</a>

    Error: {{$error}}
</x-mail::message>