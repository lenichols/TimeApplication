<?php
 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once dirname(__FILE__) . '/../../../dashboard/Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/../../../dashboard/Classes/PHPExcel/Writer/Excel2007.php';

if(isset($_POST['recCheckBx'], $_POST['export'])){

    try {
        ///this post exports xls file for selected items checked 
        ///using the checkbox on admin
        $objPHPExcel = new PHPExcel();
        $rowCount = 1;
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Project');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Task Type');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Wr Num');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Reg Hrs');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'OT Hrs');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'DT Hrs');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Notes');
    
        $lnID = (!empty($_POST['recCheckBx'])) ? $_POST['recCheckBx'] : null; 
        $arrW = array_filter($lnID);

        for($z=0;$z<=count($arrW)-1;$z++) {

            $stmto = $conn->prepare("SELECT date, eID, project, taskType, wrnum, SUM(stHours), SUM(otHours), SUM(dtHours), notes FROM records WHERE status = 'approved' AND id = ? ORDER BY dateadded ASC");
			//$stmto = $conn->prepare("SELECT date, eID, project, taskType, wrnum, SUM(stHours), SUM(otHours), SUM(dtHours), notes FROM records WHERE status = 'approved' AND id = ? ORDER BY dateadded ASC");
            //$updateRec = $conn->prepare("UPDATE `records` set `status` = 'accepted' where id = ?");

            $stmto->execute(array($arrW[$z]));
            $s = $stmto->fetch();

        if($s){ 
            $rowCount++;
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $s["date"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $s['eID']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $s['project']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $s['taskType']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $s['wrnum']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $s['stHours']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $s['otHours']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $s['dtHours']);               
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $s['notes']);   
            
            //do not mark record - this is done in sup queue
            //uncomment $data and $updateRec line to mark field when admin downloads xls
            //mark exported dates with accepted flag
            //$data = array($arrW[$z]);
            //$updateRec->execute($data);
            
            
        }
 
        }
        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="timekeeperExport.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        
        $secondsWait = 2;
        echo '<meta http-equiv="refresh" content="'.$secondsWait.'">';
        exit;
        
        
        
    } catch(PDOException $e) {
              echo $e->getMessage();
    }
} ///end of isset(post




if(isset($_POST['export'])){
    ///this export exports all records marked with approved
    require_once dirname(__FILE__) . '/../../../dashboard/Classes/PHPExcel.php';
    require_once dirname(__FILE__) . '/../../../dashboard/Classes/PHPExcel/Writer/Excel2007.php';

    try {
        $objPHPExcel = new PHPExcel();
        $rowCount = 1;
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Project');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Task Type');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Wr Num');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Reg Hrs');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'OT Hrs');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'DT Hrs');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Notes');
        
        $stmt = $conn->query("SELECT * FROM records WHERE status = 'approved' ORDER BY dateadded DESC");
        while($r = $stmt->fetch()) {
            $rowCount++;
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $r["date"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $r['eID']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $r['project']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $r['taskType']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $r['wrnum']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $r['stHours']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $r['otHours']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $r['dtHours']);               
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $r['notes']);
            

        } //end of while statement
      
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="timekeeperExport.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;

     } catch(PDOException $e) {
              echo $e->getMessage();
     }
}

?>