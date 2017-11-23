@extends('layouts.web.base')

@section('main')
<div class="ls-login-parent">
  <div class="ls-login-inner">
    <div class="ls-login-container">
      <div class="ls-login-box">
        <h1 class="ls-login-logo"><img title="Logo login" src="images/company_logo.png" /></h1>
        <form role="form" class="ls-form ls-login-form" method="POST" action="{{ route('login') }}">
          {{ csrf_field() }}

          <fieldset>

            <label class="ls-label">
              <b class="ls-label-text ls-hidden-accessible">Usuário</b>
              <input name="name" class="ls-login-bg-user ls-field-lg" type="text" value="{{ isset($name) ? $name : '' }}" placeholder="Usuário" required autofocus>
            </label>

            <label class="ls-label">
              <b class="ls-label-text ls-hidden-accessible">Senha</b>
              <div class="ls-prefix-group ls-field-lg">
                <input id="password_field" name="password" class="ls-login-bg-password" type="password" placeholder="Senha" required>
                <a class="ls-label-text-prefix ls-toggle-pass ls-ico-eye" data-toggle-class="ls-ico-eye, ls-ico-eye-blocked" data-target="#password_field" href="#"></a>
              </div>
            </label>

            <input type="submit" value="Entrar" class="ls-btn-primary ls-btn-block ls-btn-lg">
          </fieldset>

          @if ( isset( $error ) )
            <div class="ls-txt-center" style="color: #d75553"> {{ $error }} </div>
          @endif

        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page_custom_assets')
<script>
  $(document).ready(function(){

    $.get( '{{ route ('background_url') }}', function( url ) {

      css_url = 'URL(' + url + ')';

      if(url != 'not found') {
        $('.ls-login-parent').fadeTo('slow', 0.3, function() {
              $(this).css('background-image', css_url);

        }).fadeTo('slow', 1);
      }

    });

  });
  </script>
@endsection
