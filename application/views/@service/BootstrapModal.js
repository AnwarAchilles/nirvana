NV.component(
  class BootstrapModal {
    
    
    Nest = [];
    
    Element = [];
    
    inSelect = document.querySelector("body");

    inName = 'default';
    
    constructor( configure ) {
      if (configure.name) {
        this.inName = configure.name;
      }
      
      this.inSelect = document.querySelector(configure.core.constructor.selector+" "+NV.selector("modal", this.inName));

      this.Element[this.inName] = document.querySelector(configure.core.constructor.selector+" "+NV.selector("modal", this.inName));

      if (bootstrap.Modal.getInstance(this.inSelect)) {
        this.Nest[this.inName] = bootstrap.Modal.getInstance(this.inSelect);
      }else {
        if (this.inSelect!==null) {
          this.Nest[this.inName] = new bootstrap.Modal(this.inSelect, {});
        }
      }
    }

    create( name ) {

    }

    open( name ) {
      if (this.inSelect!==null) {
        if (name) {
          this.Nest[name].show();
        }else {
          this.Nest[this.inName].show();
        }
      }
    }

    openBefore( nameOrCallback, callback ) {
      if (this.inSelect!==null) {
        if (callback) {
          this.Element[nameOrCallback].addEventListener("show.bs.modal", callback );
        }else {
          this.inSelect.addEventListener("show.bs.modal", nameOrCallback );
        }
      }
    }

    openAfter( nameOrCallback, callback ) {
      if (this.inSelect!==null) {
        if (callback) {
          this.Element[nameOrCallback].addEventListener("shown.bs.modal", callback );
        }else {
          this.inSelect.addEventListener("shown.bs.modal", nameOrCallback );
        }
      }
    }

    close( name ) {
      if (this.inSelect!==null) {
        if (name) {
          this.Nest[name].hide();
        }else {
          this.Nest[this.inName].hide();
        }
      }
    }

    closeBefore( nameOrCallback, callback ) {
      if (this.inSelect!==null) {
        if (callback) {
          this.Element[nameOrCallback].addEventListener("hide.bs.modal", callback );
        }else {
          this.inSelect.addEventListener("hide.bs.modal", nameOrCallback );
        }
      }
    }

    closeAfter( nameOrCallback, callback ) {
      if (this.inSelect!==null) {
        if (callback) {
          this.Element[nameOrCallback].addEventListener("hidden.bs.modal", callback );
        }else {
          this.inSelect.addEventListener("hidden.bs.modal", nameOrCallback );
        }
      }
    }
    
  }
);
