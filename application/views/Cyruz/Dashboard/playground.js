/* DASHBOARD Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Playground", (Manifest)=> {

  /* Base Frontend */
  class Base extends Frontend {
    init() {
      console.log('x');
    }
  }

  /* LOADER Frontend */
  return {
    Apps: {
      Base: new Base,
    },
    Clones: {}
  }

});