/* ROLE Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Role", ( Manifest ) => {

  /* ROLE:BASE Frontend */
  class Base extends Frontend {
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
    // todo build toast
    buildToast( opt, response ) {
      this.toast.container("Default");
      this.toast.use("Default");

      if (opt=='success') {
        this.toast.patch("icon", '<i class="fa-duotone fa-2x fa-check-circle fa-beat : text-success me-3"></i>');
        this.toast.patch("text", '<strong class="me-auto">'+this.base.name+' Sucessfully</strong>');
      }
      if (opt=='failed') {
        this.toast.patch("icon", '<i class="fa-duotone fa-2x fa-times-circle fa-beat : text-danger me-3"></i>');
        this.toast.patch("text", '<strong class="me-auto">'+this.base.name+' Failed</strong>');
      }
      this.toast.show();
    }
  }

  /* ROLE:VIEW Frontend */
  class View extends Frontend {
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
  class Create extends Frontend {
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
  class Update extends Frontend {
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
  class Delete extends Frontend {
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
  class Menu extends Frontend {
    init() {
      this.load("modal");
      this.load("form");
      this.load("select");

      this.select.build("role_menu", this.form.patch("role_menu", "id_menu"), {
        data: this.api("GET", "menu/list"),
        patch: ( data )=> {
          return [data.id_menu, data.name+" ("+data.note+")"];
        }
      });
      this.iconPreview();
    }
    start( id ) {
      this.id = id;
      this.modal.show();
      $(this.base.target+" .boxMenu").html("");
      this.modal.trigger("after", "show", ()=> {
        this.tableMenu( this.id );
      });
    }
    tableMenu( id ) {
      const checker = {
        true: '<i class="fa fa-lg fa-check fa-fw : text-success me-1"></i>',
        false: '<i class="fa fa-lg fa-times fa-fw : text-danger me-1"></i>',
      };
      this.api("GET", "role_menu", { "Q[where][id_role]":id, "Q[join][menu]":"role_menu.id_menu = menu.id_menu", "Q[order_by][order]":"asc"}, (resp)=> {
        $(this.base.target+" .boxMenu").html("");
        resp.data.forEach((menu)=> {
          let options = JSON.parse(menu.options);
          let container = this.container("menu", "tbody");
          this.patch("icon", container).html('<i class="fa-duotone fa-lg fa-fw fa-'+menu.icon+' : '+menu.color+' me-1"></i>');
          this.patch("name", container).text(menu.name);
          this.patch("optionView", container).html(checker[options.view]);
          this.patch("optionCreate", container).html(checker[options.create]);
          this.patch("optionUpdate", container).html(checker[options.update]);
          this.patch("optionDelete", container).html(checker[options.delete]);
          this.patch("optionPrint", container).html(checker[options.print]);
          this.patch("optionImport", container).html(checker[options.import]);
          this.patch("optionExport", container).html(checker[options.export]);
          this.patch("optionFormat", container).html(checker[options.format]);
          this.patch("idRoleMenu", container).find("button").attr("onclick", "NIRVANA.run('Role', 'Menu', 'deleteMenu', '"+menu.id_role_menu+"')");
          
          $(this.base.target+" .boxMenu").append( container.html() );
        });
      }, false);
    }
    deleteMenu( idRoleMenu ) {
      this.api("DELETE", "role_menu/"+idRoleMenu, {}, resp=> {
        this.tableMenu( this.id );
      });
    }
    iconPreview() {
      this.form.patch("role_menu", "id_menu").on("change", (event)=> {
        let id_menu = this.form.patch("role_menu", "id_menu").val();
        let menu = this.api("GET", "menu/"+id_menu).responseJSON;
        $(".preview-icon").html('<i class="fa-duotone fa-xl fa-'+menu.data.icon+' : '+menu.data.color+'"></i>');
      });
    }
    submit() {
      let options = {
        view: this.form.patch("role_menu", "option_view").prop("checked"),
        create: this.form.patch("role_menu", "option_create").prop("checked"),
        update: this.form.patch("role_menu", "option_update").prop("checked"),
        delete: this.form.patch("role_menu", "option_delete").prop("checked"),
        print: this.form.patch("role_menu", "option_print").prop("checked"),
        import: this.form.patch("role_menu", "option_import").prop("checked"),
        export: this.form.patch("role_menu", "option_export").prop("checked"),
        format: this.form.patch("role_menu", "option_format").prop("checked"),
      };

      this.form.build("role_menu", {
        id_role: this.id,
        id_menu: this.form.patch("role_menu", "id_menu").val(),
        options: JSON.stringify(options),
      });

      this.api("POST", "role_menu", this.form.value("role_menu"), resp=> {
        this.tableMenu( this.id );
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
        method:[ "buildForm", "clearForm", "buildToast" ]
      },
      Menu: {
        app: [ "View" ],
        method: [ "tableMenu" ],
      }
    }
  };
});