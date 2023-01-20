/* 

NirvanaCyruz
  controller
  prefix
  
  datatables
  datatable()

  inputs
  input



*/









class Nirvana {

  constructor( root={} ) {
    
    this.root = root;

    for (const name in this.root.apps) {
      this[name] = this.root.apps[name];
      if (typeof this[name].__start !== 'undefined') {
        this[name].__start();
      }
    }

    // for (const name in this.root.binding) {
    //   let sub = this.root.binding[name];
    //   this[name][sub] = this.root.controller[sub];
    //   // console.log(this.root.controller[sub]);
    // }

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

    // UNDER DEVELOPMENT
    // for (const name in this.root.bounds) {
    //   let bind = this.root.bounds[name];
    //   this[name][bind[1]] = this.root.apps[bind[0]][bind[1]].bind( this.root.apps[bind[2]] );
    //   console.log(name);
    // }

    
    // options.controller.forEach( (control) => {
    //   let name = control.constructor.name.toLowerCase();
    //   this[name] = control;
    //   options.controller.forEach( (control) => {
    //     let _name = control.constructor.name.toLowerCase();
    //     if (name !== _name) {
    //       this[name][_name] = control;
    //     }
    //   });
    //   if (typeof this[name].start !== 'undefined') {
    //     this[name].start();
    //   }
    // });
  }

  app( name, id=null ) {
    this[name].__proto( id );
  }

}







class NirvanaApp {

  apps = this.constructor.name;

  prefix = 'nirvana';

  datatables;
  modals;
  form=new FormData();
  inputs={};

  // main setup
  constructor() {
    this.target = this.prefix+"-"+this.apps;
    this.target = this.target.toLowerCase();

    this.formOnSubmit();
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

  // output handling
  output( select, value=null ) {
    $("."+this.target+" ."+select).html( value );
    return $("."+this.target+" ."+select);
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
  select2(data, select, input) {
    let key = select.split(", ");
    let select2=[];
    if (typeof data == 'string') {
      this.xhttp("GET", data, {"Q[select]": select}, (response) => {
        response.data.forEach( (value) => {
          select2.push({ id:value[key[0]], text:value[key[1]] });
        });
        this.input( input ).select2({ theme:"bootstrap-5", data:select2, dropdownParent:$("."+this.target) });
      });
    }else {
      data.forEach((value) => {
        select2.push({ id:value[key[0]], text:value[key[1]] });
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
            value.button = value.button.replaceAll("{id}", value.id);
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







