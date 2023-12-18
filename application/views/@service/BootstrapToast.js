NV.component(
  class BootstrapToast {

    Nest = [];

    Sound = [
      {name:'SUCCESS', file:'/resource/sound/success.wav'},
      {name:'FAILED', file:'/resource/sound/failed.wav'},
    ];

    inSelect = document.querySelector("body");


    constructor( configure={} ) {
      if (this.inSelect.querySelectorAll("#toast-polite").length==0) {
        this.inSelect.append( this._elementPolite() );
      }
      if (this.inSelect.querySelectorAll("#toast-audio").length==0) {
        this.inSelect.append( this._elementAudio() );
      }
    }
    

    _element( htmlString ) {
      return new DOMParser().parseFromString(htmlString, 'text/html').querySelector("body>div");
    }

    _elementPolite() {
      return this._element(`
        <div 
          id="toast-polite" 
          aria-live="polite" 
          aria-atomic="true" 
          class="position-fixed d-flex flex-column-reverse top-0 end-0 p-3"
        ></div>
      `);
    }

    _elementAudio() {
      let soundList = "";
      for (let i=0; i<this.Sound.length; i++) {
        soundList += `<audio id="toast-audio-${this.Sound[i].name}" src="${this.Sound[i].file}"></audio>`;
      }
      return this._element(`<div id="toast-audio">${soundList}</div>`);
    }

    _elementToast( setup={} ) {
      let title = setup.title || "";
      let message = setup.message || "";
      let state = setup.state || "";
      let background = "bg-"+setup.background || "";

      let iconOrImage = "";
      if (setup.icon) {
        iconOrImage = `<i class="fa-light fa-lg fa fw fa-${setup.icon} : me-2"></i>`;
      }

      let element = ``;
      element += `<div class="toast fade font-400 ${background} mb-3" data-bs-delay="1500"  role="alert" aria-live="assertive" aria-atomic="true">`;
      if (setup.title) {
        element += `<div class="toast-header">
          ${iconOrImage}
          <strong class="me-auto">${title}</strong>
          <small>${state}</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>`;
      }
      if (setup.message) {
        element += `<div class="toast-body">
          ${message}
        </div>`;
      }
      element += `</div>`;

      return this._element(element);
    }
    
    open( setup={} ) {
      let ToastElement = this._elementToast( setup );
      let Toast =  new bootstrap.Toast(ToastElement, {});
      if (setup.sound) {
        this.inSelect.querySelector("#toast-audio-"+setup.sound.toUpperCase()).currentTime = 0;
        this.inSelect.querySelector("#toast-audio-"+setup.sound.toUpperCase()).play();
      }
      this.inSelect.querySelector("#toast-polite").append( ToastElement );
      Toast.show();
    }

  }
);