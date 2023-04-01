/* MENU Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Menu", ( Manifest ) => {

  /* MENU:BASE Frontend */
  class Base extends CyruzFrontend {
    init() {
      this.buildList();
    }
    buildList() {
      $(".datalist").html("");
      this.xhttp("GET", base_url+"Cyruz/Menu/list", resp=> {
        $(".datalist").html(resp);
      });
    }
    buildForm() {
      this.form.build("menu", {
        status: this.form.patch("menu", "status").val(),
        id_parent: this.form.patch("menu", "id_parent").val(),
        name: this.form.patch("menu", "name").val(),
        url: this.form.patch("menu", "url").val(),
        stack: this.form.patch("menu", "stack").val(),
        icon: this.form.patch("menu", "icon").val(),
        color: this.form.patch("menu", "color").val(),
        note: this.form.patch("menu", "note").val(),
      });
    }
    clearForm() {
      this.form.patch("menu", "status").val("");
      this.form.patch("menu", "id_parent").val("");
      this.form.patch("menu", "name").val("");
      this.form.patch("menu", "url").val("");
      this.form.patch("menu", "stack").val("");
      this.form.patch("menu", "icon").val("");
      this.form.patch("menu", "color").val("");
      this.form.patch("menu", "note").val("");
      $(".preview-icon").html('<i class="fa-duotone fa-xl fa-home"></i>');
    }
    buildSelect() {
      this.load("select");
      // id parent
      if (typeof this.form.patch("menu", "id_parent")!=='undefined') {
        this.select.build("menuParent", this.form.patch("menu", "id_parent"), {
          data: this.api("GET", "menu"),
          patch: ( data )=> {
            return [data.id_menu, data.name+" ("+data.note+")"];
          }
        });
      }
      // color
      if (typeof this.form.patch("menu", "color")!=='undefined') {
        this.select.build("menuColor", this.form.patch("menu", "color"), {
          data: [
            {id:'secondary', name:'SECONDARY'},
            {id:'primary', name:'PRIMARY'},
            {id:'danger', name:'DANGER'},
            {id:'warning', name:'WARNING'},
            {id:'success', name:'SUCCESS'},
            {id:'info', name:'INFO'},
          ],
          patch: ( data )=> {
            return [data.id, data.name];
          }
        });
      }
      // status Menu
      if (typeof this.form.patch("menu", "status")!=='undefined') {
        this.select.build("menuStatus", this.form.patch("menu", "status"), {
          data: [
            {id:'READY', name:'Ready'}, 
            {id:'MAINTENANCE', name:'Maintenance'}, 
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
        $(".preview-icon").html('<i class="fa-duotone fa-xl fa-'+icon+' : text-'+color+'"></i>');
      });
      this.form.patch("menu", "color").on("change", (event)=> {
        let icon = this.form.patch("menu", "icon").val();
        let color = this.form.patch("menu", "color").val();
        $(".preview-icon").html('<i class="fa-duotone fa-xl fa-'+icon+' : text-'+color+'"></i>');
      });
    }
  }

  /* MENU:DETAIL Frontend */
  class Detail extends CyruzFrontend {
    start( id ) {
      this.load("modal");
      this.api("GET", "menu/"+id, resp=> {
        this.api("GET", "menu/"+resp.data.id_parent, resp=> {
          if (typeof resp.data.icon !== 'undefined') {
            this.modal.patch("parent").html('<i class="fa-duotone fa-'+resp.data.icon+' fa-fw : text-'+resp.data.color+' me-1"></i>'+resp.data.name);
          }else {
            this.modal.patch("parent").text("");
          }
        });
        this.modal.patch("icon").text(resp.data.icon);
        this.modal.patch("name").html('<i class="fa-duotone fa-'+resp.data.icon+' fa-fw : text-'+resp.data.color+' me-1"></i>'+resp.data.name);
        this.modal.patch("url").text(resp.data.url);
        this.modal.patch("note").text(resp.data.note);
      });
      this.modal.show();
    }
  }

  /* MENU:CREATE Frontend */
  class Create extends CyruzFrontend {
    start( id_parent ) {
      this.id_parent = id_parent;
      
      this.load("modal");
      this.load("toast");
      this.load("select");
      this.load("form");

      this.buttonSubmit();
      this.clearForm();
      this.buildSelect();
      this.iconPreview();
      
      this.form.patch("menu", "id_parent").val( this.id_parent ).trigger("change");
      
      this.modal.show();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.buildForm();
        this.api("POST", "menu", this.form.value("menu"), resp=> {
          this.api("GET", "menu/count", {"Q[where][id_parent]": this.id_parent}, resp=> {
            this.api("PUT", "menu/"+this.id_parent, { child:resp.data.count });
          }, false);
          this.saveHistory();
          this.saveToast();
          this.buildList();
          this.clearForm();
          this.buttonSubmit("enable");
          this.modal.hide();
          this.buildToast("success");
        });
      });
    }
  }

  /* MENU:UPDATE Frontend */
  class Update extends CyruzFrontend {
    start( id ) {
      this.id = id;
      this.load("modal");
      this.load("toast");
      this.load("form");

      this.buttonSubmit();
      this.clearForm();
      this.buildSelect();
      this.iconPreview();

      this.api("GET", "menu/"+this.id, resp=> {
        this.form.patch("menu", "status").val(resp.data.status || 0).trigger("change");
        this.form.patch("menu", "id_parent").val(resp.data.id_parent || '').trigger("change");
        this.form.patch("menu", "name").val(resp.data.name);
        this.form.patch("menu", "url").val(resp.data.url);
        this.form.patch("menu", "icon").val(resp.data.icon);
        this.form.patch("menu", "color").val(resp.data.color).trigger("change");
        this.form.patch("menu", "note").val(resp.data.note);
      });
      this.modal.show();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.buildForm();
        this.api("PUT", "menu/"+this.id, this.form.value("menu"), resp=> {
          this.saveHistory();
          this.saveToast();
          this.buildList();
          this.clearForm();
          this.buttonSubmit("enable");
          this.modal.hide();
          this.buildToast("success");
        });
      });
    }
  }

  /* MENU:DELETE Frontend */
  class Delete extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
    }
    start( id ) {
      this.id = id;
      this.buttonSubmit();
      this.api("GET", "menu/"+id, resp=> {
        this.id_parent = resp.data.id_parent;
        this.modal.patch("name").html('<i class="fa-duotone fa-'+resp.data.icon+' fa-fw : text-'+resp.data.color+' me-1"></i>'+resp.data.name);
        this.modal.patch("note").text(resp.data.url);
      });
      this.modal.show();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.api("DELETE", "menu/"+this.id, {}, resp=> {
          this.api("GET", "menu/count", {"Q[where][id_parent]": this.id_parent}, resp=> {
            this.api("PUT", "menu/"+this.id_parent, { child:resp.data.count });
          });
          this.saveHistory();
          this.saveToast();
          this.buildList();
          this.modal.hide();
          this.buttonSubmit("enable");
          this.buildToast("success");
        });
      });
    }
  }

  /* MENU:OPTION Frontend */
  class Option extends CyruzFrontend {
    init() {
      this.load("modal");
    }
    datalist( id_menu ) {
      this.modal.patch("datalist").html("");
      this.api("GET", "menu_option/id_menu/"+id_menu, resp=> {
        let comp = [];
        resp.data.forEach( (row, i) =>{
          let nirvana = "('Menu', 'Option', 'delete', ["+row.id_menu+", "+row.id_menu_option+"])";
          comp[0] = '<td class="py-2">'+i+'</td>';
          comp[1] = '<td class="py-2 w-100">'+row.option+'</td>';
          comp[2] = '<td class="p-1"><button onclick="NIRVANA.run'+nirvana+'" class="btn btn-sm btn-danger px-2"><i class="fa fa-trash"></i></button></td>';
          this.modal.patch("datalist").append('<tr>'+comp.join('')+'</tr>');
        });
      });
    }
    start( id_menu ) {
      this.buttonSubmit();
      this.id_menu = id_menu;
      this.datalist( id_menu );
      this.modal.show();
    }
    delete( id_data ) {
      console.log( id_data ); 
      this.api("DELETE", "menu_option/"+id_data[1], resp=> {
        this.datalist( id_data[0] );
        this.buttonSubmit("enable");
      });
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        let data = {
          id_menu: this.id_menu,
          option: this.patch("option").val(),
        };
        this.api("POST", "menu_option", data, resp=> {
          this.datalist( this.id_menu );
          this.patch("option").val("");
          this.buttonSubmit("enable");
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
      Option: new Option,
    },
    Clones: {
      Base: { 
        app:["Detail", "Create", "Update", "Delete"], 
        property:["table"], 
        method:[ "buildList", "buildSelect", "buildForm", "clearForm", "iconPreview" ]
      },
    }
  }
});