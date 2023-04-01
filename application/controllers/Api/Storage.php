<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Storage extends BaseApi
{
  
  # todo resize image
  public function resize_POST()
  {
    // get storage data
    $storage = $this->models->get(['where'=> ['id_storage'=> $this->id]])->row_array();
    $source = json_decode($storage['source'], true);
    $resize = (isset($this->method['resize'])) ? $this->method['resize'] : 2;
    // set image library
    $this->controller->load->library('image_lib', [
      'image_library'=> 'gd2',
      'source_image'=> $source['full_path'],
      'new_image'=> $source['full_path'],
      'width'=> ((int) $source['image_width'] / (int) $resize),
      'height'=> ((int) $source['image_height'] / (int) $resize),
    ]);
    // do resize
    if ( $this->controller->image_lib->resize() ) {
      // reconstruct source
      $resource = array_merge($source, [
        'image_width'=> ((int) $source['image_width'] / (int) $resize),
        'image_height'=> ((int) $source['image_height'] / (int) $resize),
      ]);
      // reconstruct storage
      $restorage = [
        'source'=> json_encode($resource),
      ];
      // set output
      $this->models->put(['where'=> ['id_storage'=>$this->id], 'data'=>$restorage]);
      $this->data['id_storage'] = $this->id;
      $this->return(200);
    }
  }

  # todo upload file
  public function upload_POST( $filename )
  {
    // get extension and location
    $extension = pathinfo( $this->file[$filename]['name'], PATHINFO_EXTENSION);
    $extension = strtolower($extension);
    $location = FCPATH.'storage/';
    // check if there have repository for this extension
    if (! file_exists($location.'/'.$extension)) {
      mkdir($location.'/'.$extension, 0777);
    }
    // move to repository/extension/
    $location = $location.'/'.$extension.'/';
    // set uploader library
    $this->controller->load->library('upload', [
      'upload_path'=> $location,
      'allowed_types'=> '*',
      'encrypt_name'=> true,
      'file_ext_tolower'=> true,
    ]);
    // do upload
    if ( $this->controller->upload->do_upload( $filename ) ) {
      // set upload data
      $upload_data = $this->controller->upload->data();
      $upload_data['file_dir'] = 'storage/'.$extension;
      $upload_data['image_dir'] = 'storage/'.$extension.'/'.$this->controller->upload->data('file_name');
      // create query data
      $QUERY = [
        'name'=> (isset($this->method['name'])) ? $this->method['name'] : $this->controller->upload->data('raw_name'),
        'fullname'=> $upload_data['file_name'],
        'directory'=> $extension,
        'source'=> json_encode( $upload_data ),
      ];
      // set output
      $this->data['id_storage'] = $this->models->set(['data'=> $QUERY]);
      $this->data['name'] = $QUERY['name'];
      $this->data['url_image'] = base_url('storage/'.$extension.'/'.$this->controller->upload->data('file_name'));
      $this->return(200);
    }else {
      $this->data['location'] = $location;
      $this->data['error'] = $this->controller->upload->display_errors();
      $this->return(203);
    }
  }
  
  # todo delete image with file
  public function delete_REST()
  {
    // get query
    $QUERY = $this->query;
    // check query
    if ($this->config->api['prefix_id'] == TRUE) {
      $QUERY['where']['id_'.$this->models->table] = $this->id;
    }else {
      $QUERY['where'] = ['id'=>$this->id];
    }
    // get storage data
    $storage = $this->models->get(['where'=> ['id_storage'=> $this->id]])->row_array();
    $source = json_decode($storage['source'], true);
    unlink( $source['full_path'] );
    // set output
    $this->data = $this->models->del( $QUERY );
    $this->return(200);
  }

}