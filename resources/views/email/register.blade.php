@component('mail::message')
### 您好：{{ $username }}

@component('mail::button', ['url'=>$url])
点击验证
@endcomponent

或者复制 链接[{{ $url }}]({{ $url }}),进行验证

若您没有在TcTOrz社区填写过注册信息，说明有人滥用了您的电子邮箱，请删除此邮件，我们对给您造成的打扰感到抱歉。

Thanks, <br>
{{ config('app.name') }}
@endcomponent
