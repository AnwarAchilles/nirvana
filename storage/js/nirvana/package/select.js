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
    control.select2({ 
      theme:"bootstrap-5", 
      placeholder: options.placeholders,
      allowClear: true,
      data:this.__select[nest], 
      dropdownParent:$(dropdownParent) 
    });
  }
  // patch data
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