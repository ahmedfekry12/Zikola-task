<x-mail::message>
# Introduction

Muilty Vendor Reset Password.

{{-- <x-mail::button :url="''">
Button Text
</x-mail::button> --}}

<p>your reset code is : {{ $otp }}</p>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>