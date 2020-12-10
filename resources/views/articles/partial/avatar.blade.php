@php
    $size = isset($size) ? $size : 48;
@endphp

@if(isset($user) and $user)
    <a class="pull-left" href="{{ gravatar_profile_url($user->email) }}">
        <img class="media-object img-thumbnani" src="{{ gravatar_url($user->email, $size) }}" alt="{{ $user->name }}">
    </a>
@else
    <a class="pull-left" href="{{ gravatar_profile_url('unknown@example.com') }}">
        <img src="{{ gravatar_url('unknown@example.com') }}" alt="Unknown User">
    </a>
@endif