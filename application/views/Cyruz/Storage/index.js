/* STORAGE Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Storage", ( Manifest ) => {

  /* STORAGE:BASE Frontend */
  class Base extends Frontend {
    init() {
      this.load("table");
      this.table.build("storage", {
        cols: [
          {data:"button", width:"5%"},
          {data:"no"},
          {data:"name"},
        ],
        http: ["GET", "api/storage"],
        patch: ( data )=> {
          if (data.name == "Admin") {
            data.button = "";
          }
        }
      });
    }
    // todo build form
    buildForm() {
      this.form.build("default", {
        name: this.form.patch("default", "name").val(),
        upload: this.form.patch("default", "upload").prop('files')[0],
      });
    }
    // todo clear form
    clearForm() {
      this.form.patch("default", "name").val("");
      this.form.patch("default", "upload").val("");
    }
    // todo build select2
    buildSelect() {
      // this.select.build("userRole", this.form.patch("user", "id_role"), {
      //   data: this.api("GET", "role"),
      //   patch: ( data )=> {
      //     return [data.id_role, data.name];
      //   }
      // });
    }
  }

  /* STORAGE:DETAIL Frontend */
  class Detail extends CyruzFrontend {
    init() {
      this.load("modal");
    }
    start( id ) {
      this.api("GET", "storage/"+id, resp=> {
        this.modal.patch("name", resp.data.name);
        this.modal.patch("source").html( JSON.stringify(JSON.parse(resp.data.source), null, 4) );
      });
      this.modal.show();
    }
  }

  /* STORAGE:CREATE Frontend */
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
        this.api("POST", "storage/upload/upload", this.form.data("default"), resp=> {
          this.table.reload("storage");
          this.clearForm();
          this.modal.hide();
          this.buttonSubmit("enable");
          this.buildToast("success");
          this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
        });
      });
    }
  }

  /* STORAGE:DELETE Frontend */
  class Delete extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
    }
    start( id ) {
      this.buttonSubmit();
      this.id = id;
      this.api("GET", "storage/"+id, resp=> {
        this.modal.patch("name", resp.data.name);
      });
      this.modal.show();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.api("DELETE", "storage/"+this.id, {}, resp=> {
          this.table.reload("storage");
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
      Delete: new Delete,
    },
    Clones: {
      Base: { 
        app:["Detail", "Create", "Delete"], 
        property:["table"], 
        method:[ "buildSelect", "buildForm", "clearForm" ]
      },
    }
  };
});