<?php
session_start();
include ("konfig/koneksi.php");

/*
 echo "<i>cek sessionn dplus</i>";    
echo "<pre>";    
print_r($_SESSION['dplus']);    
echo "</pre>";  


echo "<i>cek sessionn dmin</i>";    
echo "<pre>";    
print_r($_SESSION['dmin']);    
echo "</pre>";  
*/




?>

<div class="box-header">
    <h3 class="box-title " >Nilai Preferensi</h3>
	<p>
    <a style="margin-bottom:10px" href="#" class="btn btn-default pull-right"><span class='glyphicon glyphicon-user'></span>Cetak Laporan</a>
  </p>
</div>

<table class="table table-bordered table-responsive">
<thead>
<tr>
<th ><center>Nomor</center></th>
<th ><center>Nama</center></th>
<th ><center>V<sub>i</sub></center></th>
</tr>

</thead>
<tbody>
<?php
$i=1;
$a=mysql_query("select * from alternatif order by id_alternatif asc;");
echo "<tr>";
$sortir=array();
while($da=mysql_fetch_assoc($a)){

	
		
		$idalt=$da['id_alternatif'];
	
		//ambil nilai
			
			$n=mysql_query("select * from nilai_matrik where id_alternatif='$idalt' order by id_matrik ASC");
		
		$c=0;
		$ymax=array();
		while($dn=mysql_fetch_assoc($n)){
			$idk=$dn['id_kriteria'];
			
			
			//nilai kuadrat
			
			$nilai_kuadrat=0;
			$k=mysql_query("select * from nilai_matrik where id_kriteria='$idk' order by id_matrik ASC ");
			while($dkuadrat=mysql_fetch_assoc($k)){
				$nilai_kuadrat=$nilai_kuadrat+($dkuadrat['nilai']*$dkuadrat['nilai']);
			}

			//hitung jml alternatif
			$jml_alternatif=mysql_query("select * from alternatif order by id_alternatif asc;");
			$jml_a=mysql_num_rows($jml_alternatif);	
			//nilai bobot kriteria (rata")
			$bobot=0;
			$tnilai=0;
			
			$k2=mysql_query("select * from nilai_matrik where id_kriteria='$idk' order by id_matrik ASC ");
			while($dbobot=mysql_fetch_assoc($k2)){
				$tnilai=$tnilai+$dbobot['nilai'];
			}	
			 $bobot=$tnilai/$jml_a;
			
			//nilai bobot input
			$b2=mysql_query("select * from kriteria where id_kriteria='$idk'");
			$nbot=mysql_fetch_assoc($b2);
			$bot=$nbot['bobot'];
			
			$v=round(($dn['nilai']/sqrt($nilai_kuadrat))*$bot);

				$ymax[$c]=$v;
				$c;
				$mak=max($ymax);
				$min=min($ymax);	
			
		}

		$i++;

}




foreach(@$_SESSION['dplus'] as $key=>$dxmin){
#ubah ke nol hasil akhir
 $nilaid=0; 
$nilaiPre=0;     
$nilai=0;    
    
	$jarakm=$_SESSION['dmin'][$key];
	$id_alt=$_SESSION['id_alt'][$key];
	
	//nama alternatif
	$nama=mysql_query("select * from alternatif where id_alternatif='$id_alt'");
	$nm=mysql_fetch_assoc($nama);
	
    
//echo $jarakm." / <br> ";	
//echo $dxmin." + ";	
//echo $jarakm."<br><br>";	
			
    
    
	 $nilaiPre=$dxmin+$jarakm;
    
	 $nilaid=$jarakm/$nilaiPre;
    
	
		$nilai=round($nilaid,4);
		
	//simpan ke tabel nilai preferensi
	$nm=$nm['nm_alternatif'];
	
	$sql2=mysql_query("insert into nilai_preferensi (nm_alternatif,nilai) values('$nm','$nilai')");
    
    //echo "insert into nilai_preferensi (nm_alternatif,nilai) values('$nm','$nilai')";
		
	
}
 
 //ambil data sesuai dengan nilai tertinggi
 $i=1;
	$sql3=mysql_query("select * from nilai_preferensi  order by nilai desc");
	while($data3=mysql_fetch_assoc($sql3)){
		echo "<tr>
		<td>".$i."</td>
		<td>$data3[nm_alternatif]</td>
		<td>$data3[nilai]</td>
		</tr>";
		
		$i++;
	}
 
 
 //kosongkan tabel nilai preferensi
 $del=mysql_query("delete from nilai_preferensi");

echo "</tr>";
?>

</tbody>
</table>