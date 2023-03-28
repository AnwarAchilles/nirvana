/* USER Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "User", ( Manifest ) => {

  /* USER:BASE Frontend */
  class Base extends Frontend {
    init() {
      this.load("table");
      this.table.build("user", {
        cols: [
          {data:"button", width:"5%"},
          {data:"no"},
          {data:"name"},
          {data:"email"},
        ],
        http: ["GET", "api/user"],
        patch: ( data )=> {
          if (data.name == "Admin") {
            data.button = "";
          }
        }
      });
    }
    // todo build form
    buildForm() {
      this.form.build("user", {
        name: this.form.patch("user", "name").val(),
        email: this.form.patch("user", "email").val(),
        password: this.form.patch("user", "password").val(),
        id_role: this.form.patch("user", "id_role").val(),
      });
    }
    // todo clear form
    clearForm() {
      this.form.patch("user", "name").val("");
      this.form.patch("user", "email").val("");
      this.form.patch("user", "password").val("");
      this.form.patch("user", "id_role").val("");
    }
    // todo build select2
    buildSelect() {
      this.select.build("userRole", this.form.patch("user", "id_role"), {
        data: this.api("GET", "role"),
        patch: ( data )=> {
          return [data.id_role, data.name];
        }
      });
    }
  }

  /* USER:DETAIL Frontend */
  class Detail extends CyruzFrontend {
    init() {
      this.load("modal");
    }
    start( id ) {
      this.api("GET", "user/"+id, resp=> {
        this.modal.patch("name", resp.data.name);
        this.modal.patch("email", resp.data.email);
        this.api("GET", "role/"+resp.data.id_role, resp=> {
          this.modal.patch("role", resp.data.name);
        });
      });
      this.modal.show();
    }
  }

  /* USER:CREATE Frontend */
  class Create extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
      this.load("select");
    }
    start() {
      this.buttonSubmit();
      this.buildSelect();
      this.modal.show();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.buildForm();
        this.api("POST", "user", this.form.value("user"), resp=> {
          this.table.reload("user");
          this.clearForm();
          this.modal.hide();
          this.buttonSubmit("enable");
          this.buildToast("success");
          this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
        });
      });
    }
  }

  /* PRODUCT:UPDATE Frontend */
  class Update extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
      this.load("select");
    }
    start( id ) {
      this.id = id;
      this.buttonSubmit();
      this.buildSelect();
      this.api("GET", "user/"+this.id, resp=> {
        this.form.patch("user", "name").val(resp.data.name);
        this.form.patch("user", "email").val(resp.data.email);
        this.form.patch("user", "id_role").val(resp.data.id_role);
        this.form.patch("user", "pin").val(resp.data.pin);
      });
      this.modal.show();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.buildForm();
        this.api("PUT", "user/"+this.id, this.form.value("user"), resp=> {
          this.table.reload("user");
          this.clearForm();
          this.modal.hide();
          this.buttonSubmit("enable");
          this.buildToast("success");
          this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
        });
      });
    }
  }

  /* PRODUCT:DELETE Frontend */
  class Delete extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
    }
    start( id ) {
      this.buttonSubmit();
      this.id = id;
      this.api("GET", "user/"+id, resp=> {
        this.modal.patch("name", resp.data.name);
        this.modal.patch("email", resp.data.email);
      });
      this.modal.show();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.api("DELETE", "user/"+this.id, {}, resp=> {
          this.table.reload("user");
          this.modal.hide();
          this.buttonSubmit("enable");
          this.buildToast("success");
          this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
        });
      });
    }
  }

  /* LOADER Frontend */
  return {
    Apps: {
      Base: new Base,
      Detail: new Detail,
      Create: new Create,
      Update: new Update,
      Delete: new Delete,
    },
    Clones: {
      Base: { 
        app:["Detail", "Create", "Update", "Delete"], 
        property:["table"], 
        method:[ "buildSelect", "buildForm", "clearForm" ]
      },
    }
  };
});