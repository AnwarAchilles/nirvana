NV.component(

  class RequestHttp {

    url = '';

    _header = {};

    _configure = {};

    inUrl = "";

    constructor( configure ) {
      if (configure.url) {
        if (NV.layout) {
          this.inUrl = NV.layout.load('base_url');
        }else {
          this.inUrl = {};
        }
        this.inUrl = this.inUrl+"/"+configure.url;
      }
      if (configure.token) {
        this.token(configure.token);
      }
      if (configure.header) {
        Object.entries(configure.header).forEach(Entry => {
          const [key, value] = Entry;
          this.header(key, value);
        });
      }

      return this;
    }

    configurations() {
      this.configure('headers', this._header);
      return this._configure;
    }

    token( value ) {
      this.header("Authorization", "bearer "+value);
      return this;
    }

    configure(name, value) {
      if (value) {
        this._configure[name] = value;
      }
      return this;
    }

    header(name, value) {
      if (value) {
        this._header[name] = value;
      }
      return this;
    }

    async initialize( url ) {
      let inUrl = '';

      if (typeof url!=='undefined') {
        inUrl = this.inUrl+"/"+url;
      }

      return fetch(inUrl, this.configurations()).then(proms => {
        const contentType = proms.headers.get("content-type");
        if (contentType && contentType.includes("application/json")) {
          return proms.json().then(json => {
            return {
              method: this._configure.method || "GET",
              status: proms.status,
              response: json,
            };
          });
        }
        if (contentType && contentType.includes("text/html")) {
          return proms.text().then(text => {
            return {
              method: this._configure.method || "GET",
              status: proms.status,
              response: text,
            };
          });
        }
      });
    }

    async result( url ) {
      if (this._configure.body) {
        delete this._configure.body;
      }
      this.configure("method", "GET");
      return await this.initialize( url );
    }

    async create( urlOrBody, body ) {
      this.configure("method", "POST");
      if (body) {
        this.configure("body", body);
        return await this.initialize(urlOrBody);
      }else {
        this.configure("body", urlOrBody);
        return await this.initialize('');
      }
    }

    async update( urlOrBody, body ) {
      this.configure("method", "PUT");
      if (body) {
        this.configure("body", body);
        return await this.initialize(urlOrBody);
      }else {
        this.configure("body", urlOrBody);
        return await this.initialize('');
      }
    }

    async delete( url ) {
      this.configure("method", "DELETE");
      return await this.initialize( url );
    }
    

    

  }
)