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
    let load = new NirvanaLoader( this );
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