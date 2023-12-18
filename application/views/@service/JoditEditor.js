NV.component(
  class JoditEditor {

    Nest = [];

    Configure = {
      "uploader": {
        "insertImageAsBase64URI": true
      }
    };

    inSelect = document.querySelector("body");

    inName = "";

    constructor( configure={} ) {
      this.inName = configure.name || this.inName;

      this.inSelect = document.querySelector(configure.core.constructor.selector+" "+NV.selector("editor", this.inName));

      if (configure.name) {
        if (!this.Nest[this.inName]) {
          this.Nest[this.inName] = Jodit.make(this.inSelect, this.Configure);
        }
      }
    }

    create( name, configure={} ) {
      this.inName = name || this.inName;
      this.Configure = Object.assign(this.Configure, configure );
      
      if (!this.Nest[this.inName]) {
        this.Nest[this.inName] = Jodit.make(this.inSelect, this.Configure);
      }else {
        this.Nest[this.inName] = Jodit.make(this.inSelect, this.Configure);
      }
      return this;
    }

    select(name) {
      this.inName = name || this.inName;

      return this.Nest[this.inName];
    }
  }
);