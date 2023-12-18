NV.component(
  class FormBuilder {

    inSelect = document.querySelector("body");

    inForm = [];

    inName = 'default';

    constructor( configure ) {
      if (configure.name) {
        if (Array.isArray(configure.name)) {

        }else {
          this.inName = configure.name;
        }
      }
      this.inSelect = document.querySelectorAll(configure.core.constructor.selector+" "+NV.selector("form", this.inName));
     
      if (this.inSelect.length==1) {
        if (this.inSelect!==null) {
          this.inForm[this.inName] = new Map();
        }
      }else {  
        
      }
    }

    auto() {
      // under development
    }

    use( formName ) {
      // under development
    }

    builder( dataset ) {
      if (!Array.isArray(this.inForm[this.inName])) {
        if (Array.isArray(dataset)) {
          let build = new Map();
          dataset.forEach(entry => {
            if (entry[1]!=='') {
              build.set(entry[0], entry[1]);
            }
            if (entry[2]==true) {
              build.set(entry[0], entry[1]);
            }
          });
          this.inForm[this.inName] = build;
        }else {
          Object.entries( dataset ).forEach( Entry=> {
            const [key, value] = Entry;
            this.inForm[this.inName].set(key, value);
          });
        }
      }else {
        
      }
    }

    select( selector, index=0 ) {
      if (typeof this.inSelect[index]!=='undefined') {
        return this.inSelect[index].querySelector("[name="+selector+"]");
      }
    }

    async result( type='all' ) {
      if (!Array.isArray(this.inForm[this.inName])) {
        const form = this.inForm[this.inName];
        const result = {
          'data': await this._objectdata( form ),
          'query': await this._querydata( form ),
          'form': await this._formdata( form ),
          'json': await this._jsondata( form ),
        };
        if (type=='all') {
          return result;
        }else {
          return result[type];
        }
      }else {

      }
    }

    async _objectdata( forms ) {
      const object = {};
      forms.forEach((value, key) => {
        object[key] = value;
      });
      return object;
    }
    async _formdata( forms ) {
      const formdata = new FormData();
      forms.forEach((value, key) => {
        formdata.append(key, value);
      });
      return formdata;
    }
    async _querydata( forms ) {
      const query = [];
      forms.forEach((value, key) => {
        query.push(key+"="+value);
      });
      return query.join("&");
    }
    async _jsondata( forms ) {
      return JSON.stringify( await this._objectdata(forms) );
    }

  }
)