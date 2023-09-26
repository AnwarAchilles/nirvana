
/* Source: https://__Project.test/Nirvana3/resource/javascript/nirvana-3.5.js */ 
'use strict';class NirvanaCore{static _version=3.5;static _configure=new Map([["constant","NV"],["separator","."],]);static _component=new Map();static _provider=new Map();static _service=new Map();static _store=new Map()}class Nirvana{static environment(environment){if(environment.provider){NirvanaCore._provider=new Map(environment.provider)}if(environment.service){NirvanaCore._service=new Map(environment.service)}NirvanaCore._provider.forEach((provider,name)=>{this[name]=provider});window[NirvanaCore._configure.get("constant")]=this;console.log("Nirvana "+NirvanaCore._version+" running ..")}static component(nameOrComponent,component){let nameComponent="";let classComponent={};if(component){nameComponent=`${nameOrComponent}${NirvanaCore._configure.get("separator")}${component.name}`;classComponent=component}else{nameComponent=nameOrComponent.name;classComponent=nameOrComponent}if(classComponent.__proto__.name==='Nirvana'){classComponent.component=nameComponent;classComponent.selector=nameComponent.split(".").map(partName=>this.selector('component',partName)).join(" ")}NirvanaCore._component.set(nameComponent,classComponent);return this}static provider(name,provider){NirvanaCore._provider.set(name,provider);this[name]=provider;return this}static service(nameOrService,service){let nameService="";let classService={};if(service){nameService=nameOrService;classService=service}else{nameService=nameOrService.name;classService=nameOrService}NirvanaCore._service.set(nameService,classService);return this}static store(name,data){if(NirvanaCore._store.has(name)){if(data){const lastData=NirvanaCore._store.get(name);const newData=new Map(Object.entries(data));NirvanaCore._store.set(name,new Map([...lastData,...newData]));return NirvanaCore._store.get(name)}else{return NirvanaCore._store.get(name)}}else{if(data){NirvanaCore._store.set(name,new Map(Object.entries(data)));return NirvanaCore._store.get(name)}else{NirvanaCore._store.set(name,new Map());return NirvanaCore._store.get(name)}}}static load(name){if(NirvanaCore._component.has(name)){const component=NirvanaCore._component.get(name);return component}if(NirvanaCore._service.has(name)){const service=NirvanaCore._service.get(name);return service}}static run(name){const component=NirvanaCore._component.get(name);return new component()}static element(prefix,name=''){const selector=this.selector(prefix,name);const elements=document.querySelectorAll(selector);return Array.from(elements)}static selector(prefix,name=''){const constant=NirvanaCore._configure.get("constant").toLowerCase();const prefixer=prefix?`-${prefix}`:'';const selector=name?`[${constant}${prefixer}='${name}']`:`[${constant}${prefixer}]`;return selector}element=document.querySelector("body");constructor(){this.element=element.querySelector(this.constructor.selector)}select(selector){return document.querySelectorAll(selector)}}

/* Source: https://__Project.test/Nirvana3/resource/javascript/upup/upup.min.js */ 
(function(o){"use strict";var e=navigator.serviceWorker;if(!e)return this.UpUp=null,o;var i={"service-worker-url":"upup.sw.min.js","registration-options":{}},s=!1,n="font-weight: bold; color: #00f;";this.UpUp={start:function(t){this.addSettings(t),e.register(i["service-worker-url"],i["registration-options"]).then(function(t){s&&console.log("Service worker registration successful with scope: %c"+t.scope,n),(t.installing||e.controller||t.active).postMessage({action:"set-settings",settings:i})}).catch(function(t){s&&console.log("Service worker registration failed: %c"+t,n)})},addSettings:function(e){"string"==typeof(e=e||{})&&(e={content:e}),["content","content-url","assets","service-worker-url","cache-version"].forEach(function(t){e[t]!==o&&(i[t]=e[t])}),e.scope!==o&&(i["registration-options"].scope=e.scope)},debug:function(t){s=!(0<arguments.length)||!!t}}}).call(this);

/* Source: https://__Project.test/Nirvana3/application/views//layout.js */ 
Nirvana.environment({Base:{constant:"NV",provider:[],},Provider:[],});

/* Source: https://__Project.test/Nirvana3/application/views//welcome.js */ 
NV.component(class Welcome extends Nirvana{constructor(){super()}});