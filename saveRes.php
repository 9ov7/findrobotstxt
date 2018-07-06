<!DOCTYPE html>
<html>
<head>
  <title>Result excel</title>
  <meta charset="utf-8">
</head>
<body>

<?php

echo $_POST['host']."<br/>";
echo $_POST['sitemap']."<br/>";
echo $_POST['filesize']."<br/>";
echo $_POST['httpcode']."<br/>";
echo $_POST['urlsite']."<br/>";


require_once 'PHPExcel.php'; // Подключаем библиотеку PHPExcel
  $phpexcel = new PHPExcel(); // Создаём объект PHPExcel
  /* Каждый раз делаем активной 1-ю страницу и получаем её, потом записываем в неё данные */
  $page = $phpexcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её

  // минимум красоты
  $page->getColumnDimension('A')->setWidth(4);
  $page->getColumnDimension('B')->setWidth(80);
  $page->getColumnDimension('C')->setWidth(10);
  $page->getColumnDimension('D')->setWidth(16);
  $page->getColumnDimension('E')->setWidth(90);
  // Заголовки
  $page->setCellValue("A1","№");
  $page->setCellValue("B1","Название проверки");
  $page->setCellValue("C1","Статус");
  $page->setCellValue("E1","  ");
  $page->setCellValue("E1","Текущее состояние");
  // нумерация



  $page->setCellValue("A3","1"); //$page->mergeCells('A3:A4');
  $page->setCellValue("A5","2");// $page->mergeCells('A5:A6');
  $page->setCellValue("A7","3");// $page->mergeCells('A7:A8');
  $page->setCellValue("A9","4"); //$page->mergeCells('A9:A10');
  $page->setCellValue("A11","5");// $page->mergeCells('A11:A12');
  $page->setCellValue("A13","6"); //$page->mergeCells('A13:A14');
  // предОписание
  $page->setCellValue("B3","Проверка наличия файла robots.txt");// $page->mergeCells('B3:B4');
  $page->setCellValue("B5","Проверка указания директива Host");  //$page-> mergeCells('B5:B6');
  $page->setCellValue("B7","Проверка количества директив Host,прописанных в файле");  //$page->mergeCells('B7:B8');
  $page->setCellValue("B9","Проверка размера файла robots.txt");  //$page-> mergeCells('B9:B10');
  $page->setCellValue("B11","Проверка указания директивы Sitemap");  //$page->mergeCells('B11:12');
  $page->setCellValue("B13","Проверка кода ответа сервера на запрос robots.txt"); //$page-> mergeCells('B13:B14');

  // объединяем статус


  // Состояние и рекомендации
  for($i = 3; $i < 15; $i++)
  {
    $page->setCellValue("D".$i."","Состояние");
    $i++;
    $page->setCellValue("D".$i."","Рекомендации");
  }
 

  // start
  $page->setCellValue("C3","OK");
  $page->setCellValue("E3","Файл robots.txt присутствует!");
  $page->setCellValue("E4","Доработки не требуются");
  // 2 check
  if($_POST['host']) 
  {
    $page->setCellValue("C5","OK");
    $page->setCellValue("E5","Директива Host указана!");
    $page->setCellValue("E6","Доработки не требуются");
  }
  else if(!$_POST['host'])
  {
    $page->setCellValue("C5","Oшибка");
    $page->setCellValue("E5","В файле robots.txt не указана директива Host");
    $page->setCellValue("E6","Программист: Для того, чтобы поисковые системы знали, какая версия сайта является основных зеркалом, необходимо прописать адрес основного зеркала в директиве Host. В данный момент это не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host задётся в файле 1 раз, после всех правил.");
  }
  // 3 check
  if($_POST['host'] == 1) 
  {
    $page->setCellValue("C7","OK");
    $page->setCellValue("E7","В файле прописана 1 директива Host");
    $page->setCellValue("E8","Доработки не требуются");
  }
  else if($_POST['host'] > 1)
  {
    $page->setCellValue("C7","Oшибка");
    $page->setCellValue("E7","В файле прописано несколько директив Host");
    $page->setCellValue("E8","Программист: Директива Host должна быть указана в файле толоко 1 раз. Необходимо удалить все дополнительные директивы Host и оставить только 1, корректную и соответствующую основному зеркалу сайта");
  }
  // 4 check
  if($_POST['filesize'] > 0 && $_POST['filesize'] < 32000) // ~~32Kb 
  {
    $size = $_POST['filesize']/1024;
    $page->setCellValue("C9","OK");
    $page->setCellValue("E9","Размер файла robots.txt составляет ".$size."Кб, что находится в пределах допустимой нормы");
    $page->setCellValue("E10","Доработки не требуются");
  }
  else if($_POST['filesize'] > 32000)
  {
    $size = $_POST['filesize']/1024;
    $page->setCellValue("C9","Oшибка");
    $page->setCellValue("E9","Размера файла robots.txt составляет ".$size.", что превышает допустимую норму");
    $page->setCellValue("E10","Программист: Максимально допустимый размер файла robots.txt составляем 32 кб. Необходимо отредактировть файл robots.txt таким образом, чтобы его размер не превышал 32 Кб");
  }
  // 5 check
  if($_POST['sitemap']) 
  {
    $page->setCellValue("C11","OK");
    $page->setCellValue("E11","Директива Sitemap указана");
    $page->setCellValue("E12","Доработки не требуются");
  }
  else if($_POST['sitemap'] <= 0)
  {
    $page->setCellValue("C11","Oшибка");
    $page->setCellValue("E11","В файле robots.txt не указана директива Sitemap");
    $page->setCellValue("E12","Программист: Добавить в файл robots.txt директиву Sitemap");
  }
  // 6 check
  if($_POST['httpcode'] == 200) 
  {
    $page->setCellValue("C13","OK");
    $page->setCellValue("E13","Файл robots.txt отдаёт код ответа сервера 200");
    $page->setCellValue("E14","Доработки не требуются");
  }
  else if(!$_POST['httpcode'] != 200)
  {
    $page->setCellValue("C13","Oшибка");
    $page->setCellValue("E13","При обращении к файлу robots.txt сервер возвращает код ответа ".$_POST['httpcode']."");
    $page->setCellValue("E14","Программист: Файл robots.txt должны отдавать код ответа 200, иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом, чтобы при обращении к файлу robots.txt сервер возвращает код ответа 200");
  }
  


  $page->setTitle("Report for robots txt"); 
  /* Начинаем готовиться к записи информации в xlsx-файл */
  $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
  /* Записываем в файл */

  $objWriter->save($_POST['urlsite'].".xlsx");
  echo '<a href="'.$_POST['urlsite'].'.xlsx" download="'.$_POST['urlsite'].'.xlsx" title="save me ">скачать файл</a>';

?>











</body>
</html>

