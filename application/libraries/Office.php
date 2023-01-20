<?php

class Office {

  public function excel_import( $file )
  {
    $file = $file["tmp_name"];

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($file);
    $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $collumn = $sheet[1];
    unset($sheet[1]);
    
    $result=[];
    foreach($sheet as $row) {
      $query=[];
      foreach($row as $key=>$val) {
        $query[$collumn[$key]] = $val;
      }
      $result[] = $query;
    }

    return $result;
  }

  public function excel_export( $datas )
  {

    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $cell_collumn = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    $fields=[];
    $i=0;
    foreach ($datas[0] as $field=>$data) {
      if ($field!=='id') {
        $sheet->setCellValue($cell_collumn[$i].'1', $field);
        $fields[$cell_collumn[$i]] = $field;
        $i++;
      }
    }

    $row=2;
    foreach ( $datas as $data ) {

      foreach ($fields as $key=>$val) {
        $sheet->setCellValue($key . $row, $data[$val]);
      }

      $row++;
    }

    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx( $spreadsheet );
    // $writer->save('export_samplecrud.xlsx');
    
    // Proses file excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="export_samplecrud.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
  }

  public function excel_export_html( $datas )
  {

    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $cell_collumn = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    $fields=[];
    $i=0;
    foreach ($datas[0] as $field=>$data) {
      if ($field!=='id') {
        $sheet->setCellValue($cell_collumn[$i].'1', $field);
        $fields[$cell_collumn[$i]] = $field;
        $i++;
      }
    }

    $row=2;
    foreach ( $datas as $data ) {

      foreach ($fields as $key=>$val) {
        $sheet->setCellValue($key . $row, $data[$val]);
      }

      $row++;
    }

    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx( $spreadsheet );
    // $writer->save('export_samplecrud.xlsx');
    
    // Proses file excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="export_samplecrud.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
  }

  public function excel_write( $filename=null, $data=null, $fields=null )
  {
    $filename = (!empty($filename)) ? $filename : 'office_excel';

    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    if (!empty($fields)) {
      foreach ($fields as $cell=>$value) {
        $sheet->setCellValue($cell.'1', $value);
      }
    }


    if (!empty($data)) {
      $row=2;
      foreach ( $data as $value ) {
        foreach ($fields as $cell=>$value2) {
          $sheet->setCellValue($cell . $row, $data[$val]);
        }
        $row++;
      }
    }
    
    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx( $spreadsheet );
    // Proses file excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"'); // Set nama file excel nya
    header('Cache-Control: max-age=0');
    // output
    $writer->save('php://output');
  }

  public function excel( $options=[] )
  {
    $cord = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // mode write
    if ($options['mode'] == 'write') {
      $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $fields=[];

      if (isset($options['fields'])) {
        $i=0;
        foreach ($options['fields'] as $field) {
          $fields[$cord[$i]] = $field;
          $i++;
        }
        foreach ($fields as $cell=>$value) {
          $sheet->setCellValue($cell.'1', $value);
        }
      }
      
      if (!empty($options['data'])) {
        $row=2;
        foreach ( $options['data'] as $value ) {
          foreach ($fields as $cell=>$value2) {
            $sheet->setCellValue($cell . $row, $data[$val]);
          }
          $row++;
        }
      }

      $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx( $spreadsheet );
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="'.$options['filename'].'.xlsx"'); // Set nama file excel nya
      header('Cache-Control: max-age=0');
      $writer->save('php://output');
    }

    // mode read
    if ($options['mode'] == 'read') {
      $file = $options['file']["tmp_name"];

      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      $spreadsheet = $reader->load( $file );
      $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

      $collumn = $sheet[1];
      unset($sheet[1]);
      
      $result=[];
      foreach($sheet as $row) {
        $query=[];
        foreach($row as $key=>$val) {
          $query[$collumn[$key]] = $val;
        }
        $result[] = $query;
      }

      return $result;
    }


    


  }






  /* HTML OFFICE */
  public function html_get( $source ) {
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
    return $reader->load( APPPATH.'/views/'.$source.'.html' );
  }
  public function html_set( $htmlTable ) {
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
    return $reader->loadFromString( $htmlTable );
  }
  public function html_out() {}



  // public function excel_write( $source )
  // {
  //   return new PhpOffice\PhpSpreadsheet\Writer\Xlsx( $spreadsheet );
  // }


  public function excel_html( $source )
  {
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
    $spreadsheet = $reader->loadFromString( $source );

    return new PhpOffice\PhpSpreadsheet\Writer\Xlsx( $spreadsheet );
  }


}