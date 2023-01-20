// loader
class CoreLoader {

  constructor( root={} ) {
    this.root = root;

    for (const name in this.root.apps) {
      this[name] = this.root.apps[name];
      if (typeof this[name].__start !== 'undefined') {
        this[name].__start();
      }
    }

    for (const clones in this.root.clones) {
      let methodInApps='';
      let methodSendToApps=[];    
      for (const apps in this.root.apps) {
        this.root.clones[clones].forEach((method) => {
          if (method in this.root.apps[apps]) {
            methodInApps=apps;
          }else {
            methodSendToApps.push(apps);
          }
        });
      }
      methodSendToApps.forEach((apps) => {
        this.root.clones[clones].forEach((method) => {
          this[apps][method] = this[methodInApps][method].bind( this[apps] );
        });
      });
    }
  }

  app( name, id=null ) {
    this[name].__proto( id );
  }
  
}



// controller
class CoreController {
  
  apps = this.constructor.name;

  prefix = 'core';

  datatables;
  ckeditors={};
  modals;
  form=new FormData();
  inputs={};

  // main setup
  constructor() {
    this.target = this.prefix+"-"+this.apps;
    this.target = this.target.toLowerCase();

    this.formOnSubmit();
  }

  // format number
  formatNumber( value ) {
    if (value!==undefined) {
      return new Intl.NumberFormat().format(parseFloat(value));
    }else {
      return 0;
    }
  }

  // form on submit
  formOnSubmit() {
    $("."+this.target+" form").on("submit", (event) => {
      this.inputs = {};
      this.__form( event );
      event.preventDefault();
    });
  }

  form( select=null, data ) {
    // under development
  }

  // input handling
  input( select=null ) {
    if (select == null) {
      return $("."+this.target+" [name]");
    }else {
      return $("."+this.target+" [name='"+select+"'");
    }
  }

  // selector
  select( select, value=null) {
    if (value!==null) {
      $("."+this.target+" ."+select).html( value );
    }else {
      return $("."+this.target+" ."+select);
    }
  }

  // output handling
  output( select, value=null) {
    if (value!==null) {
      $("."+this.target+" ."+select).html( value );
    }else {
      return $("."+this.target+" ."+select);
    }
  }


  // xml http request
  xhttp(type, url, data=null, callback=null, sync=true) {
    let ajax = {};
    ajax.type = type;
    ajax.async = sync;
    ajax.url = base_url + url;
    if (data !== null) {
      ajax.data = data;
    }
    if (callback !== null) {
      ajax.success = callback.bind( this );
    }
    if (data instanceof FormData) {
      ajax.processData = false;
      ajax.contentType = false;
    }
    $.ajax(ajax);
  }

  // plugin select2
  select2(urlData, select, input, callback) {
    let select2=[];
    if (typeof urlData == 'string') {
      this.xhttp("GET", urlData, {"Q[select]":select}, (response) => {
        let data = response.data || [];
        data.forEach( (value) => {
          select2.push(callback(value));
        });
        this.input( input ).select2({ theme:"bootstrap-5", data:select2, dropdownParent:$("."+this.target) });
      });
    }else {
      urlData.forEach((value) => {
        select2.push(callback(value));
      });
      this.input( input ).select2({ theme:"bootstrap-5", data:select2, dropdownParent:$("."+this.target) });
    }
  }

  // plugin datatables
  datatable(columns, ajax, dataCallback) {
    let callback = dataCallback.bind( this );
    this.datatables = $(".datatables").DataTable({
      serverSide: true,
      columns: columns,
      ajax: {
        url: base_url + ajax.url,
        type: ajax.type,
        dataSrc: (x) => {
          x.data.forEach( (value, i) => {
            value.button = $(".datatables_button").html();
            
            for (const name in value) {
              value.button = value.button.replaceAll("{"+name+"}", value[name]);
            }
              
            value.button = value.button.replaceAll("{base_url}", base_url);
            value.button = value.button.replaceAll("{current_url}", current_url);
            callback(value, i);
            x.data[i] = value;
          });
          return x.data;
        },
      },
    });
    return this.datatables;
  }

  // plugin chartjs
  chartjs() {
    return 'chartjs';
  }

  // plugin ckeditor
  ckeditor( selectorId ) {
    if (typeof this.ckeditors[selectorId] !== 'object') {
      ClassicEditor.create( document.querySelector("#"+selectorId+""), {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'numberedList', 'bulletedList', 'blockQuote', '|', 'insertTable', 'undo', 'redo' ]
      }).then((newEditor)=> {
        this.ckeditors[selectorId] = newEditor;
      });
    }
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