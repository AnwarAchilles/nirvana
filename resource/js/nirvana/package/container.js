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