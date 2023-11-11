<?php
$servername = "localhost";
$username = "root";
$password = "autoset";
$dbname = "test";
//mysql and db connection

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {  //error check
    die("Connection failed: " . $con->connect_error);
}
else
{

}


$DB_TBLName = "g5_write_unlock"; 
$filename = "excelfilename";  //your_file_name
$file_ending = "xls";   //file_extention

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.$file_ending");  
header("Pragma: no-cache"); 
header("Expires: 0");

$sep = "\t";

//$sql="SELECT * FROM $DB_TBLName";
$sql = <<<EOD
SELECT count(*) as count, wr_5, SUBSTRING_INDEX(wr_6, '|', 1) as name, 
SUBSTRING_INDEX(SUBSTRING_INDEX(wr_6, "|", 2),"|", -1) as phone,
SUBSTRING_INDEX(wr_3, '|', -1) as country
FROM g5_write_unlock group by wr_5 order by count desc
EOD;
$resultt = $con->query($sql);
while ($property = mysqli_fetch_field($resultt)) { //fetch table field name
    echo $property->name."\t";
}

print("\n");    

while($row = mysqli_fetch_row($resultt))  //fetch_table_data
{
    $schema_insert = "";
    for($j=0; $j< mysqli_num_fields($resultt);$j++)
    {
        if(!isset($row[$j]))
            $schema_insert .= "NULL".$sep;
        elseif ($row[$j] != "")
            $schema_insert .= "$row[$j]".$sep;
        else
            $schema_insert .= "".$sep;
    }
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}