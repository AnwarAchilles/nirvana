/*
 *
 * NIRVANA:STYLESHEET BUNDLE
 *
 datetime: 2023-10-02 21:09:48
 */

/* Source: https://__Project.test/Nirvana/resource/javascript/nirvana-3.5.js */ 
'use strict';

/**
 * The core class for Nirvana.
 *
 * @class NirvanaCore
 */
class NirvanaCore {
  /**
   * The version of Nirvana.
   *
   * @static
   * @type {number}
   * @memberof NirvanaCore
   */
  static _version = 3.5;

  /**
   * The configuration settings for the todo list environment.
   *
   * @static
   * @type {Map<string, string>}
   * @memberof NirvanaCore
   */
  static _configure = new Map([
    ["constant", "NV"],
    ["separator", "."],
  ]);

  /**
   * The components for the todo list.
   *
   * @static
   * @type {Map<any, any>}
   * @memberof NirvanaCore
   */
  static _component = new Map();

  /**
   * The providers for the todo list.
   *
   * @static
   * @type {Map<any, any>}
   * @memberof NirvanaCore
   */
  static _provider = new Map();

  /**
   * The services for the todo list.
   *
   * @static
   * @type {Map<any, any>}
   * @memberof NirvanaCore
   */
  static _service = new Map();

  /**
   * The stores for the todo list.
   *
   * @static
   * @type {Map<any, any>}
   * @memberof NirvanaCore
   */
  static _store = new Map();
}

/**
 * The `Nirvana` class represents the Nirvana JavaScript environment.
 * It provides various methods and features for configuring and managing the environment.
 * 
 * @class Nirvana
 */
class Nirvana {

  

  /**
   * Configures the environment.
   *
   * @param {Object} environment - An object containing key-value pairs to reconfigure the environment
   */
  static environment(environment) {
    // Configure provider if present in the environment object
    if (environment.provider) {
      NirvanaCore._provider = new Map(environment.provider);
    }
    
    // Configure service if present in the environment object
    if (environment.service) {
      NirvanaCore._service = new Map(environment.service);
    }
    
    // Set each provider as a property of the Core class
    NirvanaCore._provider.forEach((provider, name) => {
      this[name] = provider;
    });
    
    // Set the Core object as a property of the global window object
    window[NirvanaCore._configure.get("constant")] = this;
    
    // Log a message indicating the version of the application
    console.log("Nirvana " + NirvanaCore._version + " running ..");
  }


  /**
   * Generates a component and sets it in the Core.Nest registry.
   *
   * @param {string|object} nameOrComponent - The name of the component or an object representing the component.
   * @param {object} component - An optional object representing the nested component.
   * @return {undefined}
   */
  static component(nameOrComponent, component) {
    let nameComponent = "";
    let classComponent = {};
    
    // Check if a nested component is provided
    if (component) {
      nameComponent = `${nameOrComponent}${NirvanaCore._configure.get("separator")}${component.name}`;
      classComponent = component;
    } else {
      // If no nested component is provided, use the name of the component object
      nameComponent = nameOrComponent.name;
      classComponent = nameOrComponent;
    }

    // Check if the component is of type Nirvana and update the component and selector properties
    if (classComponent.__proto__.name === 'Nirvana') {
      classComponent.component = nameComponent;
      classComponent.selector = nameComponent.split(".").map(partName => this.selector('component', partName)).join(" ");
    }

    // Set the component in the Core.Nest registry
    NirvanaCore._component.set(nameComponent, classComponent);
    return this;
  }

    

  /**
   * Set a provider for a given name.
   *
   * @param {string} name - The name of the provider.
   * @param {any} provider - The provider to set.
   * @returns {Class} - The current class instance.
   */
  static provider(name, provider) {
    // Set the provider in the Core provider map
    NirvanaCore._provider.set(name, provider);

    // Set the provider as a property of the current class instance
    this[name] = provider;

    // Return the current class instance
    return this;
  }



  /**
   * Sets a service in the Core service map.
   *
   * @param {string|Object} nameOrService - The name of the service or an object representing the service.
   * @param {Object} [service] - An optional parameter that represents the service.
   * @returns {Object} - The modified Core object.
   */
  static service(nameOrService, service) {
    let nameService = "";
    let classService = {};

    // Check if a service object is provided
    if (service) {
      nameService = nameOrService;
      classService = service;
    } else {
      // Use the name and object properties of the nameOrService object
      nameService = nameOrService.name;
      classService = nameOrService;
    }

    // Set the service in the Core service map
    NirvanaCore._service.set(nameService, classService);

    return this;
  }



  /**
   * Stores data in the Core._store map.
   *
   * @param {string} name - The name of the data to be stored.
   * @param {object} data - The data to be stored.
   * @return {Map} The stored data.
   */
  static store(name, data) {
    if (NirvanaCore._store.has(name)) { // check if the data already exists
      if (data) { // check if new data is provided
        const lastData = NirvanaCore._store.get(name); // retrieve the existing data
        const newData = new Map(Object.entries(data)); // create a new map from the provided data
        NirvanaCore._store.set(name, new Map([...lastData, ...newData])); // merge the existing data with the new data and update the map
        return NirvanaCore._store.get(name); // return the updated data
      } else {
        return NirvanaCore._store.get(name); // if no new data is provided, return the existing data
      }
    } else {
      if (data) { // check if new data is provided
        NirvanaCore._store.set(name, new Map(Object.entries(data))); // create a new map from the provided data and store it
        return NirvanaCore._store.get(name); // return the stored data
      } else {
        NirvanaCore._store.set(name, new Map()); // if no new data is provided, store an empty map
        return NirvanaCore._store.get(name); // return the stored data
      }
    }
  }



  /**
   * Loads the specified nest.
   *
   * @param {string} name - The name of the nest to load.
   * @returns {Object} - A new instance of the loaded nest.
   */
  static load(name) {
    // Check if the nest is a component
    if (NirvanaCore._component.has(name)) {
      const component = NirvanaCore._component.get(name);
      return component;
    }

    // Check if the nest is a service
    if (NirvanaCore._service.has(name)) {
      const service = NirvanaCore._service.get(name);
      return service;
    }
  }



  /**
   * Runs the specified function.
   *
   * @param {string} name - The name of the function to run.
   * @returns {Object} - An instance of the function.
   */
  static run(name) {
    // Get the component from the Core's component map
    const component = NirvanaCore._component.get(name);

    // Create a new instance of the component
    return new component();
  }


    
  /**
   * Returns an array of elements that match the given prefix and name.
   *
   * @param {string} prefix - The prefix to match elements.
   * @param {string} [name=''] - The name to match elements (optional, default is an empty string).
   * @returns {Array} - An array of elements that match the given prefix and name.
   */
  static element(prefix, name = '') {
    // Use the selector method to generate a selector string
    const selector = this.selector(prefix, name);
    
    // Use document.querySelectorAll to find all elements that match the selector
    const elements = document.querySelectorAll(selector);
    
    // Return the array of elements
    return Array.from(elements);
  }


    
  /**
   * Generates a selector based on a prefix and name.
   *
   * @param {string} prefix - The prefix to add to the selector.
   * @param {string} [name=''] - The name to add to the selector. Default is an empty string.
   * @returns {string} The generated selector.
   */
  static selector(prefix, name = '') {
    // Get the lowercase constant value from the configuration
    const constant = NirvanaCore._configure.get("constant").toLowerCase();

    // Add the prefix to the selector if it is provided
    const prefixer = prefix ? `-${prefix}` : '';

    // Generate the selector based on the prefix and name
    const selector = name ? `[${constant}${prefixer}='${name}']` : `[${constant}${prefixer}]`;

    return selector;
  }

  




  /**
   * The selected element in the DOM.
   *
   * @type {Element}
   */
  element = document.querySelector("body");
  
  /**
   * Constructs a new instance of the class.
   */
  constructor() {
    /**
     * The selected element in the DOM.
     *
     * @type {Element}
     */
    this.element = element.querySelector( this.constructor.selector );
  }

  /**
   * Selects elements from the DOM based on the given selector.
   *
   * @param {string} selector - The CSS selector used to select elements.
   * @return {NodeList} - A list of elements that match the selector.
   */
  select( selector ) {
    // Use the querySelectorAll method to select elements from the DOM based on the given selector
    return document.querySelectorAll(selector);
  }

}


/* Source: https://__Project.test/Nirvana/resource/javascript/upup/upup.min.js */ 
//! UpUp
//! version : 1.1.0
//! author  : Tal Ater @TalAter
//! license : MIT
//! https://github.com/TalAter/UpUp
(function(o){"use strict";var e=navigator.serviceWorker;if(!e)return this.UpUp=null,o;var i={"service-worker-url":"upup.sw.min.js","registration-options":{}},s=!1,n="font-weight: bold; color: #00f;";this.UpUp={start:function(t){this.addSettings(t),e.register(i["service-worker-url"],i["registration-options"]).then(function(t){s&&console.log("Service worker registration successful with scope: %c"+t.scope,n),(t.installing||e.controller||t.active).postMessage({action:"set-settings",settings:i})}).catch(function(t){s&&console.log("Service worker registration failed: %c"+t,n)})},addSettings:function(e){"string"==typeof(e=e||{})&&(e={content:e}),["content","content-url","assets","service-worker-url","cache-version"].forEach(function(t){e[t]!==o&&(i[t]=e[t])}),e.scope!==o&&(i["registration-options"].scope=e.scope)},debug:function(t){s=!(0<arguments.length)||!!t}}}).call(this);
//# sourceMappingURL=upup.min.js.map

/* Source: https://__Project.test/Nirvana/application/views/layout.js */ 
/* 
 * NIRVANA JS - 3.4
 * @anwarachilles
 * 
 * 
 * */
Nirvana.environment({
  Base: {
    constant: "NV",
    provider: [],
  },
  Provider: [],
});

/* Source: https://__Project.test/Nirvana/application/views/welcome.js */ 

/**
 * Define a custom component named "Welcome" that extends the "Nirvana" class.
 * This component is used within the NV framework.
 *
 * @class Welcome
 * @extends Nirvana
 */
NV.component(
  class Welcome extends Nirvana {

    /**
     * Constructor for the "Welcome" component.
     * You can perform additional setup and logic here.
     */
    constructor() {
      super();

      // Add your initialization code here
    }
  }
);