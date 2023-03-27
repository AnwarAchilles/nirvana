/* 
 * PACKAGE EDITOR Based ckeditor5 
 * ---- ---- ---- ---- */
class PackageEditor {
  // main
  __frontend = {};
  __editor = {};
  // constructor
  constructor( Frontend, Editor ) {
    this.__frontend = Frontend;
    this.__editor = Editor;
  }
  // build
  build( nest, control ) {
    if (typeof this.__editor[nest] !== 'object') {
      let x = ClassicEditor.create( control, {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'numberedList', 'bulletedList', 'blockQuote', '|', 'insertTable', 'undo', 'redo' ]
      }).then((newEditor)=> {
        this.__editor[nest] = newEditor;
      });
    }
  }
  // patch from nest
  patch( nest ) {
    if (typeof this.__editor[nest] == 'object') {
      return this.__editor[nest];
    }
  }
}