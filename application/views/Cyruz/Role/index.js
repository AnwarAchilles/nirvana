/* ROLE Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Role", ( Manifest ) => {

  /* ROLE:BASE Frontend */
  class Base extends CyruzFrontend {
    init() {
      this.load("table");
      this.table.build("role", {
        cols: [
          {data:"button", width:"5%"},
          {data:"no"},
          {data:"name"},
          {data:"note"},
        ],
        http: ["GET", "api/role"],
        patch: ( data )=> {}
      });
    }
    buildForm() {
      this.form.initialize();

      this.form.build("role", {
        name: this.form.patch("role", "name").val(),
        note: this.form.patch("role", "note").val(),
      });
    }
    clearForm() {
      this.form.patch("role", "name").val("");
      this.form.patch("role", "note").val("");
    }
  }

  /* ROLE:VIEW Frontend */
  class View extends CyruzFrontend {
    init() {
      this.load("modal");
    }
    start( id ) {
      this.api("GET", "role/"+id, resp=> {
        this.modal.patch("name", resp.data.name);
        this.modal.patch("note", resp.data.note);
      });
      this.modal.show();
      this.modal.trigger("after", "show", ()=> {
        this.tableMenu( id );
      });
    }
  }

  /* ROLE:CREATE Frontend */
  class Create extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
    }
    start() {
      this.modal.show();
    }
    submit() {
      this.buildForm();
      this.api("POST", "role", this.form.value("role"), resp=> {
        this.table.reload("role");
        this.clearForm();
        this.modal.hide();
        this.buildToast("success");
        this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
      });
    }
  }

  /* ROLE:UPDATE Frontend */
  class Update extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
    }
    start( id ) {
      this.id = id;
      this.api("GET", "role/"+this.id, resp=> {
        this.form.patch("role", "name").val(resp.data.name);
        this.form.patch("role", "note").val(resp.data.note);
      });
      this.modal.show();
    }
    submit() {
      this.buildForm();
      this.api("PUT", "role/"+this.id, this.form.value("role"), resp=> {
        this.table.reload("role");
        this.clearForm();
        this.modal.hide();
        this.buildToast("success");
        this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
      });
    }
  }

  /* ROLE:DELETE Frontend */
  class Delete extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
    }
    start( id ) {
      this.id = id;
      this.api("GET", "role/"+id, resp=> {
        this.modal.patch("name", resp.data.name);
        this.modal.patch("note", resp.data.note);
      });
      this.modal.show();
    }
    submit() {
      this.api("DELETE", "role/"+this.id, {}, resp=> {
        this.table.reload("role");
        this.modal.hide();
        this.buildToast("success");
        this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
      });
    }
  }

  /* ROLE:MENU Frontend */
  class Menu extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("form");

      this.form.initialize();
    }
    start( id_role ) {
      this.id_role = id_role;

      $("[type='checkbox']").prop("checked", false);

      this.api("GET", "role_menu/id_role/"+id_role, resp=> {
        resp.data.forEach( row=> {
          this.patch("menu-"+row.id_menu).prop("checked", true);
          
          Object.entries( row.options ).forEach( Entry=> {
            const [name, value] = Entry;
            this.patch("menu-"+row.id_menu+"-option-"+name).prop("checked", value);
          });
        });
      });
      this.modal.show();
    }
    optionsChecks( elements ) {
      let status = $(elements).prop('checked');
      let target = $(elements).attr('data-to');
      $("."+target).prop('checked', status);
    }
    submit() {
      let data = this.form.batch( (controls, patch) => {
        controls.id_role = this.id_role;
        if (patch.name !== 'id_menu') {
          controls[patch.name] = patch.controls.prop("checked");
        }else {
          controls[patch.name] = patch.controls.val();
        }
      });

      Object.entries( data ).forEach( Entry=> {
        const [name, controls] = Entry;
        this.api("POST", "role_menu/entries", controls, resp=> {
          console.log( resp );
        });
      });
    }
  }
  
  /* LOADER Frontend */
  return {
    Apps: {
      Base: new Base,
      View: new View,
      Create: new Create,
      Update: new Update,
      Delete: new Delete,
      Menu: new Menu,
    },
    Clones: {
      Base: { 
        app:[ "View", "Create", "Update", "Delete" ], 
        property:[ "table" ], 
        method:[ "buildForm", "clearForm" ]
      }
    }
  };
});