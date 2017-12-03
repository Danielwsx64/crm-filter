var Application = Application || {};

Application.opportunity = (function(){

  var filter_options;
  var filter_route;
  var filter_content;
  var use_storage = true;

  function init(options) {

    if (typeof(Storage) === "undefined")
      use_storage = false;

    filter_options = options.filter_options || {};
    filter_route = options.filter_route;
    filter_content = options.filter_content;

    add_event_listeners();

    if ( options.star_onInit )
      render_filter_result(filter_options);
  }


  /*  Private  */

  function add_event_listeners() {
    $("[action='clean']").click(function(event){
      $('#filter_form')[0].reset();

    });

    $("[action='filter']").click(function(event){
      send_filter_form();
    });

    $("[action='export_page']").click(function(event){
      send_filter_export('page');
    });

    $("[action='export_all']").click(function(event){
      send_filter_export('all');
    });
  }

  function change_list_size(size) {

    filter_options.list_size = size;
    filter_options.list_page = 1;
    render_filter_result(filter_options);
  }

  function change_list_page(page) {
    filter_options.list_page = page;
    render_filter_result(filter_options);
  }

  function change_list_order(name, order) {
    var list_of_orders = {
      none: 'ASC',
      ASC: 'DESC',
      DESC: 'ASC',
    };

    if( ! filter_options.list_order )
      filter_options.list_order = {};

    filter_options.list_order.name = name;
    filter_options.list_order.order = list_of_orders[order];

    render_filter_result(filter_options);
  }

  function send_filter_export(type) {
    var data = filter_options;
    data.export = type;

    url = '/Oportunidades/Export' + '?' + $.param(data);
    window.open(url, '_blank');
  }

  function send_filter_form() {

    $("#filter_form select").each(function(){
      var selected = $(this).val();
      var empty_index = (selected) ? selected.indexOf("") : -1;

      if (empty_index >= 0)
        selected.splice(empty_index, 1);

      if ( !selected || selected.length < 1 )
        delete filter_options[$(this).attr('name')];
      else
        filter_options[$(this).attr('name')] = selected;

    });

    $("#filter_form input").each(function(){
      if( $(this).val() != "" )
        filter_options[$(this).attr('name')] = $(this).val();
      else
        delete filter_options[$(this).attr('name')];
    });

    filter_options.list_page = 1;
    render_filter_result(filter_options);
  }

  function render_filter_result(data) {
    pre_render_events();

    if(!data._token)
      data._token = $('[name="csrf-token"]').attr('content');

    $.post(filter_route, data, function(response){
      $(filter_content).html('');
      $(filter_content).append(response);
      post_render_events();
    });
  }

  function pre_render_events() {

    $(filter_content + ' table').addClass('ls-transparent-25');

  }

  function post_render_events() {

    locastyle.popover.init()

    $("#list_size_selector").change(function(event){
      change_list_size($(this).val());
    });

    $("#list_page_selector a").click(function(event){
      event.preventDefault();
      if(! $(this).parent().hasClass('ls-disabled') )
        change_list_page( $(this).data('pageNumber') );
    });

    $(filter_content + " table [link-type='order']").click(function(){
      change_list_order($(this).data('orderName'), $(this).data('order'))
    });

  }

  function get_options() {
    return filter_options;
  }

  return{
    init: init,
    options: get_options
  };

}());
