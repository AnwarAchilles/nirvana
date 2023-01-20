/* 
 * CORE TABLE Based datatables
 * ---- ---- ---- ---- */
class CoreTable {
  // private
  __frontend={};
  __table={};
  // playground
  columns=[];
  renders={};
  // main
  constructor( Frontend, Table ) {
    this.__frontend = Frontend;
    this.__table = Table;
  }
  // build table nest based
  build( nest, options ) {
    // set parameters
    const params = {};
    params.columns = options.cols;
    params.type = options.http[0];
    params.url = options.http[1]+"/datatable";
    params.dataSrc = options.patch.bind( this );
    // set columns
    this.columns[nest] = params.columns;
    // render table
    this.renders[nest] = $(".datatables").DataTable({
      serverSide: true,
      columns: params.columns,
      ajax: {
        url: this.__table.baseurl+"/"+params.url,
        type: params.type,
        dataSrc: ( src ) => {
          src.data.forEach( (data, i) => {
            data.button = $(".datatables_button").html();

            for (const name in data) {
              data.button = data.button.replaceAll("{"+name+"}", data[name]);
            }

            data.button = data.button.replaceAll("{base_url}", base_url);
            data.button = data.button.replaceAll("{current_url}", current_url);

            params.dataSrc(data, i);

            src.data[i] = data;
          });
          return src.data;
        }
      }
    });    
  }
  // set column
  column( columns={} ) {
    if (Array.isArray(columns)) {
      this.columns = columns;
    }else {
      this.columns.push(columns);
    }
  }
  // reload table
  reload( nest ) {
    this.__frontend.table.renders[nest].ajax.reload();
  }
}
/* 
 * CORE MODAL Based bootstrap 
 * ---- ---- ---- ---- */
class CoreModal {
  // setup
  __frontend = {};
  __modal = {};
  // playground
  constructor( Frontend, Modal ) {
    this.__frontend = Frontend;
    this.__modal = Modal;
  }
  // build modal
  build( nest, options ) {
    let params = {};
    params.url = options.http[0];
    params.data = {};
    params.data.modal = options.http[1].modal+"/"+this.__frontend.name;
    
    if (typeof options.http!=='undefined') {
      let element = this.__frontend.xhttp("POST", params.url, params.data, false).responseJSON;
      let modalParse = $($.parseHTML(element.data[0]));
      $("["+this.__frontend.prefix+"-modal='"+nest+"']").html( modalParse );
    }

    this.__modal[nest] = new bootstrap.Modal( $(this.__frontend.target), { keyboard: false });
  }
  // patch data
  patch( nest, name, data ) {
    let target = $(this.__frontend.target).find("["+this.__frontend.prefix+"-modal]");
    let content = target.html();
    content = content.replaceAll("{{"+name+"}}", data);
    target.html(content);
  }
  // show modal
  show( nest ) {
    this.__modal[nest].show();
  }
  // hide modal
  hide( nest ) {
    if (this.__modal[nest] !== false) {
      $( this.__frontend.target ).remove();
    }
    this.modal[nest].hide();
  }
  // trigger
  patch() {}
}
/* 
 * CORE FORM
 * ---- ---- ---- ---- */
class CoreForm {
  // setup
  __frontend={};
  __form={};
  // accessable variable
  datas={};
  values={};
  // main constructor init
  constructor( Frontend, Form ) {
    this.__frontend = Frontend;
    this.__form = Form;
  }
  // intialize all form controls
  initialize( callback ) {
    let forms = $(this.__frontend.target).find("["+this.__frontend.prefix+"-form]");
    let formsTemporary = {};
    let __form = {};

    forms.each( (i, form)=> {
      let formBaseName = $(form).attr(this.__frontend.prefix+"-form");
      let formControls = $(form).find("[name]");
      formsTemporary[formBaseName] = [];
      formControls.each( (i, controls)=> {
        let controlBaseName = $(controls).attr("name");
        formsTemporary[formBaseName].push(controlBaseName);
      });
    });

    Object.entries( formsTemporary ).forEach((entries)=> {
      let [formName, controlsName] = entries;
      let form = $(this.__frontend.target).find("["+this.__frontend.prefix+"-form='"+formName+"']");

      if ( form.length>1 ) {
        __form[formName] = [];
        $(form).each( (i, form)=> {
          let formControls = $(form).find("[name]");
          __form[formName][i] = {};
          formControls.each( (ii, controls)=> {
            let controlBaseName = $(controls).attr("name");
            __form[formName][i][controlBaseName] = $(controls);
          });
        });
      }else {
        __form[formName] = {};
        $(form).each( (i, form)=> {
          let formControls = $(form).find("[name]");
          formControls.each( (ii, controls)=> {
            let controlBaseName = $(controls).attr("name");
            __form[formName][controlBaseName] = $(controls);
          });
        });
      }
    });

    this.__form = __form;
  }
  // builder
  build( nest, patcher=null ) {
    this.initialize();

    this.datas[nest] = new FormData();
    this.values[nest] = {};
    Object.entries( patcher ).forEach( entries=> {
      const [controls, value] = entries;
      this.datas[nest].append(controls, value);
      this.values[nest][controls] = value;
    });
  }
  // patching data
  patch( nest, indexOrInput=false, input=false) {
    this.initialize();

    let params = [];
    if (typeof indexOrInput=='string') {
      params = [indexOrInput, input];
      return this.__form[nest][ params[0] ];
    }else {
      params = [input, indexOrInput];
      return this.__form[nest][ params[1] ][ params[0] ];
    }
  }
  // get form values
  value( nest=false ) {
    if (nest!==false) {
      return this.values[nest];
    }else {
      return this.values;
    }
  }
  // get form data
  data( nest=false ) {
    if (nest!==false) {
      return this.datas[nest];
    }else {
      return this.datas;
    }
  }
}
/* 
 * CORE EDITOR Based ckeditor5 
 * ---- ---- ---- ---- */
class CoreEditor {

}
/* 
 * CORE SELECT Based select2 
 * ---- ---- ---- ---- */
class CoreSelect {
  // options
  __frontend={};
  __select={};
  // data
  datas={};
  // constructor
  constructor( Frontend, Select ) {
    this.__frontend = Frontend;
    this.__select = Select;
  }
  // builder nest
  build( nest, options ) {
    let data = [];

    this.datas[nest] = [];
    this.__select[nest] = [];

    if (typeof options.data!=='undefined') {
      data = options.data;
    }

    if (typeof options.http!=='undefined') {
     data = this.__frontend.xhttp(options.http[0], options.http[1]).responseJSON.data; 
    }

    data.forEach( data => {
      this.__select[nest].push( data );
    });

    if (typeof options.patch!=='undefined') {
      let patcher = options.patch.bind( this );
      this.__select[nest].forEach( data => {
        const select = { id:"id should be here", text:"text should be here" };
        patcher( select, data );
        this.datas[nest].push( select );
      });
    }

    options.input.select2({ theme:"bootstrap-5", data:this.datas[nest], dropdownParent:$(this.__frontend.target) });
  }
  // get data
  data( nest ) {
    if (nest==null) {
      this.data;
    }else {
      this.data[nest];
    }
  }
}
/* 
 * CORE LOADER BASED 
 * ---- ---- ---- ---- */
class CoreLoader {
  frontend={};
  // main
  constructor( Frontend ) {
    this.frontend= Frontend;
  }
  // load core form
  form( option={} ) {
    this.frontend.form = new CoreForm( this.frontend, option );
  }
  // modal bootstrap
  modal( option={} ) {
    this.frontend.modal = new CoreModal( this.frontend, option );
  }
  // modal bootstrap
  toast( option={} ) {
    this.frontend.toast = new CoreToast( this.frontend, option );
  }
  // plugin datatables
  table( option={} ) {
    this.frontend.table = new CoreTable( this.frontend, option );
  }
  // plugin editor
  editor( option={} ) {
    this.frontend.editor = new CoreEditor( this.frontend, option );
  }
  // plugin editor
  select( option={} ) {
    this.frontend.select = new CoreSelect( this.frontend, option );
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

}

/* 
 * CORE FRONTEND BASED 
 * ---- ---- ---- ---- */
class CoreFrontend {

  // set name applications
  name = this.constructor.name;

  // prefix main target
  prefix = "frontend";

  __setup = {};

  // for CoreLoader
  load = {};

  // plugin
  form={};
  modal={};
  toast={};
  select2={};
  ckeditor={};

  // spesial function
  onStart = "start";
  onInit = "index";
  onSubmit = "submit";

  // main
  constructor() {
    // setup core
    this.load = new CoreLoader( this );

    // set target element
    this.target = "["+this.prefix+"='"+this.name+"']";
    // this.target = this.target.toLowerCase();

    // set onsubmit special function
    this.__onSubmit();
  }

  // special function on initialize
  __onInit( data ) {
    this[this.onInit] ( data );
  }

  // special function form on submitted
  __onSubmit() {
    $(this.target+" form").on("submit", (event) => {
      this.input = {};
      this[ this.onSubmit ] ( event );
      event.preventDefault();
    });
  }

  // setup setting
  setup( newSetup ) {
    this.__setup = newSetup;
  }
  // xml http request
  xhttp(type, url, data=null, callback=null, sync=false) {
    let ajax = {};
    ajax.type = type;
    ajax.url = base_url + "/" + url;
    ajax.async = sync;
    if (typeof data=='boolean') {
      ajax.async = data;
    }
    if (typeof data=='object') {
      ajax.data = data;
    }
    if (typeof data=='function') {
      ajax.success = data.bind( this );
    }
    if (typeof callback=='boolean') {
      ajax.async = callback;
    }
    if (typeof callback=='function') {
      ajax.success = callback.bind( this );
    }
    if (data instanceof FormData) {
      ajax.processData = false;
      ajax.contentType = false;
    }
    return $.ajax(ajax);
  }
  // todo patch data
  patch( from, variable, data ) {
    return from.replaceAll("{"+variable+"}", data);
  }
}


/* 
 * CORE FRAMEWORK BASED 
 * ---- ---- ---- ---- */
class Framework {

  constructor( root={} ) {
    this.root = root;

    for (const name in this.root.Apps) {
      this[name] = this.root.Apps[name];
      if (typeof this[name]['onStart'] !== 'undefined') {
        this[name].app[ this[name]['onStart'] ]();
      }
    }

    
    for (const clones in this.root.Clones) {
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

  app( name, data=null ) {
    this[name].app.onInit = this[name]['onInit'];
    if (typeof this[name]['onSubmit'] !== 'undefined') {
      this[name].app.onSubmit = this[name]['onSubmit'];
    }
    this[name].app.__onInit( data );
  }
  
}