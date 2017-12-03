@if(sizeof($opportunities) < 1)
  <div class="ls-alert-warning"><strong>Nenhuma Oportunidade encontrada.</strong> Tente alterar os parametros informados.</div>
@else

  @php
    $order_class_list = array(
      'ASC' => 'ls-data-ascending',
      'DESC' => 'ls-data-descending',
    );

    $data_order = $list_order['order'];
    $data_order_name = $list_order['name'];
    $order_class = $order_class_list[$data_order];
  @endphp

  <table class="ls-table ls-bg-header">
    <thead>
      <tr>
        <th class="{{ ($data_order_name == 'opportunity') ? $order_class : '' }}">
          <a href="#" data-order="{{ ($data_order_name == 'opportunity') ?  $data_order : 'none' }}" data-order-name="opportunity" link-type="order">
            Oportunidade
          </a>
        </th>
        <th class="{{ ($data_order_name == 'user_name') ? $order_class : '' }}">
          <a href="#" data-order="{{ ($data_order_name == 'user_name') ?  $data_order : 'none' }}" data-order-name="user_name" link-type="order">
            Corretor
          </a>
        </th>
        <th class="{{ ($data_order_name == 'development') ? $order_class : '' }} hidden-md hidden-sm hidden-xs">
          <a href="#" data-order="{{ ($data_order_name == 'development') ?  $data_order : 'none' }}" data-order-name="development" link-type="order">
            Empreendimento
          </a>
        </th>
        <th class="{{ ($data_order_name == 'sales_stage') ? $order_class : '' }} hidden-xs">
          <a href="#" data-order="{{ ($data_order_name == 'sales_stage') ?  $data_order : 'none' }}" data-order-name="sales_stage" link-type="order">
            Fase da venda
          </a>
        </th>
        <th class="{{ ($data_order_name == 'phone') ? $order_class : '' }}">
          <a href="#" data-order="{{ ($data_order_name == 'phone') ?  $data_order : 'none' }}" data-order-name="phone" link-type="order">
            Telefone
          </a>
        </th>
        <th class="{{ ($data_order_name == 'email') ? $order_class : '' }}">
          <a href="#" data-order="{{ ($data_order_name == 'email') ?  $data_order : 'none' }}" data-order-name="email" link-type="order">
            Email
          </a>
        </th>
      </tr>
    </thead>
    <tbody>
      @for ($i = 0; $i < sizeof($opportunities); $i++)
        <tr>

          <td>
            <a href='#' class="ls-ico-info ls-float-right hidden-md hidden-sm hidden-xs" data-ls-module="popover" data-title="Informações da Oportunidade" data-content="@include('opportunity._popover', [ 'opportunity' => $opportunities[$i] ])" data-placement="right" title="Mais Informações"></a>
            <a href="{{ get_opportunity_url( $opportunities[$i]->opportunity_id ) }}" target="_blank">
              {{ $opportunities[$i]->opportunity }}
            </a>
          </td>

          <td>
            {{ $opportunities[$i]->user_name . ' ' . $opportunities[$i]->user_last_name }}
          </td>

          <td class="hidden-md hidden-sm hidden-xs">
            {{ $opportunities[$i]->development }}
          </td>

          <td class="hidden-xs">
            {{ $opportunities[$i]->sales_stage }}
          </td>

          <td class="ls-clearfix">
            {{ $opportunities[$i]->phone }}
          </td>

          <td>{{ $opportunities[$i]->email }}</td>

        </tr>
      @endfor
    </tbody>
  </table>

  @php
    $page_group = intval($list_page / 5) + 1;
    $total_groups = intval($total_pages / 5) + 1;

    $start = ( $page_group * 5 ) - 4;

    if( $start > $list_page ) {
      $start = $start - 5;
      $page_group--;
    }

  @endphp

  <div class="ls-pagination-filter">
    <ul class="ls-pagination" id="list_page_selector">

      <li {!! ($list_page == 1) ? 'class="ls-disabled"' : '' !!}>
        <a href="#" data-page-number="{{ $list_page - 1 }}">&laquo; Anterior</a>
      </li>

      @if($page_group > 1)
        <li>
          <a href="#" data-page-number="{{ $list_page - 5 }}">...</a>
        </li>
      @endif

      @for ($i = $start; $i < $start + 5; $i++ )
        @break( ($total_pages + 1 - $i) <= 0 )

        <li {!! ($list_page == $i) ? 'class="ls-active"' : '' !!}>
          <a href="#" data-page-number="{{ $i }}">{{ $i }}</a>
        </li>
      @endfor

      @if($page_group < $total_groups)
        <li>
          <a href="#" data-page-number="{{ $list_page + 5 }}">...</a>
        </li>
      @endif

      <li {!! ( ($total_pages - $list_page) <= 0 ) ? 'class="ls-disabled"' : '' !!}>
        <a href="#" data-page-number="{{ $list_page + 1 }}">Próximo &raquo;</a>
      </li>

    </ul>

    <div class="ls-filter-view">
      <div class="ls-custom-select">
        <select name="list_size" id="list_size_selector" class="ls-select">
          @for($i = 15; $i <= 60; $i = $i + 15)
            <option {{ ( $list_size == $i ) ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
          @endfor
        </select>
      </div>
    </div>
  </div>

@endif
