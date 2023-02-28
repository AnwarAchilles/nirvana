
/* 
 * PACKAGE CONTAINER for containering element
 * ---- ---- ---- ---- */
class PackageContainer {
  // private
  __frontend = {};
  __stack = {};
  // main
  constructor( Frontend, Table ) {
    this.__frontend = Frontend;
  }
  stack( nest ) {
    this.__stack[nest]=0;
  }
  patch( nest ) {
    let target = $("["+this.__frontend.base.repo+"-Container='"+nest+"']");
    let content = $($.parseHTML("<div>"+target.html()+"</div>"));
    return content;
  }
  set( nest, Nametarget, nestPatch ) {
    let target = $(this.__frontend.base.target+" "+Nametarget);
    if (typeof this.__stack[nest]!=='undefined') {
      this.__stack[nest] += 1;
      nestPatch.attr(this.__frontend.base.repo+"-"+nest+"-Container", this.__stack[nest]);
      let reparse = $("<div></div>").html( nestPatch );
      nestPatch = $($.parseHTML( reparse.html().replaceAll("{stack}", this.__stack[nest]) ));
      target.append( nestPatch );
    }else {
      nestPatch.attr(this.__frontend.base.repo+"-"+nest+"-Container", "");
      target.html( nestPatch );
    }
  }
  del( nest, Nametarget, index ) {
    let target = $(this.__frontend.base.target+" "+Nametarget);
    if (typeof index!=='undefined') {
      target.find("["+this.__frontend.base.repo+"-"+nest+"-Container='"+index+"']").remove();
    }else {
      target.find("["+this.__frontend.base.repo+"-"+nest+"-Container").remove();
    }
  }
}

/* 
 * PACKAGE TABLE Based datatables
 * ---- ---- ---- ---- */
class PackageTable {
  // private
  __frontend={};
  __table={};
  // playground
  columns=[];
  // main
  constructor( Frontend, Table ) {
    this.__frontend = Frontend;
  }
  // build table nest based
  build( nest, options ) {
    // set parameters
    const params = {};
    params.columns = options.cols;
    params.type = options.http[0];
    params.url = options.http[1]+"/datatable";
    params.dataSrc = options.patch.bind( this );
    params.order = options.order;
    // render table
    this.__table[nest] = $(".datatables").DataTable({
      serverSide: true,
      columns: params.columns,
      order: params.order,
      ajax: {
        url: this.__frontend.base.url+"/"+params.url,
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
  // reload table
  reload( nest ) {
    this.__table[nest].ajax.reload();
  }
}
/* 
 * PACKAGE MODAL Based bootstrap 
 * ---- ---- ---- ---- */
class PackageModal {
  // setup
  __frontend = {};
  __modal = {};
  __target = "";
  httpReady=false;
  // playground
  constructor( Frontend, Modal ) {
    this.__frontend = Frontend;
    this.initialize();
  }
  initialize() {
    let target = $("["+this.__frontend.base.repo+"-Modal='"+this.__frontend.base.name+"']");
    if (target.length!==0) {
      this.__target = target;
      this.__modal = new bootstrap.Modal(target, { keyboard: false });
    }
  }
  http( api, directory ) {
    this.httpReady = true;
    api = api+"/modal";
    let params = directory;
    if (typeof params!=='undefined') {
      params = params+"/";
    }else {
      params = '';
    }
    params = { modal: params+this.__frontend.base.repo+"/Modal/"+this.__frontend.base.name };
    let element = this.__frontend.api("POST", api, params, false).responseJSON;
    let modalParse = $($.parseHTML(element.data[0]));
    $("["+this.__frontend.base.repo+"-Modal='http']").html( modalParse );
    this.initialize();
  }
  // patch data
  patch( name, data ) {
    let target = this.__target.find("[Modal-Patch='"+name+"']");
    if (typeof data!=='undefined') {
      target.html( data );
    }else {
      return target;
    }
  }
  // show modal
  show() {
    this.__modal.show();
  }
  // hide modal
  hide() {
    if (this.httpReady !== false) {
      $( this.__frontend.base.target ).remove();
    }
    this.__modal.hide();
  }
  // trigger
  trigger( beforeAfter, showHide, callback ) {
    let tail = '';
    if (beforeAfter=='before') {
      if (showHide=='show') {
        tail = "show";
      }else {
        tail = "hide";
      }
    }else {
      if (showHide=='show') {
        tail = "shown";
      }else {
        tail = "hidden";
      }
    }
    this.__target.on(tail+".bs.modal", callback);
  }
}
/* 
 * PACKAGE TOAST Based bootstrap 
 * ---- ---- ---- ---- */
class PackageToast {
  __frontend = {};
  __toast = {};
  __target = "";
  __container = "";
  useContainer = false;
  constructor( Frontend, Toast ) {
    this.__frontend = Frontend;

    this.initialize();
  }
  initialize() {
    let target = $("["+this.__frontend.base.repo+"-Toast='"+this.__frontend.base.name+"']");
    if (target.length!==0) {
      this.__target = target;
      this.__toast = new bootstrap.Toast(target, {});
    }
  }
  container( name ) {
    this.useContainer = true;
    if (typeof name!=='undefined') {
      this.__container = $("[Container-Toast='"+name+"']").find(".toast-container");
    }else {
      this.__container = $(".toast-container");
    }
  }
  use( name ) {
    let target = $("["+this.__frontend.base.repo+"-Toast='"+name+"']");
    if (target.length!==0) {
      this.__target = target;
      this.__toast = new bootstrap.Toast(target, {});
    }
  }
  patch(name, data) {
    let target = this.__target.find("[Toast-Patch='"+name+"']");
    target.html( data );
  }
  // show modal
  show() {
    if (this.useContainer==true) {
      let container = this.__container;
      let toast = $.parseHTML(this.__target[0].outerHTML);
      toast[0].removeAttribute("Product-Toast");
      container.append( toast );
      this.__toast = new bootstrap.Toast( $(toast), {});
      $(toast).on("hidden.bs.toast", ()=> {
        $(toast).remove();
      });
    }
    this.__toast.show();
  }
  // hide modal
  hide() {
    this.__toast.hide();
  }
  // trigger
  trigger( beforeAfter, showHide, callback ) {
    let tail = '';
    if (beforeAfter=='before') {
      if (showHide=='show') {
        tail = "show";
      }else {
        tail = "hide";
      }
    }else {
      if (showHide=='show') {
        tail = "shown";
      }else {
        tail = "hidden";
      }
    }
    this.__target.on(tail+".bs.toast", callback);
  }
}
/* 
 * PACKAGE FORM
 * ---- ---- ---- ---- */
class PackageForm {
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

    this.initialize();
  }
  // intialize all form controls
  initialize() {
    let forms = $(this.__frontend.base.target).find("["+this.__frontend.base.repo+"-Form]");
    let formsTemporary = {};
    let __form = {};
    let __data = {};
    let __value = {};

    forms.each( (i, form)=> {
      let formBaseName = $(form).attr(this.__frontend.base.repo+"-Form");
      let formControls = $(form).find("[name]");
      formsTemporary[formBaseName] = [];
      formControls.each( (i, controls)=> {
        let controlBaseName = $(controls).attr("name");
        formsTemporary[formBaseName].push(controlBaseName);
      });
    });

    Object.entries( formsTemporary ).forEach((entries)=> {
      let [formName, controlsName] = entries;
      let form = $(this.__frontend.base.target).find("["+this.__frontend.base.repo+"-Form='"+formName+"']");
      
      if ( form.length>1 ) {
        __form[formName] = [];
        __data[formName] = [];
        __value[formName] = [];
        $(form).each( (i, form)=> {
          let formControls = $(form).find("[name]");
          __form[formName][i] = {};
          __data[formName][i] = {};
          __value[formName][i] = {};
          formControls.each( (ii, controls)=> {
            let controlBaseName = $(controls).attr("name");
            __form[formName][i][controlBaseName] = $(controls);
          });
        });
      }else {
        __form[formName] = {};
        __data[formName] = {};
        __value[formName] = {};
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
    this.datas = __data;
    this.values = __value;
  }
  // builder
  build( nest, indexOrPatcher, patcher=null ) {
    if (typeof indexOrPatcher=='object') {
      this.datas[nest] = new FormData();
      this.values[nest] = {};
      Object.entries( indexOrPatcher ).forEach( entries=> {
        const [controls, value] = entries;
        if (value!=="") {
          this.datas[nest].append(controls, value);
          this.values[nest][controls] = value;
        }
      });
    }else {
      this.datas[nest][indexOrPatcher] = new FormData();
      this.values[nest][indexOrPatcher] = {};
      Object.entries( patcher ).forEach( entries=> {
        const [controls, value] = entries;
        if (value!=="") {
          this.datas[nest][indexOrPatcher].append(controls, value);
          this.values[nest][indexOrPatcher][controls] = value;
        }
      });
    }
  }
  // patching data
  patch( nest, indexOrInput=false, input=false) {
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
  // looping form
  loop( nest, callback ) {
    if (typeof this.__form[nest]!=='undefined') {
      this.__form[nest].forEach( callback );
    }
  }
  // is nest exist
  exists( nest ) {
    if (typeof this.__form[nest]!=="undefined") {
      return true;
    }else {
      return false;
    }
  }
}
/* 
 * PACKAGE EDITOR Based ckeditor5 
 * ---- ---- ---- ---- */
class PackageEditor {
  // main
  __frontend = {};
  __editor = {};
  // constructor
  constructor( Frontend, Editor ) {
    this.__frontend = Frontend;
    this.__editor = Editor;
  }
  // build
  build( nest, control ) {
    if (typeof this.__editor[nest] !== 'object') {
      let x = ClassicEditor.create( control, {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'numberedList', 'bulletedList', 'blockQuote', '|', 'insertTable', 'undo', 'redo' ]
      }).then((newEditor)=> {
        this.__editor[nest] = newEditor;
      });
    }
  }
  // patch from nest
  patch( nest ) {
    if (typeof this.__editor[nest] == 'object') {
      return this.__editor[nest];
    }
  }
}
/* 
 * PACKAGE SELECT Based select2 
 * ---- ---- ---- ---- */
class PackageSelect {
  // options
  __frontend = {};
  __select = {};
  __form = {};
  // constructor
  constructor( Frontend, Select ) {
    this.__frontend = Frontend;
    this.__form = Frontend.form;
    this.__select = Select;
  }
  // builder nest
  build( nest, control, options ) {
    let data=[];

    if (typeof options.data.responseJSON!=='undefined') {
      data = options.data.responseJSON.data;
    }else {
      data = options.data;
    }

    if (typeof options.patch!=='undefined') {
      this.__select[nest] = [];
      let patcher = options.patch.bind( this );
      data.forEach( data => {
        const select = patcher( data );
        this.__select[nest].push({ id:select[0], text:select[1] });
      });
    }

    let dropdownParent = "["+this.__frontend.base.repo+"-Modal='"+this.__frontend.base.name+"']";
    control.select2({ theme:"bootstrap-5", data:this.__select[nest], dropdownParent:$(dropdownParent) });
  }
  patch( nest, id ) {
    let output = "";
    if (typeof id!=='undefined') {
      this.__select[nest].forEach(data=> {
        if (data.id == id) {
          output = data.text;
        }
      });
    }else {
      output = this.__select[nest];
    }
    return output;
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
 * PACKAGE LOADER
 * ---- ---- ---- ---- */
class PackageLoader {
  frontend={};
  // main
  constructor( Frontend ) {
    this.frontend= Frontend;
  }
  // package loader
  form( option={}, name ) {
    this.frontend[name] = new PackageForm( this.frontend, option );
  }
  modal( option={}, name ) {
    this.frontend[name] = new PackageModal( this.frontend, option );
  }
  toast( option={}, name ) {
    this.frontend[name] = new PackageToast( this.frontend, option );
  }
  table( option={}, name ) {
    this.frontend[name] = new PackageTable( this.frontend, option );
  }
  editor( option={}, name ) {
    this.frontend[name] = new PackageEditor( this.frontend, option );
  }
  select( option={}, name ) {
    this.frontend[name] = new PackageSelect( this.frontend, option );
  }
  container( option={}, name ) {
    this.frontend[name] = new PackageContainer( this.frontend, option );
  }
}
/* 
 * FRONTEND NIRVANA
 * ---- ---- ---- ---- */
class Frontend {
  // base
  base = {
    name: this.constructor.name,
    prefix: "frontend",
  };
  // todo override onEvent
  override = {
    onInit: "init",
    onStart: "start",
    onSubmit: "submit",
  };
  // onEvent
  onInit() {
    this[ this.override["onInit"] ]();
  }
  onStart( data=null ) {
    this[ this.override["onStart"] ]( data );
  }
  onSubmit() {
    $( this.base.target+" form" ).on("submit", (event)=> {
      this[ this.override["onSubmit"] ]();
      event.preventDefault();
    });
  }
  // todo load package
  load( core, rename ) {
    let load = new PackageLoader( this );
    let name = rename || core;
    load[core]( {}, name );
  }
  // xml http request
  xhttp(type, url, data=null, callback=null, sync=false) {
    let ajax = {};
    ajax.type = type;
    ajax.url = url;
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
  // rest api http
  api( type, url, data, callback, sync) {
    url = this.base.url+"/api/"+url;
    return this.xhttp(type, url, data, callback, sync);
  }
  // patching data
  container( name, find ) {
    let container = $("["+this.base.repo+"='"+this.base.name+"']").find("["+this.base.repo+"-Container='"+name+"']");
    if (typeof find!=='undefined') {
      container = container.find( find );
    }
    return container;
  }
  patch(name, from) {
    if (typeof from!=='undefined') {
      return from.find("["+this.base.repo+"-Patch='"+name+"']");
    }else {
      return $("["+this.base.repo+"='"+this.base.name+"']").find("["+this.base.repo+"-Patch='"+name+"']");
    }
  }
  isMobile() {
    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
      // true for mobile device
      return true;
    }else{
      // false for not mobile device
      return false;
    }
  }
  redirect( url ) {
    window.location.assign(url);
  }
}
/* 
 * FRAMEWORK NIRVANA
 * ---- ---- ---- ---- */
class Framework {
  __manifest = {};
  __override = {};
  __clone = {};
  // setup manifest
  constructor( Manifest ) {
    if (typeof Manifest!=='undefined') {
      Object.entries( Manifest ).forEach((Entries)=> {
        let [name, value] = Entries;
        // create onMethod
        this.__manifest[name] = value;
      });
    }
  }
  // todo building the framework
  build( nest, callback ) {
    let Frontend = callback( this.__manifest[nest] );
    // building override
    this.__override[nest]={};
    if (typeof Frontend.Overrides!=='undefined') {
      Object.entries( Frontend.Overrides ).forEach((Overrides)=> {
        let [newName, method] = Overrides;
        // create onMethod
        this.__override[nest][newName] = method;
      });
    }
    // building frontend
    this[nest] = {};
    Object.entries( Frontend.Apps ).forEach((Apps)=> {
      let [newName, frontend] = Apps;
      // patching data
      frontend.base.repo = nest;
      frontend.base.url = this.__manifest[nest].url;
      frontend.base.target = "["+nest+"='"+frontend.base.name+"']";
      // patching onMethod
      if (typeof this.__override[nest][newName]!=='undefined') {
        Object.entries( this.__override[nest][newName] ).forEach((Overrides)=> {
          let [onName, method] = Overrides;
          frontend.override[onName] = method;
        });
      }
      // create frontend
      this[nest][newName] = frontend;
    });
    // instance framework
    this.instance( nest );
    // building clones
    if (typeof Frontend.Clones!=='undefined') {
      Object.entries( Frontend.Clones ).forEach((Clones)=> {
        let [from, clone] = Clones;
        let fromFrontend = this[nest][from];
        let app = [];
        let method = [];
        let property = [];
        // set parameters
        if (Array.isArray(clone.app)) {
          app = clone.app;
        }else {
          app.push(clone.app);
        }
        if (Array.isArray(clone.method)) {
          method = clone.method;
        }else {
          method.push(clone.method);
        }
        if (Array.isArray(clone.property)) {
          property = clone.property;
        }else {
          property.push(clone.property);
        }
        // create clone prototype method
        app.forEach((app)=> {
          let toFrontend = this[nest][app];
          if (method.length>0) {
            method.forEach((method)=> {
              toFrontend.__proto__[method] = fromFrontend.__proto__[method];
            });
          }
          if (property.length>0) {
            property.forEach((property)=> {
              toFrontend[property] = fromFrontend[property];
            });
          }
        });
      });
    }
  }
  // todo instance specified onMethod
  instance( nest ) {
    Object.entries( this[nest] ).forEach((Apps)=> {
      let [name, frontend] = Apps;
      // instance onStart
      if (typeof frontend[frontend.override["onInit"]]!=='undefined') {
        frontend.onInit();
      }
      // instance onSubmit
      if (typeof frontend[frontend.override["onSubmit"]]!=='undefined') {
        frontend.onSubmit();
      }
    });
  }
  // todo load frontend
  load( nest, frontend ) {
    let apps = this[nest];
    if (typeof frontend!=='undefined') {
      if (typeof apps[frontend]!=='undefined') {
        return apps[frontend];
      }else {
        console.log("Nirvana: ["+nest+"='"+frontend+"'] not exists");
        return {};
      }
    }else {
      return apps;
    }
  }
  // todo execute onInit
  start( nest, frontend, data=null ) {
    let baseFrontend = this[nest][frontend];
    if (typeof baseFrontend[baseFrontend.override["onStart"]]!=='undefined') {
      baseFrontend.onStart( data );
    }else {
      console.log("Nirvana: ["+nest+"='"+frontend+"'] not have "+baseFrontend.override["onStart"]+"() declared");
    }
  }
  // todo execute running
  run( nest, frontend, method, data=null ) {
    let baseFrontend = this[nest][frontend];
    baseFrontend[method]( data );
  }
}