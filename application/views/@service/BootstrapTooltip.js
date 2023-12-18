NV.component(
  class BootstrapTooltip {

    Nest = [];

    inSelect = document.querySelector("body");


    constructor( configure={} ) {
      this.inSelect = document.querySelector(configure.core.constructor.selector);
    }

    create(name, title) {
      let element = this.inSelect.querySelector(NV.selector("tooltip", name));
      if (bootstrap.Tooltip.getInstance(element)) {
        this.Nest[name] = bootstrap.Tooltip.getInstance(element);
      }else {
        this.Nest[name] = new bootstrap.Tooltip(element, {
          title: title,
          placement: 'auto',
          popperConfig: function (defaultBsPopperConfig) {
            // var newPopperConfig = {...}
            // use defaultBsPopperConfig if needed...
            // return newPopperConfig
          }
        });
      }
    }




  }
);