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