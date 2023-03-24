/* MENU Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Menu", ( Manifest ) => {

  /* MENU:BASE Frontend */
  class Base extends Frontend {
    init() {
      this.buildList();
    }
    buildList() {
      $(".datalist").html("");
      this.xhttp("GET", base_url+"cyruz/menu/list", resp=> {
        $(".datalist").html(resp);
      });
    }
    buildForm() {
      this.form.build("menu", {
        id_parent: this.form.patch("menu", "id_parent").val(),
        name: this.form.patch("menu", "name").val(),
        url: this.form.patch("menu", "url").val(),
        icon: this.form.patch("menu", "icon").val(),
        color: this.form.patch("menu", "color").val(),
        note: this.form.patch("menu", "note").val(),
      });
    }
    clearForm() {
      this.form.patch("menu", "id_parent").val("");
      this.form.patch("menu", "name").val("");
      this.form.patch("menu", "url").val("");
      this.form.patch("menu", "icon").val("");
      this.form.patch("menu", "color").val("");
      this.form.patch("menu", "note").val("");
      $(".preview-icon").html('<i class="fa-duotone fa-xl fa-home"></i>');
    }
    buildSelect() {
      this.load("select");
      if (typeof this.form.patch("menu", "id_parent")!=='undefined') {
        this.select.build("menuParent", this.form.patch("menu", "id_parent"), {
          data: this.api("GET", "menu"),
          patch: ( data )=> {
            return [data.id_menu, data.name+" ("+data.note+")"];
          }
        });
      }
      if (typeof this.form.patch("menu", "color")!=='undefined') {
        this.select.build("menuColor", this.form.patch("menu", "color"), {
          data: [
            {id:'text-secondary', name:'SECONDARY'},
            {id:'text-primary', name:'PRIMARY'},
            {id:'text-danger', name:'DANGER'},
            {id:'text-warning', name:'WARNING'},
            {id:'text-success', name:'SUCCESS'},
            {id:'text-info', name:'INFO'},
          ],
          patch: ( data )=> {
            return [data.id, data.name];
          }
        });
      }
    }
    iconPreview() {
      this.form.patch("menu", "icon").on("change", (event)=> {
        let icon = this.form.patch("menu", "icon").val();
        let color = this.form.patch("menu", "color").val();
        $(".preview-icon").html('<i class="fa-duotone fa-xl fa-'+icon+' : '+color+'"></i>');
      });
      this.form.patch("menu", "color").on("change", (event)=> {
        let icon = this.form.patch("menu", "icon").val();
        let color = this.form.patch("menu", "color").val();
        $(".preview-icon").html('<i class="fa-duotone fa-xl fa-'+icon+' : '+color+'"></i>');
      });
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

  /* MENU:VIEW Frontend */
  class View extends Frontend {
    start( id ) {
      this.load("modal");
      this.api("GET", "menu/"+id, resp=> {
        this.api("GET", "menu/"+resp.id_parent, resp=> {
          this.modal.patch("parent", resp.data.name || "-");
        });
        this.modal.patch("name", resp.data.name);
        this.modal.patch("url", resp.data.url);
        this.modal.patch("note", resp.data.note);
      });
      this.modal.show();
    }
  }

  /* MENU:CREATE Frontend */
  class Create extends Frontend {
    start( id_parent ) {
      this.id_parent = id_parent;
      this.load("modal");
      this.load("toast");
      this.load("select");
      this.load("form");

      this.clearForm();
      this.buildSelect();
      this.iconPreview();
      
      this.form.patch("menu", "id_parent").val( this.id_parent ).trigger("change");
      this.modal.show();
    }
    submit() {
      this.buildForm();
      this.api("POST", "menu", this.form.value("menu"), resp=> {
        this.api("GET", "menu/count", {"Q[where][id_parent]": this.id_parent}, resp=> {
          this.api("PUT", "menu/"+this.id_parent, { count_child:resp.data.count });
        });
        this.buildList();
        this.clearForm();
        this.modal.hide();
        this.buildToast("success");
        this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
      });
    }
  }

  /* MENU:UPDATE Frontend */
  class Update extends Frontend {
    start( id ) {
      this.id = id;
      this.load("modal");
      this.load("toast");
      this.load("form");

      this.clearForm();
      this.buildSelect();
      this.iconPreview();

      this.api("GET", "menu/"+this.id, resp=> {
        console.log(resp);
        this.form.patch("menu", "id_parent").val(resp.data.id_parent || 0).trigger("change");
        this.form.patch("menu", "name").val(resp.data.name);
        this.form.patch("menu", "url").val(resp.data.url);
        this.form.patch("menu", "icon").val(resp.data.icon);
        this.form.patch("menu", "color").val(resp.data.color).trigger("change");
        this.form.patch("menu", "note").val(resp.data.note);
      });
      this.modal.show();
    }
    submit() {
      this.buildForm();
      this.api("PUT", "menu/"+this.id, this.form.value("menu"), resp=> {
        this.buildList();
        this.clearForm();
        this.modal.hide();
        this.buildToast("success");
        this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
      });
    }
  }

  /* MENU:DELETE Frontend */
  class Delete extends Frontend {
    start( id ) {
      this.id = id;
      this.load("modal");
      this.load("toast");
      this.load("form");
      this.api("GET", "menu/"+id, resp=> {
        this.id_parent = resp.data.id_parent;
        this.modal.patch("name", resp.data.name);
        this.modal.patch("url", resp.data.url);
        this.modal.patch("icon", resp.data.icon);
        this.modal.patch("color", resp.data.color);
      });
      this.modal.show();
    }
    submit() {
      this.api("DELETE", "menu/"+this.id, {}, resp=> {
        this.api("GET", "menu/count", {"Q[where][id_parent]": this.id_parent}, resp=> {
          this.api("PUT", "menu/"+this.id_parent, { count_child:resp.data.count });
        });
        this.buildList();
        this.modal.hide();
        this.buildToast("success");
        this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
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
    },
    Clones: {
      Base: { 
        app:["View", "Create", "Update", "Delete"], 
        property:["table"], 
        method:[ "buildList", "buildSelect", "buildForm", "clearForm", "iconPreview", "buildToast" ]
      },
    }
  }
});