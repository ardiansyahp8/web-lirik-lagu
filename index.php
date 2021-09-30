<?php
session_start();
$uid = isset( $_SESSION['uid'] ) ? $_SESSION['uid'] : '';
$title_kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
$title_lid = isset( $_GET['lid'] ) ? $_GET['lid'] : '';
include "control.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--<link rel="stylesheet" href="style2x.css">-->
  <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet">

<title>
<?php
if( $title_kid ) {
	$tkat = mysql_fetch_array( mysql_query( "SELECT * FROM kategori WHERE kid='{$title_kid}'" ) );
	echo "Kategori &rarr; {$tkat['kategori']} | ".WEB;
} elseif( $title_lid || $title_kid ) {
	$lir = mysql_fetch_array( mysql_query( "SELECT * FROM lirik WHERE lid='{$title_lid}'" ) );
	echo "Lirik &rarr; {$lir['judul']} | ".WEB;
} else {
	echo "Home | ".WEB;
}
?>
</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript">
function konfirmasi(id) {
	var tanya = confirm( "Apakah Anda yakin akan menghapus data dengan ID - " + id + " ?" );
	if( tanya == true ) return true;
	else return false;
}
function cek() {
	var x = document.getElementById('kategori').value;
	if( x == "" ) {
		alert( "Mohon maaf, harap mengisi nama kategori terlebih dahulu..." );
		return false;
	} else {
		return true;
	}
}
</script>
</head>
<body>
  
<?php
if( $uid ) {
	$user = mysql_fetch_array( mysql_query( "SELECT * FROM user WHERE uid='{$uid}'" ) );
	echo "<div class=\"wrap\">\n";
	echo "	<div class=\"wrap-title\">".WEB."</div>\n";
	echo "	<div class=\"wrap-slogan\">Pencarian Lirik Lagu Berbasis <i>Voice Recognition</i></div>\n";
	echo "	<div class=\"welcome\">Welcome, <b>{$user['fullname']}</b> &rarr; <a href=\"".URL."/?module=logout\">Logout</a> &rarr; Anda login pada: ".date( "l, F d, Y H:i:s A" )."</div>\n";
	echo "	<div class=\"page\">\n";
	echo "		<div class=\"left\">\n";
	echo "			<div class=\"box\">\n";
	echo "				<h1>Menu Utama</h1>\n";
	echo "				<ul>\n";
	echo "					<li><a href=\"".URL."/\">Home</a></li>\n";
	echo "					<li><a href=\"".URL."/?module=kategori\">Kategori Lirik</a></li>\n";
	echo "					<li><a href=\"".URL."/?module=cari-lirik\">Cari Lirik</a></li>\n";
	echo "					<li><a href=\"".URL."/?module=paling-top\">Lagu Teratas</a></li>\n";
	echo "					<li><a href=\"".URL."/?module=logout\">Logout</a></li>\n";
	echo "				</ul>\n";
	echo "			</div>\n";
	echo "		</div>\n";
	echo "		<div class=\"right\">\n";

	if( $module == 'kategori' ) {
		$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
		if( $kid ) {
			$detail_kategori = mysql_fetch_array( mysql_query( "SELECT * FROM kategori WHERE kid='{$kid}'" ) );
			$lirik = mysql_query( "SELECT * FROM lirik WHERE kid='{$kid}'" );
			echo "		<div class=\"box\">\n";
			echo "			<h1>Kategori : {$detail_kategori['kategori']}</h1>\n";
			echo "			<p>Lirik lagu untuk kategori - {$detail_kategori['kategori']}";
			if(isset($_SESSION)) { if($user['fullname']=='Administrator'){ echo " &rarr; <a href=\"".URL."/?module=tambah-lirik&kid=$kid\">Tambah Lirik Lagu</a>"; };};
			echo " &rarr; <a href=\"javascript:history.back();\">Kembali ke halaman sebelumnya</a></p>\n";
			echo "			<table border=\"0\">\n";
			echo "			<tr class=\"head\">\n";
			echo "				<td width=\"30\" align=\"center\">NO</td>\n";
			echo "				<td width=\"200\">Judul lagu</td>\n";
			echo "				<td width=\"100\" align=\"center\">Artis</td>\n";
			echo "				<td width=\"100\" align=\"center\">Kategori</td>\n";
			if(isset($_SESSION)) { if($user['fullname']=='Administrator'){ echo "				<td width=\"50\" align=\"center\">Aksi</td>\n"; }; };
			echo "			</tr>\n";

			if( mysql_num_rows( $lirik ) == 0 ) {
				echo "		<tr class=\"no-data\"><td colspan=\"5\">Maaf, belum ada data kategori lirik lagu untuk saat ini</td></tr>\n";
			} else {
				$no = 1;
				while( $data_lirik = mysql_fetch_array( $lirik ) ) {
					echo "	<tr class=\"data\">\n";
					echo "		<td align=\"center\">{$no}</td>\n";
					echo "		<td><b><a href=\"".URL."/?module=lirik&kid={$data_lirik['kid']}&lid={$data_lirik['lid']}\" title=\"Klik untuk melihat detail lirik lagu  - {$data_lirik['judul']} oleh {$data_lirik['artis']}\">{$data_lirik['judul']}</a></b></td>\n";
					echo "		<td align=\"center\">{$data_lirik['artis']}</td>\n";
					echo "		<td align=\"center\">{$detail_kategori['kategori']}</td>\n";
					if(isset($_SESSION)) { if($user['fullname']=='Administrator'){ echo "		<td align=\"center\"><a href=\"".URL."/?module=edit-lirik&kid={$data_lirik['kid']}&lid={$data_lirik['lid']}\" title=\"Edit lirik - {$data_lirik['judul']}\"><img src=\"".URL."/b_edit.png\"></a> &nbsp; <a href=\"".URL."/?module=delete-lirik&kid={$data_lirik['kid']}&lid={$data_lirik['lid']}\" onclick=\"return konfirmasi('".$data_lirik['lid']."')\" title=\"Hapus lirik - {$data_lirik['judul']}\"><img src=\"".URL."/b_drop.png\"></a></td>\n"; };};
					echo "	</tr>\n";
					$no++;
				}
			}
			echo "			</table>\n";
			echo "		</div>\n";
		} else {
			$sql_kategori = mysql_query( "SELECT * FROM kategori" );
			echo "		<div class=\"box\">\n";
			echo "			<h1>Daftar Kategori Lirik Lagu</h1>\n";
			echo "			<form method=\"post\" action=\"\" class=\"form-kategori\" autocomplete=\"off\">\n";
			echo "				<input type=\"text\" name=\"kategori\" style=\"display:none\" id=\"kategori\" placeholder=\"Ketik nama kategori, pisahkan dengan koma untuk penambahan sekaligus\" autofocus><br><input style=\"display:none\" type=\"submit\" name=\"act\" value=\"Simpan Kategori\" onclick=\"return cek();\">\n";
			if( isset( $_SESSION['simpan-kategori']['gagal'] ) ) {
				echo "				<div class=\"cat-error\">Kategori ".$_SESSION['simpan-kategori']['gagal']." sudah ada.</div>\n";
				unset( $_SESSION['simpan-kategori']['gagal'] );
			}
			echo "			</form>\n";
			echo "			<div class=\"clear\"></div><br>\n";
			echo "			<table border=\"0\">\n";
			echo "			<tr class=\"head\">\n";
			echo "				<td width=\"35\" align=\"center\">NO</td>\n";
			echo "				<td width=\"250\">Nama Kategori</td>\n";
			echo "				<td width=\"100\" align=\"center\">Total Lagu</td>\n";
			if(isset($_SESSION)) { if($user['fullname']=='Administrator'){ echo "				<td width=\"50\" align=\"center\">Aksi</td>\n"; };};
			echo "			</tr>\n";

			if( mysql_num_rows( $sql_kategori ) == 0 ) {
				echo "		<tr class=\"no-data\"><td colspan=\"4\">Maaf, belum ada data kategori lirik lagu untuk saat ini</td></tr>\n";
			} else {
				$no = 1;
				while( $data_kategori = mysql_fetch_array( $sql_kategori ) ) {
					$total_lagu = mysql_num_rows( mysql_query( "SELECT * FROM lirik WHERE kid='{$data_kategori['kid']}'" ) );
					echo "	<tr class=\"data\">\n";
					echo "		<td align=\"center\">{$no}</td>\n";
					echo "		<td>Kategori: <b><a href=\"".URL."/?module=kategori&kid={$data_kategori['kid']}\" title=\"Klik untuk melihat daftar lirik lagu di kategori  - {$data_kategori['kategori']}\">{$data_kategori['kategori']}</a></b></td>\n";
					echo "		<td align=\"center\">{$total_lagu}</td>\n";
					if(isset($_SESSION)) { if($user['fullname']=='Administrator'){ echo "		<td align=\"center\"><a href=\"".URL."/?module=edit-kategori&kid={$data_kategori['kid']}\" title=\"Edit kategori lirik - {$data_kategori['kategori']}\"><img src=\"".URL."/b_edit.png\"></a> &nbsp; <a href=\"".URL."/?module=delete-kategori&kid={$data_kategori['kid']}\" onclick=\"return konfirmasi('".$data_kategori['kid']."')\" title=\"Hapus kategori lirik - {$data_kategori['kategori']}\"><img src=\"".URL."/b_drop.png\"></a></td>\n"; };};
					echo "	</tr>\n";
					$no++;
				}
			}
			echo "			</table>\n";
		}
		echo "		</div>\n";
	} elseif( $module == 'edit-kategori' ) {
		$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
		$edit_kategori = mysql_fetch_array( mysql_query( "SELECT * FROM kategori WHERE kid='{$kid}'" ) );
		echo "		<div class=\"box\">\n";
		echo "			<h1>Edit Kategori Lirik Lagu</h1>\n";
		echo "			<p>Silahkan memasukkan nama kategori lirik lagu yang baru lalu tekan tombol Ubah untuk menyimpan data</p>\n";
		echo "			<form method=\"post\" action=\"\" class=\"form-kategori\">\n";
		echo "				<input type=\"text\" name=\"kategori\" placeholder=\"Masukkan nama kategori lirik yang baru\" value=\"{$edit_kategori['kategori']}\"><br><input type=\"submit\" name=\"act\" value=\"Ubah Kategori\">\n";
		if( isset( $_SESSION['simpan-kategori']['gagal'] ) ) {
			echo "				<div class=\"cat-error\">".$_SESSION['simpan-kategori']['gagal']."</div>\n";
			unset( $_SESSION['simpan-kategori']['gagal'] );
		}
		echo "			</form>\n";
		echo "		</div>\n";
	} elseif( $module == 'tambah-lirik' ) {
		$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
		$tolirik = mysql_fetch_array( mysql_query( "SELECT * FROM kategori WHERE kid='{$kid}'" ) );
		echo "		<div class=\"box\">\n";
		echo "			<h1>Tambah Lirik Lagu ke - {$tolirik['kategori']}</h1>\n";
		echo "			<form method=\"post\" action=\"\" class=\"form-lirik\" autocomplete=\"off\">\n";
		echo "				Masukkan Nama Artis:<br><input type=\"text\" name=\"artis\" placeholder=\"Nama Artis\"><br>\n";
		echo "				Masukkan Judul Lagu:<br><input type=\"text\" name=\"judul\" placeholder=\"Judul Lagu\"><br>\n";
		echo "				Masukkan Lirik Lagu:<br><textarea name=\"lirik\" rows=\"10\"></textarea><br>\n";
		echo "				<input type=\"submit\" name=\"act\" value=\"Tambah Lirik\">\n";
		if( isset( $_SESSION['simpan-lirik']['gagal'] ) ) {
			echo "				<div class=\"cat-error\">".$_SESSION['simpan-lirik']['gagal']."</div>\n";
			unset( $_SESSION['simpan-lirik']['gagal'] );
		}
		echo "			</form>\n";
		echo "		</div>\n";
	} elseif( $module == 'edit-lirik' ) {
		$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
		$lid = isset( $_GET['lid'] ) ? $_GET['lid'] : '';
		$edit_lirik = mysql_fetch_array( mysql_query( "SELECT * FROM lirik WHERE kid='{$kid}' AND lid='{$lid}'" ) );
		echo "		<div class=\"box\">\n";
		echo "			<h1>Ubah Lirik Lagu: {$edit_lirik['judul']}</h1>\n";
		echo "			<p><a href=\"javascript:history.back();\">Kembali ke halaman sebelumnya</a></p>\n";
		echo "			<form method=\"post\" action=\"\" class=\"form-lirik\" autocomplete=\"off\">\n";
		echo "				Masukkan Nama Artis:<br><input type=\"text\" name=\"artis\" placeholder=\"Nama Artis\" value=\"{$edit_lirik['artis']}\"><br>\n";
		echo "				Masukkan Judul Lagu:<br><input type=\"text\" name=\"judul\" placeholder=\"Judul Lagu\" value=\"{$edit_lirik['judul']}\"><br>\n";
		echo "				Masukkan Lirik Lagu:<br><textarea name=\"lirik\" rows=\"10\">{$edit_lirik['lirik']}</textarea><br>\n";
		echo "				<input type=\"submit\" name=\"act\" value=\"Edit Lirik\">\n";
		if( isset( $_SESSION['edit-lirik']['gagal'] ) ) {
			echo "				<div class=\"cat-error\">".$_SESSION['edit-lirik']['gagal']."</div>\n";
			unset( $_SESSION['edit-lirik']['gagal'] );
		}
		echo "			</form>\n";
		echo "		</div>\n";
	} elseif( $module == 'cari-lirik' ) {
		echo "		<div class=\"box\">\n";
		echo "			<h1>Cari Lirik Lagu</h1>\n";
		echo "			<p>Anda dapat mengetikkan judul lagu, dan nama artis untuk mencari lirik lagu yang diinginkan.</p>\n";
		echo "			<form method=\"post\" action=\"\" class=\"form-lirik\" autocomplete=\"off\">\n";
		echo "<div style=\"display:none\" class=\"container\"> <!--page container -->
    <div onChange=\"x()\" id=\"textbox\"  class=\"text-box\" contenteditable=\"true\"></div> <!--text box which will contain spoken text -->
    <i onClick=\"y()\" id=\"sekalian\"  class=\"fa fa-microphone\"></i> <!-- microphone icon to be clicked before speaking -->
  </div>
  <script src=\"index.js\"></script> <!-- link to index.js script -->";
  echo "<script type='text/javascript'>
  
  function y()
  {
  window.setTimeout('x()',5000);
  window.setTimeout('w()',6000);
  }
				 function x()
				 {
				 var hasil; //nt=nilai teori, np=nilai praktek
				 hasil=document.getElementById('textbox').textContent;
				
				 document.getElementById('hasilsuara').value=hasil;
				 document.getElementById('hasilsuara').text=hasil;
				 }
				 
				 function z()
				 {
					 document.getElementById('sekalian').click();
				 }

				 function w()
				 {
					 document.getElementById('sub').click();
				 }
				</script>";
		echo "				<input id=\"hasilsuara\" type=\"text\" name=\"data\" placeholder=\"Ketik kata kunci pencarian...\" autofocus><br>\n";
		echo "		<input id=\"sub\" type=\"submit\" name=\"act\" value=\"Cari Lirik\">\n";
		echo "		<img id=\"klikme\" onClick=\"z()\"  src=\"Mic.png\" width=\"35px\" height=\"35px\" style=\"text-align:right; margin-top:-35px; margin-bottom:-10px; margin-left:550px;\"></img>\n";
		echo "			</form>\n";
		if( $act == 'Cari Lirik' ) {
			$data = isset( $_POST['data'] ) ? $_POST['data'] : '';
			$datas = ( $data == "" ) ? "Kata Kunci Pencarian Tidak Disebutkan" : $data;
			$cari_lirik = mysql_query( "SELECT * FROM lirik WHERE artis LIKE '%$data%' OR judul LIKE '%$data%'" );
			echo "			<p>Kata Kunci Pencarian : <b>{$datas}</b> | Jumlah Pencarian : <b>".mysql_num_rows( $cari_lirik )."</b> Data</p>\n";
			echo "			<table border=\"0\">\n";
			echo "			<tr class=\"head\">\n";
			echo "				<td width=\"30\" align=\"center\">NO</td>\n";
			echo "				<td width=\"200\">Judul lagu</td>\n";
			echo "				<td width=\"100\" align=\"center\">Artis</td>\n";
			echo "				<td style=\"display:none\" width=\"100\" align=\"center\">Kategori</td>\n";
			echo "			</tr>\n";

			if( mysql_num_rows( $cari_lirik ) == 21 ) {
				echo "		<tr class=\"no-data\"><td colspan=\"5\">Maaf, kata kunci pencarian tidak disebutkan. Harap mengulangi input kata kunci pencarian.</td></tr>\n";
			} else {
				$no = 1;
				while( $hasil_lirik = mysql_fetch_array( $cari_lirik ) ) {
					$kat = mysql_fetch_array( mysql_query( "SELECT * FROM kategori WHERE kid='{$hasil_lirik['kid']}'" ) );
					echo "	<tr class=\"data\">\n";
					echo "		<td align=\"center\">{$no}</td>\n";
					echo "		<td><b><a href=\"".URL."/?module=lirik&kid={$hasil_lirik['kid']}&lid={$hasil_lirik['lid']}\" title=\"Klik untuk melihat detail lirik lagu  - {$hasil_lirik['judul']} oleh {$hasil_lirik['artis']}\">{$hasil_lirik['judul']}</a></b></td>\n";
					echo "		<td align=\"center\">{$hasil_lirik['artis']}</td>\n";
					echo "		<td style=\"display:none\" align=\"center\"><a href=\"".URL."/?module=kategori&kid={$kat['kid']}\">{$kat['kategori']}</a></td>\n";
					echo "	</tr>\n";
					$no++;
				}
			}

			if( mysql_num_rows( $cari_lirik ) == 0 ) {
				echo "		<tr class=\"no-data\"><td colspan=\"5\">Maaf, lirik lagu tersebut untuk saat ini belum ada</td></tr>\n";
			}
			echo "			</table>\n";
		}
		echo "		</div>\n";
	} elseif( $module == 'lirik' ) {
		$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
		$lid = isset( $_GET['lid'] ) ? $_GET['lid'] : '';
		mysql_query( "UPDATE lirik SET dilihat=dilihat+1 WHERE kid='{$kid}' AND lid='{$lid}'" );
		$view_lirik = mysql_fetch_array( mysql_query( "SELECT * FROM lirik l,kategori k WHERE l.kid=k.kid AND l.kid='{$kid}' AND l.lid='{$lid}'" ) );
		echo "		<div class=\"box\">\n";
		echo "			<h1>Lirik: {$view_lirik['judul']}</h1>\n";
		echo "			<p class=\"info\">Artis : <b>{$view_lirik['artis']}</b> | Kategori: <b><a href=\"".URL."/?module=kategori&kid={$view_lirik['kid']}\">{$view_lirik['kategori']}</a></b> | Dibuat: ".date( "d-m-Y", $view_lirik['dibuat'] )." | Diubah: ".date( "d-m-Y", $view_lirik['diubah'] )." | Dilihat : {$view_lirik['dilihat']} kali</p>\n";
		echo "			<p class=\"lirik\">".nl2br( $view_lirik['lirik'] )."</p>\n";
		echo "		</div>\n";
	} elseif( $module == 'lirik-terbaru' ) {
		$terbaru = mysql_query( "SELECT * FROM lirik GROUP BY dibuat DESC LIMIT 20" );
		echo "		<div class=\"box\">\n";
		echo "			<h1>Lirik Lagu Terbaru dan Tergress</h1>\n";
		echo "			<table border=\"0\">\n";
		echo "			<tr class=\"head\">\n";
		echo "				<td width=\"30\" align=\"center\">NO</td>\n";
		echo "				<td width=\"200\">Judul lagu</td>\n";
		echo "				<td width=\"100\" align=\"center\">Artis</td>\n";
		echo "				<td width=\"100\" align=\"center\">Kategori</td>\n";
		echo "				<td width=\"50\" align=\"center\">Dilihat</td>\n";
		echo "			</tr>\n";

		if( mysql_num_rows( $terbaru ) == 0 ) {
			echo "		<tr class=\"no-data\"><td colspan=\"5\">Maaf, belum ada data kategori lirik lagu untuk saat ini</td></tr>\n";
		} else {
			$no = 1;
			while( $tlirik = mysql_fetch_array( $terbaru ) ) {
				$xlirik = mysql_fetch_array( mysql_query( "SELECT * FROM kategori WHERE kid='{$tlirik['kid']}'" ) );
				echo "	<tr class=\"data\">\n";
				echo "		<td align=\"center\">{$no}</td>\n";
				echo "		<td><b><a href=\"".URL."/?module=lirik&kid={$tlirik['kid']}&lid={$tlirik['lid']}\" title=\"Klik untuk melihat detail lirik lagu  - {$tlirik['judul']} oleh {$tlirik['artis']}\">{$tlirik['judul']}</a></b></td>\n";
				echo "		<td align=\"center\">{$tlirik['artis']}</td>\n";
				echo "		<td align=\"center\">{$xlirik['kategori']}</td>\n";
				echo "		<td align=\"center\">{$tlirik['dilihat']}</td>\n";
				echo "	</tr>\n";
				$no++;
			}
		}
		echo "			</table>\n";
		echo "		</div>\n";
	}  elseif( $module == 'paling-top' ) {
		$terbaru = mysql_query( "SELECT * FROM lirik WHERE dilihat != '0' ORDER BY dilihat DESC LIMIT 20" );
		echo "		<div class=\"box\">\n";
		echo "			<h1>Lirik Lagu Teratas</h1>\n";
		echo "			<table border=\"0\">\n";
		echo "			<tr class=\"head\">\n";
		echo "				<td width=\"30\" align=\"center\">NO</td>\n";
		echo "				<td width=\"200\">Judul lagu</td>\n";
		echo "				<td width=\"100\" align=\"center\">Artis</td>\n";
		echo "				<td width=\"100\" align=\"center\">Kategori</td>\n";
		echo "				<td width=\"50\" align=\"center\">Dilihat</td>\n";
		echo "			</tr>\n";

		if( mysql_num_rows( $terbaru ) == 0 ) {
			echo "		<tr class=\"no-data\"><td colspan=\"5\">Maaf, belum ada data kategori lirik lagu untuk saat ini</td></tr>\n";
		} else {
			$no = 1;
			while( $tlirik = mysql_fetch_array( $terbaru ) ) {
				$xlirik = mysql_fetch_array( mysql_query( "SELECT * FROM kategori WHERE kid='{$tlirik['kid']}'" ) );
				echo "	<tr class=\"data\">\n";
				echo "		<td align=\"center\">{$no}</td>\n";
				echo "		<td><b><a href=\"".URL."/?module=lirik&kid={$tlirik['kid']}&lid={$tlirik['lid']}\" title=\"Klik untuk melihat detail lirik lagu  - {$tlirik['judul']} oleh {$tlirik['artis']}\">{$tlirik['judul']}</a></b></td>\n";
				echo "		<td align=\"center\">{$tlirik['artis']}</td>\n";
				echo "		<td align=\"center\"><a href=\"".URL."/?module=kategori&kid={$tlirik['kid']}\" title=\"Klik untuk melihat daftar lirik lagu  di kategori - {$xlirik['kategori']}\">{$xlirik['kategori']}</a></td>\n";
				echo "		<td align=\"center\">{$tlirik['dilihat']}</td>\n";
				echo "	</tr>\n";
				$no++;
			}
		}
		echo "			</table>\n";
		echo "		</div>\n";
	} else {
		echo "		<div class=\"box\">\n";
		echo "			<h1>Selamat Datang</h1>\n";
		echo "			<p>Teknologi saat ini telah banyak digunakan tidak hanya untuk kebutuhan pekerjaan atau administratif, namun juga menjadi sarana hiburan <i>(entertainment)</i>. Hiburan yang ada pun beragam, mulai dari yang berupa grafik, musik, maupun video. Dalam dunia musik misalnya, karya-karya dapat dinikmati melalui irama maupun makna yang terkandung di dalamnya, misalnya makna melalui lirik lagu. Namun, seringkali penikmat musik yang ingin menyenandungkan suatu lagu kesulitan karena tidak hafal liriknya.<br><br>Saat ini, solusi yang ada ialah dengan mengetikkan secara manual untuk mencari lirik tersebut. Namun hal tersebut dirasa terlalu sulit terlebih dapat saja terjadi kesalahan penulisan. Dibutuhkan sistem pencarian yang lebih mudah dan dapat dilakukan tanpa perlu repot, maka salah satu solusinya ialah dengan menerapkan pencarian lirik lagu menggunakan pengenalan suara <i>(voice recognition)</i>.</p>\n";
		echo "			<p>Manfaat yang diharapkan melalui pembuatan aplikasi ini ialah sebagai berikut:</p>\n";
		echo "			<ol>\n";
		echo "				<li>1. Diharapkan dapat dalam mencari lirik lagu menggunakan suara berbasis <i>voice recognition</i></li>\n";
		echo "				<li>2. Diharapkan pengguna dapat mencari lirik lagu dengan mudah tanpa mengetik secara manual</li>\n";
		echo "				<li>3. Diharapkan dapat menjadi acuan ke depan untuk mengembangkan teknologi sistem <i>voice recognition</i> yang mampu memahami suara</i></li>\n";
		echo "			</ol>\n";
		echo "		</div>\n";
	}

	echo "		</div>\n";
	echo "		<div class=\"clear\">\n";
	echo "	</div>\n";

	echo "	<div class=\"footer\">Aplikasi Web Pencarian Lirik Lagu Berbasis <i>Voice Recognition</i>. &copy; 2019</div>\n";
	echo "</div>\n";
} else {
	echo "<div class=\"login-box\">\n";
	echo "	<div class=\"title\">".WEB."</div>\n";
	echo "	<div class=\"sub-title\">Aplikasi Pencarian Lirik Lagu</div>\n";
	echo "	<div class=\"form-login\">\n";
	echo "		<form method=\"post\" action=\"\" autocomplete=\"off\">\n";
	echo "			Masukkan Username Anda:<br><input type=\"text\" name=\"username\" placeholder=\"Username...\" autofocus><br>\n";
	echo "			Masukkan Password Anda:<br><input type=\"password\" name=\"password\" placeholder=\"Password...\"><br>\n";
	echo "			<input type=\"submit\" name=\"act\" value=\"Login Pengguna\">\n";

	if( isset( $_SESSION['login']['gagal'] ) ) {
		echo "		<div class=\"error\">".$_SESSION['login']['gagal']."</div>\n";
		unset( $_SESSION['login']['gagal'] );
	}

	echo "		</form>\n";
	echo "	</div>\n";
	echo "</div>\n";
	echo "<div class=\"copy\">Aplikasi Web Pencarian Lirik Lagu Berbasis <i>Voice Recognition</i>. &copy; 2019</div>\n";
}
?>

</body>
</html>
