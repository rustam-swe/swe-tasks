<form action="vazifa1.php" method="POST">
	kelgan vaqti<input type="DATE" name="data1">  
    <input type ="TIME" name = "time1"><br>
	ketgan vaqti <input type="DATE" name="data2"> 
    <input type ="TIME" name = "time2"><br>
	<button>Yuborish</button>

</form>
<pre>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
   $Date1 = $_POST['data1'];
   $Date2 = $_POST['data2'];
   $Time1 = $_POST['time1'];
   $Time2 = $_POST['time2'];
   
   $time1 = new DateTime($Time1);
   $time2 = new DateTime($Time2);

   $oraliq = $time1 -> diff($time2);

   $soat1 = $oraliq ->h;
   $minut1 = $oraliq ->i;

   $array = explode(":", $Time1);
   $son = intval($array[0]);
   $son += 9;
   $son = strval($son);
   $son1 = $array[0];
   $son2 = $array[1];
   $qiymat = "$son:$son2";

   $time = new DateTime($qiymat);
   $oraliq2 = $time2 -> diff($time);
   $soat2 = $oraliq2 ->h;
   $minut2 = $oraliq2 ->i;

   echo "ishga kelgan vaqt: << $Time1 >>\n";
   echo "ishdan ketgan vaqt: << $Time2 >>>\n";

   echo "shu kuni:  << $Date1 >>  9 soat ishlash kerak edi   siz << $soat1:$minut1 >>  soat ishladingiz\n";
   echo "siz << $soat2:$minut2 >> soat qarz bo'lgansiz";
   //var_dump($array);
   
    
    

}
