<?



function FormChars ($p1) { // преобразования лишних символов и пробелов строки
return nl2br(htmlspecialchars(trim($p1), ENT_QUOTES), false);
}

function parse_url_if_valid($url,$protocol) 
{
    // Массив с компонентами URL, сгенерированный функцией parse_url()
    $arUrl = parse_url($url);
    // Возвращаемое значение. По умолчанию будет считать наш URL некорректным.
    $ret = null;

    // Если не был указан протокол, или
    // указанный протокол некорректен для url
    if (!array_key_exists("scheme", $arUrl)
            || !in_array($arUrl["scheme"], array("http", "https")))
        // Задаем протокол по умолчанию - https
        $arUrl["scheme"] = $protocol;

    // Если функция parse_url смогла определить host
    if (array_key_exists("host", $arUrl) &&
            !empty($arUrl["host"]))
        // Собираем конечное значение url
        $ret = sprintf("%s://%s%s", $arUrl["scheme"],
                        $arUrl["host"], $arUrl["path"]);

    // Если значение хоста не определено
    // (обычно так бывает, если не указан протокол),
    // Проверяем $arUrl["path"] на соответствие шаблона URL.
    else if (preg_match("/^\w+\.[\w\.]+(\/.*)?$/", $arUrl["path"]))
        // Собираем URL
        $ret = sprintf("%s://%s", $arUrl["scheme"], $arUrl["path"]);

    // Если url валидный и передана строка параметров запроса
    if ($ret && empty($ret["query"]))
        $ret .= sprintf("?%s", $arUrl["query"]);

    $ret = str_replace("?", "", $ret);
    return $ret;
}

function doesFile($url,$filename) // наличие файла СТАТУС 0/имяфайла
{

	// файл, который мы проверяем
	$fileUrl = $url."/".$filename;
	

	if (@fopen($fileUrl, "r")) {
		return $fileUrl;
	} else {
		echo "<br/>Файла нету";
		return 0;
	}

}


function getRemoteFileSize($fileurl){ // Получить размер удаленного файла можно из HTTP-заголовков.
   $parse = parse_url($fileurl);
   $host = $parse['host'];
   $fp = @fsockopen ($host, 80, $errno, $errstr, 20); // устанавливаем сокет соединение
   if(!$fp){
     $ret = 0;
   }else{
     $host = $parse['host'];
     fputs($fp, "HEAD ".$fileurl." HTTP/1.1\r\n");
     fputs($fp, "HOST: ".$host."\r\n");
     fputs($fp, "Connection: close\r\n\r\n");
     $headers = "";
     while (!feof($fp)){
       $headers .= fgets ($fp, 128);
     }
     fclose ($fp);
     $headers = strtolower($headers);
     $array = preg_split("|[\s,]+|",$headers);
     $key = array_search('content-length:',$array);
     $ret = $array[$key+1];
   }
   if($array[1]==200) return $ret;
   else return -1*$array[1];
}


function finalFileSize($size) // в зависимости от ответа ф-ии getRemoteFileSize , делаем вывод 
{
	if($size==0) echo "Не могу соединиться";
 	elseif($size<0) echo "Ошибка. Код ответа HTTP: ".(-1*$size);
 	else $size;
}

function get_http_response_code_on_query_file($fileUrl) { // ф-ия выводит ответ на  http-запрос к файлу
 	$headers = get_headers($fileUrl);
 	return substr($headers[0], 9, 3);
}



function checkHost($array)
{
	$sum = 0;
	for($i = 0; $i < count($array); $i++)
	{
		if(strstr($array[$i],"Host:"))
			$sum++;
	}
	return $sum;
}

function checkSitemap($array)
{
	$sum = 0;
	for($i = 0; $i < count($array); $i++)
	{
		if(strstr($array[$i],"Sitemap:"))
			$sum++;
	}
	return $sum;
}




function workFile($fileUrl)
{
	$array = file($fileUrl); // берем файл и загоняем в массив
	

	echo "<br/>Вхождения строки Host-->".checkHost($array); // check HOST
	echo "<br/>";
	echo "<br/>Вхождения строки Sitemap-->".checkSitemap($array); // check SiteMap
	echo "<br/>";echo "<br/>Размерность файла ->";
	finalFileSize(getRemoteFileSize($fileUrl)); // check sizeFile
	echo "<br/>";
	echo "<br/>Код ответа на запрос файла ->>".get_http_response_code_on_query_file($fileUrl)."<br/>";
	echo "<br/>";
	$str = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$fileUrl); 
    echo    "<br/><br/>
            <form method='POST' action='/saveRes.php'>
            <input type='hidden' name='host' value=".checkHost($array).">
            <input type='hidden' name='sitemap' value=".checkSitemap($array).">
            <input type='hidden' name='filesize' value=".finalFileSize(getRemoteFileSize($fileUrl)).">
            <input type='hidden' name='httpcode' value=".get_http_response_code_on_query_file($fileUrl).">
            <input type='hidden' name='urlsite' value=".$str.">
            <input type='submit' name='button' value='Save result'>
            </form>
            <br/><br/>";

	echo "<br/>";
	

}

?>