<!DOCTYPE html>
<html>
<head>
	<title>Вывод</title>
	<meta charset="utf-8">
</head>
<body>




<?

include_once('function.php');







if($_POST['siteName']) // если форма не пустая
{

	$filename = "robots.txt"; // берем имя файла

    $url = parse_url_if_valid(FormChars($_POST['siteName']),"http"); // делайт ссылку валидной 
    if (!$url) {
        // Введен некорректный URL
        echo "enter valid address";
        echo '<a href="index.php">Hommmmeeee</a>';
        exit();
    } else {
        // Работаем с полученным значением, как нам удобно.
        

    	$fileurl = doesFile($url,$filename); // присваем значение функции сайт+имяФайла если все верно


    	if(!doesFile($url,$filename)) // пробуем через протокол https найти файл
    	{
    		echo "<br/>going to try<br/>";
    		$url = parse_url_if_valid(FormChars($_POST['siteName']),"https");
    		$fileurl = doesFile($url,$filename);
    	}

        if($fileurl)
        {
        	echo "<br/>STATUS = 1<br/>";
        	echo "<br/>URL:$url<br/>";
        	workFile($fileurl,$url);



        	
        }
        else 
        {
        	echo "STATUS = 0";
        }
    
    }

	echo '<a href="index.php">Hommmmeeee</a>';

} else {
	echo 'Form was empty!';
	echo '<a href="index.php">Hommmmeeee</a>';
}


?>


















</body>
</html>







