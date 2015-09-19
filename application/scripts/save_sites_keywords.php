<?php

include_once "../../libraries/connect_db.class.php";

$site_tablename="site_keyword";

//----------------------- функция сохранения сайтов в базу-----------------------------------
function save_site($Sites){
	global $site_tablename,$user_id;
        
        
//        foreach($Sites as $value){
//            $temp[] = mb_strtolower($value, "UTF-8");
//        }
//        
//        $Sites = $temp;
//        unset($temp);
//        unset($value);
        
	$ObjDb = new connect_db();
        $ObjDb->db_connect();
	//-------------------------------работа с таблицей данных--------------------------------
	
               
	//------------------------------выбор сайтов из таблицы-----------------------------
	$query = "SELECT site FROM $site_tablename WHERE user_id = '$user_id'";
	$result = mysql_query($query);
	if(!$result){
		print(mysql_error());
		//return 0;
	}
	for ($i=0; $i<mysql_num_rows($result); $i++){
	 	$query_data = mysql_fetch_array($result);	 	
		//$SiteData[] = iconv ('windows-1251', 'utf-8', $query_data['site']);
                $SiteData[] = $query_data['site'];
	}
	if(count($SiteData)!=0){
		$SiteData = array_unique($SiteData);
		$temp = $SiteData;
		$SiteData = array();
		foreach($temp as $value){
			$SiteData[] = $value;
		}		
		unset($temp);
	}
	//--------------------------------запись и удаление сайтов в таблице--------------------------
	if(count($SiteData)==0){
		for($i=0;$i<count($Sites);$i++){
			$site = $Sites[$i];
                        
			//$site = iconv ('utf-8', 'windows-1251', $site);
			$query="INSERT INTO $site_tablename VALUES(NULL,'$user_id','$site',NULL)";
			$result=mysql_query($query);
			if(!$result){
				print(mysql_error());
                                exit;
				//return 0;
			}
		}
	}
	else{	
		$deleted_sites = array_unique(array_diff ($SiteData, $Sites));
		$add_sites = array_unique(array_diff ($Sites, $SiteData));	
		
		foreach($add_sites as $site){
			//$site = iconv ('utf-8', 'windows-1251', $site);				
			$query="INSERT INTO $site_tablename VALUES(NULL,'$user_id','$site',NULL)";
			$result=mysql_query($query);
			if(!$result){
				print(mysql_error());
                                exit;
				//return 0;
			}
		}
		foreach($deleted_sites as $site){
			//$site = iconv ('utf-8', 'windows-1251', $site);			
			$query="DELETE FROM $site_tablename WHERE site = '$site' AND user_id = '$user_id'";
			$result=mysql_query($query);
					if(!$result){
				print(mysql_error());
                                exit;
				//return 0;
			}
		}			
	}
        $ObjDb->db_close();
	return 1;
}



//-------------------------------функция сохранения запросов в базу-------------------
function save_keyword($Data){
	global $site_tablename,$user_id;		
	//-------------------------------работа с таблицей данных--------------------------------
	$ObjDb = new connect_db();
        $ObjDb->db_connect();	
	//-------------------------запись и удаление запросов из таблицы---------------------------
	foreach($Data as $SiteKeyword){
		$site = $SiteKeyword[0];
		$k = count($SiteKeyword)-1;
		
		//$site = iconv ('utf-8', 'windows-1251', $site);	
		$query = "SELECT keyword FROM $site_tablename WHERE user_id = '$user_id' AND site = '$site'";		
		$result = mysql_query($query);
		if(!$result){		
			print(mysql_error());
                        exit;
			//return 0;
		}
		for ($i=0; $i<mysql_num_rows($result); $i++){
 			$query_data = mysql_fetch_array($result);
 			$keyword = $query_data['keyword'];
			if($keyword == null && mysql_num_rows($result)==1){
				$KeywordData = array();
			}
			else{
				//$KeywordData[] = iconv ('windows-1251', 'utf-8', $keyword);
                                $KeywordData[] = $keyword;
			}
		}								
		$KeywordsTemp = array_splice($SiteKeyword,-$k,$k);		
		$deleted_keywords = array_unique(array_diff ($KeywordData, $KeywordsTemp));
		$add_keywords = array_unique(array_diff ($KeywordsTemp, $KeywordData));
		
		if(count($add_keywords)!=0&&count($KeywordData)==0){
			$query = "DELETE FROM $site_tablename WHERE  user_id = $user_id AND site = '$site' AND keyword IS NULL "; 
			$result = mysql_query($query);
			if(!$result){
				print(mysql_error());
                                exit;
				//return 0;
			}
		}
		foreach($add_keywords as $keyword){
			//$keyword = iconv ('utf-8', 'windows-1251', $keyword);
			$query="INSERT INTO $site_tablename VALUES(NULL,'$user_id','$site','$keyword')";
			$result=mysql_query($query);
			if(!$result){
				print(mysql_error());
                                exit;
				//return 0;
			}	
		}
		foreach($deleted_keywords as $keyword){
			//$keyword = iconv ('utf-8', 'windows-1251', $keyword);
			$query = "DELETE FROM $site_tablename WHERE  user_id = $user_id AND site = '$site' AND keyword = '$keyword' "; 
			$result = mysql_query($query);
			if(!$result){
				print(mysql_error());
                                exit;
				//return 0;
			}
		}
		//------------------------------случай удаления все ключевых слов----
		if(count($deleted_keywords)==count($KeywordData)&&count($KeywordData)!=0&&count($add_keywords)==0){
			$query="INSERT INTO $site_tablename VALUES(NULL,'$user_id','$site',NULL)";
			$result=mysql_query($query);
			if(!$result){
				print(mysql_error());
                                exit;
				//return 0;
			}		
		}
		unset($SiteData);
		unset($KeywordData);
	}
        $ObjDb->db_close();
	return 1;
}
//--------------------------------------------------------------------------------------

	
if (isset( $_GET["jsonTextKeyword"]) || isset( $_GET["jsonTextSite"])){
	$result = false;
	$user_id = $_GET["user_id"];
        
	if (isset( $_GET["jsonTextSite"])){
		$jsonTextSite = $_GET["jsonTextSite"];
		$SiteArray = json_decode($jsonTextSite, true);
                
                 //Удаляем пробелы trim Удаляет пробелы (или другие символы) из начала и конца строки 
                foreach($SiteArray as $val){
                        $temp[] = trim($val);
                }
                $SiteArray = $temp;
                unset($temp); 
                //------------------------------------------------------------------------------------
                
		if(!is_array($SiteArray)){
			$SiteArray = array();
		}
                
		$result = save_site($SiteArray); 	
	}
	
	if (isset( $_GET["jsonTextKeyword"])) 
	{	
		$jsonTextKeyword = $_GET["jsonTextKeyword"];
				
		$KeywordArray = json_decode($jsonTextKeyword, true);
		//-------------------из IE данные поступают в windows-1251 но пхп пишет что это юникод---------------------
		//-------------------json_decode() кодирует только уникод
		/*if(!is_array($KeywordArray)){
			$jsonTextKeyword = iconv ('windows-1251', 'utf-8', $jsonTextKeyword);
			$KeywordArray = json_decode($jsonTextKeyword, true);
		}*/
                //Проверяем количество запросов
                $quantity = 0;
                foreach ($KeywordArray as $val){
                    $quantity += count($val)-1;
                }
                if($quantity>50){
                    print 'Количество запросов превышает допустимое!';
                    exit;
                }
                
                //Удаляем пробелы trim Удаляет пробелы (или другие символы) из начала и конца строки
                unset($val);
                unset($temp);
                foreach($KeywordArray as $array){
                    foreach($array as $val){
                        $temp[] = trim($val);
                    }
                    $tempKeyword[] = $temp;
                    unset($temp);
                }
                unset($temp);                
                $KeywordArray = $tempKeyword;
                unset($tempKeyword);
                
		$result = save_keyword($KeywordArray); 	
	}	

	echo $result;
} 
else 
{
	echo "PHP is working correctly. Congratulations!";
}

?>