/* COMPANY Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Company", ( Manifest ) => {

  /* COMPANY:BASE Frontend */
  // class Base extends CyruzFrontend {
  //   init() {}
  // }

  class Logo extends CyruzFrontend {
    start( id_company ) {
      this.load('form');
      this.load('toast');

      this.form.build("logo", {
        logo: this.form.patch('logo', 'logo').prop('files')[0]
      });

      this.api("POST", "storage/upload/logo", this.form.data('logo'), resp=> {
        this.api("POST", "storage/resize/"+resp.data.id_storage);
        
        let image = resp.data.url_image;
        this.api("PUT", "company/"+id_company, { logo: resp.data.id_storage }, resp=> {
          this.buildToast("success");
          this.patch("image-logo").attr("src", image);
        });
      });
    }
  }

  class Profile extends CyruzFrontend {
    start( id_company ) {
      this.id_company = id_company;
      this.load('form');
      this.load('toast');
    }
    submit() {
      this.form.build('profile', {
        name: this.form.patch('profile', 'name').val(),
        description: this.form.patch('profile', 'description').val(),
      });
      this.api("PUT", "company/"+this.id_company, this.form.value('profile'), resp=> {
        this.buildToast("success");
      });
    }
  }

  /* LOADER Frontend */
  return {
    Apps: {
      // Base: new Base,
      Logo: new Logo,
      Profile: new Profile,
    },
    Clones: {}
  }
});

NIRVANA.start('Company', 'Profile', company.id_company);