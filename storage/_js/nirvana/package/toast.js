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