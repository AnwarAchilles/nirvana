NV.component(
  class InputDebounce {

    lastWord = '';
    
    inTiming;

    inDelay = 400;

    constructor() {}

    execute( element, callback ) {
      clearTimeout(this.inTiming);
      this.lastWord = element.value;
      this.inTiming = setTimeout(() => {
        if (this.lastWord === element.value) {
          callback(element.value);
        }
      }, this.inDelay);
    }
  }
  
)