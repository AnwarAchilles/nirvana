/* 
 * PACKAGE TABLE Based datatables
 * ---- ---- ---- ---- */
class PackageTable {
  // private
  __frontend={};
  __table={};
  // playground
  columns=[];
  // main
  constructor( Frontend, Table ) {
    this.__frontend = Frontend;
  }
  // build table nest based
  build( nest, options ) {
    if ( ! $.fn.DataTable.isDataTable('.datatables') ) {
      // set parameters
      const params = {};
      params.columns = options.cols;
      params.type = options.http[0];
      params.url = options.http[1]+"/datatable";
      params.dataSrc = options.patch.bind( this );
      params.order = options.order;
      // render table
      this.__table[nest] = $(".datatables").DataTable({
        serverSide: true,
        columns: params.columns,
        order: params.order,
        ajax: {
          url: this.__frontend.base.url+"/"+params.url,
          type: params.type,
          dataSrc: ( src ) => {
            src.data.forEach( (data, i) => {
              data.button = $(".datatables_button").html();
  
              for (const name in data) {
                data.button = data.button.replaceAll("{"+name+"}", data[name]);
              }
  
              data.button = data.button.replaceAll("{base_url}", base_url);
              data.button = data.button.replaceAll("{current_url}", current_url);
  
              params.dataSrc(data, i);
  
              src.data[i] = data;
            });
            return src.data;
          }
        }
      });
    }
  }
  // reload table
  reload( nest ) {
    if (typeof this.__table[nest].ajax !== 'undefined') {
      this.__table[nest].ajax.reload();
    }
  }
}