<?php
flush();
///http://status.icq.com/online.gif?icq=НАШ_УИН_НОМЕР&img=5
// нужно заполнить поля
$from='Dmitry';
$fromemail='123g@softportal.com';
$subject='tema';
$to='353498998';  // номер
$body='mess';

$submit='Send Message'; // НЕ РЕДАКТИРОВАТЬ
$ref="http://wwp.icq.com/$to"; // НЕ РЕДАКТИРОВАТЬ

// формирование заголовка
$PostData="from=".$from."&fromemail=".urlencode($fromemail)."&subject=".$subject."&body=".$body."&to=".$to."&submit=".urlencode($submit);

$len=strlen($PostData);


$zapros="POST /scripts/WWPMsg.dll HTTP/1.0\n
Referer: $ref\n
Content-Type: application/x-www-form-urlencoded\n
Content-Length: $len\n
Host: wwp.icq.com\n
Accept: */*\n
Accept-Encoding: gzip, deflate\n
Connection: Keep-Alive\n
User-Agent: Mozilla/4.0 (compatible; MSIE 5.01; Windows NT)\n\n
$PostData";

echo $zapros." ------------- ";
flush();

// открываем сокет и шлем заголовок
$fp=fsockopen("wwp.icq.com", 80, &$errno, &$errstr, 30);
if(!$fp) { print "$errstr ($errno)<br> "; exit; }

// для наглядности выводим заголовок ответа и страницу на экран
fputs($fp,$zapros);
print fgets($fp,20048);
fclose($fp);
?>