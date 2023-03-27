/* 
 * PACKAGE LOADER
 * ---- ---- ---- ---- */
class NirvanaLoader {
  frontend={};
  // main
  constructor( Frontend ) {
    this.frontend= Frontend;
  }
  // package loader
  form( option={}, name ) {
    this.frontend[name] = new PackageForm( this.frontend, option );
  }
  modal( option={}, name ) {
    this.frontend[name] = new PackageModal( this.frontend, option );
  }
  toast( option={}, name ) {
    this.frontend[name] = new PackageToast( this.frontend, option );
  }
  table( option={}, name ) {
    this.frontend[name] = new PackageTable( this.frontend, option );
  }
  editor( option={}, name ) {
    this.frontend[name] = new PackageEditor( this.frontend, option );
  }
  select( option={}, name ) {
    this.frontend[name] = new PackageSelect( this.frontend, option );
  }
  container( option={}, name ) {
    this.frontend[name] = new PackageContainer( this.frontend, option );
  }
}