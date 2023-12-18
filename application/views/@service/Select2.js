NV.component(
  class Select2 {
    
    Nest = {};

    Configure = {
      theme: 'bootstrap-5',
      placeholder: 'Select',
      allowClear: true,
    };

    inSelect = document.querySelector("body");

    inSelector = [];

    inName = "";

    useModal = false;

    constructor( configure={} ) {
      this.Configure.dropdownParent = configure.parent || this.inSelect;
      this.Configure.placeholder = configure.placeholder || 'Select';

      this.useModal = configure.useModal || false;

      this.inSelector.push(configure.core.constructor.selector);
    }
    
    image( callback ) {
      return callback;
    }

    select( name ) {
      return this.Nest[name];
    }

    create( name, reconfigure={} ) {
      this.inName = name || 'default';
      this.Configure = Object.assign(this.Configure, reconfigure );
      this.inSelector.push(NV.selector("select", this.inName));
      this.inSelect = document.querySelector( this.inSelector.join(" ") );
      this.inSelector.pop();
      
      this.Nest[this.inName] = $(this.inSelect).select2(this.Configure);

      if (this.useModal) {
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
      }

      $(this.inSelect).val(null).trigger("change");
    }

    

  }
)