<form id="filter_form" action="{{ route('opportuninies_filter') }}" action="POST" class="ls-form ls-form-horizontal row">

  {{ csrf_field()  }}

  <fieldset>

    <label class="ls-label col-md-3 col-sm-4 col-xs-12">
      <b class="ls-label-text">Responsável</b>
      <div class="ls-custom-select select-multiple">
        <select multiple class="ls-select" name="user_owner">
          <option value=""></option>
          @foreach($users as $user)
            <option value="{{ $user->first_name . $user->last_name }}">
            {{ $user->first_name . ' ' . $user->last_name }}
            </option>
          @endforeach
        </select>
      </div>
    </label>

    <label class="ls-label col-md-3 col-sm-4 col-xs-12">
      <b class="ls-label-text">Atribuido à</b>
      <div class="ls-custom-select select-multiple">
        <select multiple class="ls-select" name="assigned">
          <option value=""></option>
          @foreach($users as $user)
            <option value="{{ $user->first_name . $user->last_name }}">
            {{ $user->first_name . ' ' . $user->last_name }}
            </option>
          @endforeach
        </select>
      </div>
    </label>

    <label class="ls-label col-md-3 col-sm-4 col-xs-12">
      <b class="ls-label-text">Empreendimento</b>
      <div class="ls-custom-select select-multiple">
        <select multiple class="ls-select" name="development">
          <option value=""></option>
          @foreach($developments as $development)
            <option value="{{ $development->name }}">{{ $development->name }}</option>
          @endforeach
        </select>
      </div>
    </label>

    <label class="ls-label col-md-3 col-sm-4 col-xs-12">
      <b class="ls-label-text">Fase da venda</b>
      <div class="ls-custom-select select-multiple">
        <select multiple class="ls-select" name="sales_stage">
          <option value=""></option>
          @foreach($sales_stages as $sales_stage)
            @if($sales_stage->sales_stage != null)
              <option value="{{ $sales_stage->sales_stage }}">
              {{ $sales_stage->sales_stage }}
              </option>
            @endif
          @endforeach
        </select>
      </div>
    </label>

    <label class="ls-label col-md-3 col-sm-4 col-xs-12">
      <b class="ls-label-text">Origem</b>
      <div class="ls-custom-select select-multiple">
        <select multiple class="ls-select" name="lead_source">
          <option value=""></option>
          @foreach($lead_sources as $lead_source)
            @if($lead_source->lead_source != null)
              <option value="{{ $lead_source->lead_source }}">
              {{ $lead_source->lead_source }}
              </option>
            @endif
          @endforeach
        </select>
      </div>
    </label>

    <label class="ls-label col-md-3 col-sm-4 col-xs-12">
      <b class="ls-label-text">Oportunidade</b>
      <input type="text" name="opportunity_name" placeholder="Nome da Oportunidade" class="ls-field">
    </label>

    <label class="ls-label col-md-3 col-sm-4 col-xs-12">
      <b class="ls-label-text">Conta</b>
      <input type="text" name="account_name" placeholder="Nome da conta" class="ls-field">
    </label>

  </fieldset>

  <div class="ls-actions-btn">
    <a class="ls-btn ls-ico-search" action="filter">Filtrar</a>
    <a class="ls-btn-danger ls-ico-remove" action="clean">Limpar</a>
    <div data-ls-module="dropdown" class="ls-dropdown">
      <a href="#" class="ls-btn ls-ico-export" role="combobox" aria-expanded="false">Exportar</a>
      <ul class="ls-dropdown-nav" aria-hidden="true">
        <li><a href="#" role="option" action="export_page">Página</a></li>
        <li><a href="#" role="option" action="export_all">Tudo</a></li>
      </ul>
    </div>
  </div>
</form>
