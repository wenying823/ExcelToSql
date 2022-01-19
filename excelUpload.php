<?php
require('library/php-excel-reader/excel_reader2.php');
require('library/SpreadsheetReader.php');
require('db_config.php');

function sql_value( $data ) {
	$data = str_replace("'" , "''" , $data);
	return $data;
}

$output_sql = $_POST['output_sql'];
$mysqli_check = $_POST['mysqli_check'];
if(isset($_POST['Submit'])) {
	if($output_sql == "" && $mysqli_check == "") {
		echo "至少需勾選輸出sql語法喔!";
	} else {
		$mimes = ['application/vnd.ms-excel' , 'text/xls' , 'text/xlsx' , 'application/vnd.oasis.opendocument.spreadsheet' , 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
		if(in_array($_FILES["file"]["type"] , $mimes)) {
			//建立存放sql語法txt檔
			if($output_sql == 'on') {
				$filename = $_FILES['file']['name'];
				$filename = str_replace(".xlsx" , "-insert_sql.txt" , $filename);
				$sqlfile = fopen(iconv("utf-8" , "big5" , $filename) , "w") or die("Unable to open file!");
			}
			$uploadFilePath = 'uploads/' . basename($_FILES['file']['name']);
			move_uploaded_file($_FILES['file']['tmp_name'] , iconv("utf-8" , "big5" , $uploadFilePath));
			$Reader = new SpreadsheetReader(iconv("utf-8" , "big5" , $uploadFilePath));

			//遊戲列表(GameList)
			$Reader->ChangeSheet(0);
			fwrite($sqlfile , '#GameList_sql'."\r\n");
			foreach($Reader as $Row) {
				$GameID = isset($Row[0]) ? $Row[0] : '';
				if($GameID == '') {
					break;
				} elseif($GameID == 'GameID') {
					continue;
				}
				$GameCode = isset($Row[1]) ? $Row[1] : '';
				$RealCode = isset($Row[2]) ? $Row[2] : '';
				$GameName = isset($Row[3]) ? $Row[3] : '';
				$MenuType = isset($Row[4]) ? $Row[4] : '';
				$OpenGame = isset($Row[5]) ? $Row[5] : '';
				$IsMobile = isset($Row[6]) ? $Row[6] : '';
				$IsWeb = isset($Row[7]) ? $Row[7] : '';
				$Image = isset($Row[8]) ? $Row[8] : '';
				$Currency = isset($Row[9]) ? $Row[9] : '';
				$NewTime = isset($Row[10]) ? $Row[10] : '';
				$UPTime = isset($Row[11]) ? $Row[11] : '';
				$action = isset($Row[12]) ? $Row[12] : '';
				$NewTime_new = date("Y-m-d H:i:s" , strtotime($NewTime));
				$UPTime_new = date("Y-m-d H:i:s" , strtotime($UPTime));
				//SQL語句轉義字符
				$GameCode = sql_value($GameCode);
				$RealCode = sql_value($RealCode);
				$GameName = sql_value($GameName);
				$GameName = sql_value($GameName);
				$MenuType = sql_value($MenuType);
				$OpenGame = sql_value($OpenGame);
				$IsMobile = sql_value($IsMobile);
				$IsWeb = sql_value($IsWeb);
				$Image = sql_value($Image);
				$Currency = sql_value($Currency);
				if($action == "") {
					continue;
				}
				if($action == "I") {
					$query = "insert into GameList.GameList values('" . $GameID . "','" . $GameCode . "','" . $RealCode . "','" . $GameName . "','" . $MenuType . "','" . $OpenGame . "','" . $IsMobile . "','" . $IsWeb . "','" . $Image . "','" . $Currency . "','" . $NewTime_new . "','" . $UPTime_new . "');";
				} elseif($action == "U") {
					$query = "update GameList.GameList set GameID='" . $GameID . "',GameCode='" . $GameCode . "',RealCode='" . $RealCode . "',GameName='" . $GameName . "',MenuType='" . $MenuType . "',OpenGame='" . $OpenGame . "',IsMobile='" . $IsMobile . "',IsWeb='" . $IsWeb . "',Image='" . $Image . "',Currency='" . $Currency . "',NewTime='" . $NewTime_new . "',UPTime='" . $UPTime_new . "' where GameID = '" . $GameID . "' ;";
				}
				if($output_sql == 'on') {
					fwrite($sqlfile , $query);
				}
				if($mysqli_check == 'on') {
					$mysqli->query($query);
				}
			}
			fwrite($sqlfile , "\r\n");

			//字典檔(GameDictionary)
			$Reader->ChangeSheet(1);
			fwrite($sqlfile , '#GameDictionary_sql'."\r\n");
			foreach($Reader as $Row) {
				$DictionaryType = isset($Row[0]) ? $Row[0] : '';
				if($DictionaryType == '') {
					break;
				} elseif($DictionaryType == 'DictionaryType') {
					continue;
				}
				$GameDictionary = isset($Row[1]) ? $Row[1] : '';
				$GameCode = isset($Row[2]) ? $Row[2] : '';
				$GameType = isset($Row[3]) ? $Row[3] : '';
				$GameName = isset($Row[4]) ? $Row[4] : '';
				$NewTime = isset($Row[5]) ? $Row[5] : '';
				$UPTime = isset($Row[6]) ? $Row[6] : '';
				$action = isset($Row[7]) ? $Row[7] : '';
				$NewTime_new = date("Y-m-d H:i:s" , strtotime($NewTime));
				$UPTime_new = date("Y-m-d H:i:s" , strtotime($UPTime));
				//SQL語句轉義字符
				$GameDictionary = sql_value($GameDictionary);
				$GameCode = sql_value($GameCode);
				$GameType = sql_value($GameType);
				$GameName = sql_value($GameName);
				$GameName = sql_value($GameName);
				if($action == "") {
					continue;
				}
				if($action == "I") {
					$query = "insert into GameList.GameDictionary values('" . $DictionaryType . "','" . $GameDictionary . "','" . $GameCode . "','" . $GameType . "','" . $GameName . "','" . $NewTime_new . "','" . $UPTime_new . "');";
				} elseif($action == "U") {
					$query = "update GameList.GameDictionary set DictionaryType='" . $DictionaryType . "',GameDictionary='" . $GameDictionary . "',GameCode='" . $GameCode . "',GameType='" . $GameType . "',GameName='" . $GameName . "',NewTime='" . $NewTime_new . "',UPTime='" . $UPTime_new . "' WHERE GameDictionary = '" . $GameDictionary . "' AND GameCode = '" . $GameCode . "';";				}

				if($output_sql == 'on') {
					fwrite($sqlfile , $query);
				}
				if($mysqli_check == 'on') {
					$mysqli->query($query);
				}
			}
			fwrite($sqlfile , "\r\n");

			//子分類(GameMenu)
			$Reader->ChangeSheet(2);
			fwrite($sqlfile , '#GameMenu_sql'."\r\n");
			foreach($Reader as $Row) {
				$GameCode = isset($Row[0]) ? $Row[0] : '';
				if($GameCode == '') {
					break;
				} elseif($GameCode == 'GameCode') {
					continue;
				}
				$MenuType = isset($Row[1]) ? $Row[1] : '';
				$MenuOrder = isset($Row[2]) ? $Row[2] : '';
				$MenuSubOrder = isset($Row[3]) ? $Row[3] : '';
				$MenuName = isset($Row[4]) ? $Row[4] : '';
				$TopUserID = isset($Row[5]) ? $Row[5] : '';
				$NewTime = isset($Row[6]) ? $Row[6] : '';
				$UPTime = isset($Row[7]) ? $Row[7] : '';
				$action = isset($Row[8]) ? $Row[8] : '';
				$NewTime_new = date("Y-m-d H:i:s" , strtotime($NewTime));
				$UPTime_new = date("Y-m-d H:i:s" , strtotime($UPTime));
				//SQL語句轉義字符
				$MenuType = sql_value($MenuType);
				$MenuOrder = sql_value($MenuOrder);
				$MenuSubOrder = sql_value($MenuSubOrder);
				$MenuName = sql_value($MenuName);
				$TopUserID = sql_value($TopUserID);
				if($action == "") {
					continue;
				}
				if($mysqli_check == 'on') {
					$mysqli->query($query);
				}


				if($action == "I") {
					$query = "insert into GameList.GameMenu values('" . $GameCode . "','" . $MenuType . "','" . $MenuOrder . "','" . $MenuSubOrder . "','" . $MenuName . "','" . $TopUserID . "','" . $NewTime_new . "','" . $UPTime_new . "');";
				} elseif($action == "U") {
					$query = "update GameList.GameMenu set GameCode='" . $GameCode . "',MenuType='" . $MenuType . "',MenuOrder='" . $MenuOrder . "',MenuSubOrder='" . $MenuSubOrder . "',MenuName='" . $MenuName . "',TopUserID='" . $TopUserID . "',NewTime='" . $NewTime_new . "',UPTime='" . $UPTime_new . "' where MenuType = '" . $MenuType . "' AND GameCode = '" . $GameCode . "';";
				}
				if($output_sql == 'on') {
					fwrite($sqlfile , $query);
				}
			}
			if($mysqli_check != "") {
				echo "已新增/更新資料庫";
				echo "<br>";
			}
			if($output_sql == 'on') {
				echo "已輸出sql語法至txt檔";
				echo "<br>";
				fclose($sqlfile);
			}
		} else {
			echo "請選擇正確檔案";
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Excel to SQL</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<div class="container">

	<form method="POST" action="index.php" enctype="multipart/form-data">
		<button type='submit' class="btn btn-lg btn-danger">返回</button>
	</form>
</div>

</body>
</html>
