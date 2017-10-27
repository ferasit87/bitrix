<?php
/**
 * Created by PhpStorm.
 * User: Feras
 * Date: 19.10.2017
 * Time: 15:20
 */
class Export
{
     public $app;
     
     public function __construct($app)
     {
        $this->app = $app;
     }
      /**
     * export data to file
     * @param $arResult data 
     * @param $type type of exported file (csv,xml) 
     * @param $delimiter delimiter for csv file
     */
    public function export($arResult,$type,$delimiter = ",")
    {

        $file = $type =='csv' ||  $type =='xml' ? 'user_data_' . date('Y-m-d H:i:s') . '.' . $type : null;
        if (!$file) return;
        if ($type =='csv'){
            $rootFile = $_SERVER["DOCUMENT_ROOT"] . '/' . $file;
            $csvFile = new CCSVData();
            $csvFile->SetDelimiter($delimiter);
            fopen($rootFile, 'w') or die("Can't create file");
            $csvFile->SaveFile($rootFile, $arResult['FIELDS']);
            
            foreach ($arResult['USERS'] as $u) {
                $data = [];
                foreach ($arResult['FIELDS'] as $field) {
                    $rowval = $u[$field];
                    $data[] = (!$rowval ? 'NAN' : $rowval);
                }
                $csvFile->SaveFile($rootFile, $data);
            }
        }elseif( $type =='xml'){
            $xml = new SimpleXMLElement('<root/>');
            foreach ($arResult['USERS'] as $u) {
                $elem = $xml->addChild('USER');
                $data = [] ;
                foreach ($arResult['FIELDS'] as $field) {
                    $rowval = $u[$field];
                    $data[$rowval] = $field;
                }
                array_walk_recursive($data, array ($elem, 'addChild'));
            }
            $xml->asXml($file);
            $rootFile = $_SERVER["DOCUMENT_ROOT"] . '/' . $file;
        }
        if (file_exists($rootFile)) {
                $this->app->RestartBuffer();
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                header('Content-Type: ' . finfo_file($finfo, $rootFile));
                finfo_close($finfo);
                header('Content-Disposition: attachment; filename=' . basename($rootFile));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($rootFile));
                ob_clean();
                flush();
                readfile($rootFile);
                exit;
            }
        return false;
    }
}