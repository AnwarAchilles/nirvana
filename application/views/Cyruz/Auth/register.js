/* AUTH Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Auth", (Manifest)=> {

  /* MAIN Frontend */
  class Base extends Frontend {
    // todo show password
    passwordShow( selector ) {
      let type = this.form.patch("user", selector).attr("type");
      if (type=="text") {
        this.form.patch("user", selector).attr("type", "password");
      }else {
        this.form.patch("user", selector).attr("type", "text");
      }
    }
    // todo build toast
    buildToast( opt, response ) {
      this.toast.container = true;
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
  class Login extends Frontend {
    init() {
      this.load("form");
      this.load("toast");
    }
    submit() {
      this.form.build("user", {
        name: this.form.patch("user", "name").val(),
        email: this.form.patch("user", "email").val(),
        password: this.form.patch("user", "password").val(),
        id_role: 2,
      });

      if (this.form.patch("user", "password").val()==this.form.patch("user", "password").val()) {
        this.api("POST", "user", this.form.value("user"), resp=> {
          this.api("POST", "toasted", {header:"New Player registered", message:this.form.patch("user", "name").val()+" has been registered to the apps."}, resp=> {
            window.location.assign( base_url+"/cyruz" );
          });
        });
      }else {
        this.buildToast("failed", "Password Not same");
      }
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
    }
  }
});