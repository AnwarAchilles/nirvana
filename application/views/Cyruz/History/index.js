/* HISTORY Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "History", ( Manifest ) => {

  /* HISTORY:BASE Frontend */
  class Base extends Frontend {
    init() {
      this.load("table");
      this.table.build("history", {
        cols: [
          {data:"button", width:"5%"},
          {data:"no"},
          {data:"prefix"},
          {data:"name"},
          {data:"status"},
          {data:"datetime"},
        ],
        http: ["GET", "api/history"],
        patch: ( data )=> {
          if (data.name == "Admin") {
            data.button = "";
          }
        }
      });
    }
  }

  /* HISTORY:DETAIL Frontend */
  class Detail extends CyruzFrontend {
    init() {
      this.load("modal");
    }
    start( id ) {
      this.api("GET", "history/"+id, resp=> {
        this.modal.patch("prefix", resp.data.prefix);
        this.modal.patch("status", resp.data.status);
        this.modal.patch("name", resp.data.name);
        
        this.modal.patch("user").html("");
        Object.entries( JSON.parse(resp.data.user) ).forEach( Entry=> {
          const [name, value] = Entry;
          this.modal.patch("user").append("<tr><th class='py-1'>"+name+"</th><td class='py-1'>"+value+"</td></tr>");
        });

        this.modal.patch("menu").html("");
        Object.entries( JSON.parse(resp.data.menu) ).forEach( Entry=> {
          const [name, value] = Entry;
          this.modal.patch("menu").append("<tr><th class='py-1'>"+name+"</th><td class='py-1'>"+value+"</td></tr>");
        });

        this.modal.patch("source").html("");
        Object.entries( JSON.parse(resp.data.source) ).forEach( Entry=> {
          const [name, value] = Entry;
          this.modal.patch("source").append("<tr><th class='py-1'>"+name+"</th><td class='py-1'>"+value+"</td></tr>");
        });
      });
      this.modal.show();
    }
  }

  /* HISTORY:DELETE Frontend */
  class Delete extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
    }
    start( id ) {
      this.buttonSubmit();
      this.id = id;
      this.api("GET", "history/"+id, resp=> {
        this.modal.patch("prefix", resp.data.prefix);
        this.modal.patch("status", resp.data.status);
        this.modal.patch("name", resp.data.name);
      });
      this.modal.show();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.api("DELETE", "history/"+this.id, {}, resp=> {
          this.api("POST", "toasted", {header:users.email, message:this.base.name+" "+this.base.repo});
          this.table.reload("history");
          this.modal.hide();
          this.buttonSubmit("enable");
          this.buildToast("success");
        });
      });
    }
  }

  /* LOADER Frontend */
  return {
    Apps: {
      Base: new Base,
      Detail: new Detail,
      Delete: new Delete,
    },
    Clones: {
      Base: { 
        app:["Detail", "Delete"], 
        property:["table"],
      },
    }
  };
});