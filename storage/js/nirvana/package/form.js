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