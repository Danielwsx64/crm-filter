<div class="ls-box ls-board-box">
  <header class="ls-info-header">
    <p class="ls-float-right ls-float-none-xs ls-small-info">Período: <strong>{{ $opportunities['end_date'] }}</strong> - <strong>{{ $opportunities['start_date'] }}</strong></p>
    <h2 class="ls-title-3">Oportunidades</h2>
  </header>

  <div class="row">

    <div class="col-sm-6 col-md-3">
      <div class="ls-box">

        <div class="ls-box-head">
          <h6 class="ls-title-4">Novas</h6>
        </div>

        <div class="ls-box-body">
          <span class="ls-board-data">
            <strong class="ls-color-theme">{{ $opportunities['news'] }}</strong>
          </span>
        </div>

      </div>
    </div>

    <div class="col-sm-6 col-md-3">
      <div class="ls-box">

        <div class="ls-box-head">
          <h6 class="ls-title-4">Em aberto</h6>
        </div>

        <div class="ls-box-body">
          <span class="ls-board-data">
            <strong class="ls-color-theme">{{ $opportunities['opened'] }}</strong>
            <small>{{ number_format($opportunities['opened_percent'], 2) }}%</small>
          </span>
        </div>

      </div>
    </div>

    <div class="col-sm-6 col-md-3">
      <div class="ls-box">

        <div class="ls-box-head">
          <h6 class="ls-title-4">Ganhamos</h6>
        </div>

        <div class="ls-box-body">
          <span class="ls-board-data">
            <strong class="ls-color-theme">{{ $opportunities['won'] }}</strong>
            <small>{{ number_format($opportunities['won_percent'], 2) }}%</small>
          </span>
        </div>

      </div>
    </div>

    <div class="col-sm-6 col-md-3">
      <div class="ls-box">

        <div class="ls-box-head">
          <h6 class="ls-title-4">Perdemos</h6>
        </div>

        <div class="ls-box-body">
          <span class="ls-board-data">
            <strong class="ls-color-theme">{{ $opportunities['lost'] }}</strong>
            <small>{{ number_format($opportunities['lost_percent'], 2) }}%</small>
          </span>
        </div>

      </div>
    </div>

  </div>
</div>
