/* 
 * FRAMEWORK NIRVANA
 * ---- ---- ---- ---- */
class Framework {
  __manifest = {};
  __override = {};
  __clone = {};
  // setup manifest
  constructor( Manifest ) {
    if (typeof Manifest!=='undefined') {
      Object.entries( Manifest ).forEach((Entries)=> {
        let [name, value] = Entries;
        // create onMethod
        this.__manifest[name] = value;
      });
    }
  }
  // todo building the framework
  build( nest, callback ) {
    let Frontend = callback( this.__manifest[nest] );
    // building override
    this.__override[nest]={};
    if (typeof Frontend.Overrides!=='undefined') {
      Object.entries( Frontend.Overrides ).forEach((Overrides)=> {
        let [newName, method] = Overrides;
        // create onMethod
        this.__override[nest][newName] = method;
      });
    }
    // building frontend
    if (typeof this[nest]!=='object') {
      this[nest] = {};
    }
    Object.entries( Frontend.Apps ).forEach((Apps)=> {
      let [newName, frontend] = Apps;
      // patching data
      frontend.base.repo = nest;
      frontend.base.url = this.__manifest[nest].url;
      frontend.base.target = "["+nest+"='"+frontend.base.name+"']";
      // patching onMethod
      if (typeof this.__override[nest][newName]!=='undefined') {
        Object.entries( this.__override[nest][newName] ).forEach((Overrides)=> {
          let [onName, method] = Overrides;
          frontend.override[onName] = method;
        });
      }
      // create frontend
      this[nest][newName] = frontend;
    });
    // instance framework
    this.instance( nest );
    // building clones
    if (typeof Frontend.Clones!=='undefined') {
      Object.entries( Frontend.Clones ).forEach((Clones)=> {
        let [from, clone] = Clones;
        let fromFrontend = this[nest][from];
        let app = [];
        let method = [];
        let property = [];
        // set parameters
        if (Array.isArray(clone.app)) {
          app = clone.app;
        }else {
          app.push(clone.app);
        }
        if (Array.isArray(clone.method)) {
          method = clone.method;
        }else {
          method.push(clone.method);
        }
        if (Array.isArray(clone.property)) {
          property = clone.property;
        }else {
          property.push(clone.property);
        }
        // create clone prototype method
        app.forEach((app)=> {
          let toFrontend = this[nest][app];
          if (method.length>0) {
            method.forEach((method)=> {
              toFrontend.__proto__[method] = fromFrontend.__proto__[method];
            });
          }
          if (property.length>0) {
            property.forEach((property)=> {
              toFrontend[property] = fromFrontend[property];
            });
          }
        });
      });
    }
  }
  // todo instance specified onMethod
  instance( nest ) {
    Object.entries( this[nest] ).forEach((Apps)=> {
      let [name, frontend] = Apps;
      // instance onStart
      if (typeof frontend[frontend.override["onInit"]]!=='undefined') {
        frontend.onInit();
      }
      // instance onSubmit
      if (typeof frontend[frontend.override["onSubmit"]]!=='undefined') {
        frontend.onSubmit();
      }
    });
  }
  // todo load frontend
  load( nest, frontend ) {
    let apps = this[nest];
    if (typeof frontend!=='undefined') {
      if (typeof apps[frontend]!=='undefined') {
        return apps[frontend];
      }else {
        console.log("Nirvana: ["+nest+"='"+frontend+"'] not exists");
        return {};
      }
    }else {
      return apps;
    }
  }
  // todo execute onInit
  start( nest, frontend, data=null ) {
    let baseFrontend = this[nest][frontend];
    if (typeof baseFrontend[baseFrontend.override["onStart"]]!=='undefined') {
      baseFrontend.onStart( data );
    }else {
      console.log("Nirvana: ["+nest+"='"+frontend+"'] not have "+baseFrontend.override["onStart"]+"() declared");
    }
  }
  // todo execute running
  run( nest, frontend, method, data=null ) {
    let baseFrontend = this[nest][frontend];
    baseFrontend[method]( data );
  }
}