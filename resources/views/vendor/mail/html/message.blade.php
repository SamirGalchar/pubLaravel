@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{--{{ config('app.name') }}--}}<img src="{{ asset('assets/images/logo-for-email-template.png') }}" />
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy 
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset --}}

{{-- Footer --}}
@slot('footer')
<table class="m_-1876636661601082055footer" role="presentation" style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';margin:0 auto;padding:0;text-align:center;width:570px" width="570" cellspacing="0" cellpadding="0" align="center">
    <tbody>
        <tr>
            <td style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';max-width:100vw;padding:32px 6px;" align="center">
                <div style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';float:left">
                    All Rights Reserved © Cobra Sports Performance Limited 2017-{{ date('Y') }}
                </div>
                <div style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';float:right">
                    <a href="https://www.facebook.com/groups/381382130770638/" style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color:#b0adc5;text-decoration:underline" target="_blank" ><img src="https://fit-30.online/training/front/images/facebook-mail.png"></a>
                    <a href="https://www.instagram.com/cobraforce1/" style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color:#b0adc5;text-decoration:underline" target="_blank" ><img src="https://fit-30.online/training/front/images/instagram-mail.png"></a>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@endslot
@endcomponent
