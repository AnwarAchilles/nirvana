/* AUTH Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Auth", ( Manifest )=> {

  /* MAIN Frontend */
  class Base extends CyruzFrontend {
    // todo show password
    passwordShow() {
      let type = this.form.patch("user", "password").attr("type");
      if (type=="text") {
        this.form.patch("user", "password").attr("type", "password");
      }else {
        this.form.patch("user", "password").attr("type", "text");
      }
    }
    // todo build toast
    buildToast( opt, response ) {
      this.toast.container("Default");
      this.toast.use("Default");

      if (opt=='success') {
        this.toast.patch("icon", '<i class="fa-duotone fa-2x fa-check-circle fa-beat : text-success me-3"></i>');
        this.toast.patch("text", '<strong class="me-auto">'+this.base.name+' '+response+'</strong>');
      }
      if (opt=='failed') {
        this.toast.patch("icon", '<i class="fa-duotone fa-2x fa-times-circle fa-beat : text-danger me-3"></i>');
        this.toast.patch("text", '<strong class="me-auto">'+this.base.name+' '+response+'</strong>');
      }

      this.toast.show();
    }
  }

  /* LOGIN Frontend */
  class Login extends CyruzFrontend {
    init() {
      this.load("form");
      this.load("toast");
      this.buttonSubmit();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.form.build("user", {
          email: this.form.patch("user", "email").val(),
          password: this.form.patch("user", "password").val(),
        });
  
        this.api("POST", "auth/login", this.form.value("user"), resp=> {
          if (resp.message=="user-verified") {
            window.location.assign( base_url+"/cyruz" );
          }
          if (resp.message=="user-not-found") {
            this.buildToast("failed", "Credentials Not Avaliable");
          }
          if (resp.message=="password-wrong") {
            this.buildToast("failed", "Wrong Password");
          }
          this.buttonSubmit("enable");
        });
      });
    }
  }

  /* LOADER Frontend */
  return {
    Apps: {
      Base:   new Base,
      Login:  new Login,
    },
    Clones: {
      Base: {
        app:     [ "Login" ],
        method:  [ "buildToast", "passwordShow" ]
      }
    },
  }
});