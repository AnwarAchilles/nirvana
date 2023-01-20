

class NirvanaCyruz {

  controller='';

  prefix='nirvana';

  datatables;

  modals;

  // main setup
  constructor( controller=null, option={} ) {
    this.controller = controller || this.constructor.name;
    this.prefix = option.prefix || this.prefix;

    
    this.target = this.prefix+"-"+this.controller;
    this.target = this.target.toLowerCase();

  }

  // form handling
  form( callback ) {
    return $("."+this.target+" form").on("submit", callback);
  }

  // input handling
  input( select=null ) {
    if (select == null) {
      return $("."+this.target+" [name]");
    }else {
      return $("."+this.target+" [name='"+select+"'");
    }
  }

  // output handling
  output( select, value ) {
    $("."+this.target+" ."+select).html( value );
  }


  // xml http request
  http(type, url, data=null, callback=null, sync=true) {
    let ajax = {};
    ajax.type = type;
    ajax.async = sync,
    ajax.url = base_url + url;
    if (data !== null) {
      ajax.data = data;
    }
    if (callback !== null) {
      ajax.success = callback;
    }
    $.ajax(ajax);
  }

  // plugin select2
  select2() {}

  // plugin datatables
  datatable(columns, ajax, dataCallback) {
    this.datatable = $(".datatables").DataTable({
      serverSide: true,
      columns: columns,
      ajax: {
        url: base_url + ajax.url,
        type: ajax.type,
        dataSrc: function(x) {
          x.data.forEach( function(value, i) {
            value.button = $(".datatables_button").html();
            value.button = value.button.replaceAll("{id}", value.id);
            value.button = value.button.replaceAll("{base_url}", base_url);
            value.button = value.button.replaceAll("{current_url}", current_url);
            dataCallback(value, i);
            x.data[i] = value;
          });
          return x.data;
        },
      },
    });
    return this.datatable;
  }

  // plugin chartjs
  chartjs() {
    return 'chartjs';
  }
  
  // toast bootstrap
  toast() {}
  
  // modal bootstrap
  modal() {
    this.modals = new bootstrap.Modal($("."+this.target), {
      keyboard: false
    });
    return this.modals;
  }

}