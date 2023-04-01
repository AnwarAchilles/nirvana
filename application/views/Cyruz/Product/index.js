/* PRODUCT Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Product", ( Manifest ) => {

  /* PRODUCT:BASE Frontend */
  class Base extends CyruzFrontend {
    init() {
      this.load("table");
      this.table.build("product", {
        cols: [
          {data:"button", width:"5%"},
          {data:"no"},
          {data:"name"},
        ],
        http: ["GET", "api/product"],
        patch: ( data )=> {}
      });
    }
    // todo build form
    buildForm() {
      this.form.build("product", {
        category: this.form.patch("product", "category").val(),
        name: this.form.patch("product", "name").val(),
        content: this.editor.patch("ProductContent").getData(),
      });
    }
    // todo build select2
    buildSelect() {
      this.select.build("productCategory", this.form.patch("product", "category"), {
        data: [
          { id:"cat_1", text:"Category 1" },
          { id:"cat_2", text:"Category 2" },
          { id:"cat_3", text:"Category 3" },
        ],
        patch: ( data )=> {
          return [data.id, data.text];
        }
      });
    }
    // todo patch modal
    modalPatch( id ) {
      this.api("GET", "product/"+id, resp=> {
        this.modal.patch("category").text( resp.data.category );
        this.modal.patch("name").text(resp.data.name);
        this.modal.patch("content").html(resp.data.content);
      });
    }
    // todo clear form
    clearForm() {
      this.form.patch("product", "category").val(0).trigger("change");
      this.form.patch("product", "name").val("");
      this.form.patch("product", "content").setData("");
    }
  }

  /* PRODUCT:DETAIL Frontend */
  class Detail extends CyruzFrontend {
    start( id ) {
      this.load("modal");
      this.modalPatch( id );
      this.modal.show();
    }
  }

  /* PRODUCT:CREATE Frontend */
  class Create extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
      this.load("select");
      this.load("editor");
      
      this.editor.build("ProductContent", this.form.patch("product", "content")[0] );
    }
    start() {
      this.buttonSubmit();
      this.buildSelect();
      this.modal.show();
    }
    submit() {
      this.buildForm();
      this.buttonSubmit("disable", process=> {
        this.api("POST", "product", this.form.value("product"), resp=> {
          this.saveHistory();
          this.saveToast();
          this.table.reload("product");
          this.clearForm();
          this.modal.hide();
          this.buildToast("success");
          this.buttonSubmit("enable");
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
      this.load("editor");

      this.editor.build("ProductContent", this.form.patch("product", "content")[0] );
    }
    start(id) {
      this.buttonSubmit();
      this.buildSelect();

      this.id = id;

      this.api("GET", "product/"+this.id, resp=> {
        this.form.patch("product", "name").val(resp.data.name);
        this.editor.patch("ProductContent").setData(resp.data.content);
      });

      this.modal.show();
    }
    submit() {
      this.buildForm();
      this.buttonSubmit("disable", process=> {
        this.api("PUT", "product/"+this.id, this.form.value("product"), resp=> {
          this.saveHistory();
          this.saveToast();
          this.table.reload("product");
          this.clearForm();
          this.modal.hide();
          this.buildToast("success");
          this.buttonSubmit("enable");
        });
      });
    }
  }

  /* PRODUCT:DELETE Frontend */
  class Delete extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
    }
    start( id ) {
      this.buttonSubmit();
      this.id = id;
      this.modalPatch( id );
      this.modal.show();
    }
    submit() {
      this.buttonSubmit("disable", process=> {
        this.api("DELETE", "product/"+this.id, {}, resp=> {
          this.saveHistory();
          this.saveToast();
          this.table.reload("product");
          this.modal.hide();
          this.buildToast("success");
          this.buttonSubmit("enable");
        });
      });
    }
  }

  /* PRODUCT:PRINT Frontend */
  class Print extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
    }
    start( id ) {
      this.id = id;
      if (this.isMobile()) {
        this.redirect( this.base.url+"/Cyruz/Product/print/"+id+"?download=true" );
      }else {
        this.modal.show();
        this.modal.patch("preview").attr("src", this.base.url+"/Cyruz/Product/print/"+id);
      }
    }
  }

  /* PRODUCT:FORMAT Frontend */
  class Format extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
    }
    start() {
      this.redirect( this.base.url+"Cyruz/Product/format");
    }
  }

  /* PRODUCT:IMPORT Frontend */
  class Import extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
    }
    start() {
      this.buttonSubmit();
      this.modal.show();
    }
    submit() {
      this.form.build("product", {
        excel: this.form.patch("product", "excel").prop("files")[0],
      });
      this.buttonSubmit("disable", process=> {
        this.xhttp("POST", this.base.url+"Cyruz/Product/import", this.form.data("product"), resp=> {
          this.saveHistory();
          this.saveToast();
          this.modal.hide();
          this.table.reload("product");
          this.buildToast("success");
          this.buttonSubmit("enable");
        });
      });
    }
  }

  /* PRODUCT:EXPORT Frontend */
  class Export extends CyruzFrontend {
    init() {
      this.load("modal");
      this.load("toast");
      this.load("form");
    }
    start() {
      this.saveHistory();
      this.saveToast();
      this.redirect( this.base.url+"Cyruz/Product/export");
    }
  }

  /* LOADER Frontend */
  return {
    Apps: {
      Base:     new Base,
      Detail:   new Detail,
      Create:   new Create,
      Update:   new Update,
      Delete:   new Delete,
      Print:    new Print,
      Format:   new Format,
      Import:   new Import,
      Export:   new Export,
    },
    Clones: {
      Base: { 
        app:        [ "Detail", "Create", "Update", "Delete", "Import" ],
        property:   [ "table" ],
        method:     [ "buildSelect", "modalPatch", "buildForm", "clearForm" ],
      },
    }
  };
});