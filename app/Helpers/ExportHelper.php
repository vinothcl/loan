<?php
namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExportHelper {
	public static $col = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ', 'FA', 'FB', 'FC', 'FD', 'FE', 'FF', 'FG', 'FH', 'FI', 'FJ', 'FK', 'FL', 'FM', 'FN', 'FO', 'FP', 'FQ', 'FR', 'FS', 'FT', 'FU', 'FV', 'FW', 'FX', 'FY', 'GA', 'GB', 'GC', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GJ', 'GK', 'GL', 'GM', 'GN', 'GO', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GV', 'GW', 'GX', 'GY');
	/**
	 * This function is used to get download file Xls and Csv
	 * @param $excelData = array(
	'column'=> $column,
	'rows'=> $rows,
	'fileName'=> 'excel',
	'type'=> 'xls',
	'limit'=> 5000,
	);
	 * note $savePath-> should send from public path Ex: 'public/webfiles/tmp/test.xls'
	 * @return excel
	 * @author Techaffinity:vinothcl
	 *
	 */
	public static function exportRecordWithColumns($dataVal) {

		$dataColumn = (isset($dataVal['column'])) ? $dataVal['column'] : [];
		$results = (isset($dataVal['rows'])) ? $dataVal['rows'] : [];
		$fileName = (isset($dataVal['fileName'])) ? $dataVal['fileName'] : 'Excel' . date("Y-m-d");
		$type = (isset($dataVal['type'])) ? $dataVal['type'] : 'xls';
		$limit = (isset($dataVal['limit'])) ? $dataVal['limit'] : (count($results) + 50);
		$col = self::$col;
		$spreadSheet = new Spreadsheet();
		$spreadSheet->setActiveSheetIndex(0);
		$colId = 1;
		$i = 0;
		foreach ($dataColumn as $fieldname) {
			$spreadSheet->getActiveSheet()->SetCellValue($col[$i] . $colId, $fieldname);
			$spreadSheet->getActiveSheet()->getStyle($col[$i] . $colId)->getFont()->setBold(true);
			$i++;
		}
		if ($results) {
			foreach ($results as $row) {
				$colId++;
				$j = 0;
				if ($colId <= $limit) {
					foreach ($row as $key => $value) {
						$spreadSheet->getActiveSheet()->SetCellValue($col[$j] . $colId, $value);
						if (is_numeric($value)) {
							$spreadSheet->getActiveSheet()->getStyle($col[$j] . $colId)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
						} else {
							$spreadSheet->getActiveSheet()->getStyle($col[$j] . $colId)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
						}
						$j++;
					}
				}
			}
		} else {
			$colId++;
			$j = 0;
			$spreadSheet->getActiveSheet()->SetCellValue($col[0] . $colId, 'No Record Found');
		}
		foreach (range('A', $spreadSheet->getActiveSheet()->getHighestDataColumn()) as $col) {
			$spreadSheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
		//save our workbook as this file name
		//$filename = $fileName . '.xls';
		$filename = $fileName . '.' . $type;
		header('Content-Type: application/vnd.ms-excel');
		//tell browser what's the file name
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0'); //no cache
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		if ($type == 'csv') {
			$writer = IOFactory::createWriter($spreadSheet, 'Csv');
		} else {
			$writer = IOFactory::createWriter($spreadSheet, 'Xls');
		}
		$writer->save('php://output');
		exit;
	}

	/**
	 * this function is used to export using row and colums
	 * @param $rows
	 * @param $headers
	 * @author Techaffinity:vinothcl
	 */
	public static function exportWithRowAndColums($rows, $headers, $fileName = 'excel-') {
		$excelData = array(
			'column' => $headers,
			'rows' => self::exportRecordRowResetByColums($rows, array_keys($headers)),
			'fileName' => $fileName . date("Y-m-d-His"),
		);
		return self::exportRecordWithColumns($excelData);
	}
	/**
	 * This function is used to reset rows by colums
	 * @param $dataRow
	 * @param $dataColumn
	 * @return array
	 * @author Techaffinity:vinothcl
	 */
	public static function exportRecordRowResetByColums($dataRow, $dataColumn) {
		$finalResult = array();
		foreach ($dataRow as $row) {
			$finalResultRow = array();
			$result = array();
			foreach ($row as $key => $value) {
				if (in_array($key, $dataColumn)) {
					$finalResultRow[$key] = $value;
				}
			}
			foreach ($dataColumn as $col) {
				if (array_key_exists($col, $finalResultRow)) {
					$result[$col] = $finalResultRow[$col];
				} else {
					$result[$col] = "";
				}

			}
			$finalResult[] = $result;
		}
		return $finalResult;
	}
}