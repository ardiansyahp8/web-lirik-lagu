<?php
date_default_timezone_set( 'Asia/Jakarta' );
mysql_connect( 'localhost', 'root', '' );
mysql_select_db( 'web-lirik' );
define( 'WEB', 'Website Lirik Lagu' );
define( 'URL', 'http://localhost/web-lirik-lagu' );
define( 'URL2', 'http://localhost/web-lirik-lagu' );
$act = isset( $_POST['act'] ) ? $_POST['act'] : '';
$module = isset( $_GET['module'] ) ? $_GET['module'] : '';

// cek login
if( $act == 'Login Pengguna' ) {
	$username = isset( $_POST['username'] ) ? $_POST['username'] : '';
	$password = isset( $_POST['password'] ) ? $_POST['password'] : '';
	$salah = array();
	if( empty( $username ) || empty( $password ) ) { $salah[] = 'Masukkan username dan password Anda.'; }
	if( !count( $salah ) ) {
		$data = mysql_fetch_array( mysql_query( "SELECT * FROM user WHERE username='{$username}' AND password='".md5( $password )."'" ) );
		if( $data ) {
			$_SESSION['uid'] = $data['uid'];
		} else {
			$salah[] = 'Maaf, password Anda salah. Coba ulangi lagi.';
		}
	}
	if( count( $salah ) ) { $_SESSION['login']['gagal'] = implode( '<br>', $salah ); }
	if($_SESSION['uid'] == '2'){
	header( "Location: ".URL );
	}else {
		header("Location:".URL2);
	}
	exit;
} elseif( $module == 'logout' ) {
	session_destroy();
	header( "Location: ".URL2 );
	exit;
} elseif( $act == 'Simpan Kategori' ) {
	$salah = array();
	$kategori = isset( $_POST['kategori'] ) ? $_POST['kategori'] : '';
	$kategori_array = explode( ',', $kategori );
	for( $i = 0; $i < count( $kategori_array ); $i++ ) {
		//if( empty( $kategori_array[$i] ) ) { $salah[] = 'Harap mengisi nama kategori sebelum mengklik tombol Simpan'; }
		if( mysql_num_rows( mysql_query( "SELECT * FROM kategori WHERE kategori='{$kategori_array[$i]}'" ) ) == 0 ) {
			mysql_query( "INSERT INTO kategori VALUES( '', '{$kategori_array[$i]}' )" );
		} else {
			$salah[] = $kategori_array[$i];
		}
	}
	if( count( $salah ) ) { $_SESSION['simpan-kategori']['gagal'] = implode( ', ', $salah ); }
	header( "Location: ".URL."/?module=kategori" );
	exit;
} elseif( $act == 'Ubah Kategori' ) {
	$salah = array();
	$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
	$kategori = isset( $_POST['kategori'] ) ? $_POST['kategori'] : '';
	$sql = mysql_fetch_array( mysql_query( "SELECT * FROM kategori WHERE kid='{$kid}'" ) );
	$kategori = ( $kategori == "" ) ? $sql['kategori'] : $kategori;
	if( !count( $salah ) ) {
		mysql_query( "UPDATE kategori SET kategori='{$kategori}' WHERE kid='{$kid}'" );
	}
	if( count( $salah ) ) { $_SESSION['ubah-kategori']['gagal'] = implode( ', ', $salah ); }
	if( count( $salah ) ) {
		header( "Location: ".URL."/?module=edit-kategori&kid=$kid" );
	} else {
		header( "Location: ".URL."/?module=kategori" );
	}
	exit;
} elseif( $module == 'delete-kategori' ) {
	$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
	mysql_query( "DELETE FROM kategori WHERE kid='{$kid}'" );
	header( "Location: ".URL."/?module=kategori" );
	exit;
} elseif( $act == 'Tambah Lirik' ) {	
	$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
	$judul = isset( $_POST['judul'] ) ? $_POST['judul'] : '';
	$artis = isset( $_POST['artis'] ) ? $_POST['artis'] : '';
	$lirik = isset( $_POST['lirik'] ) ? $_POST['lirik'] : '';
	$salah = array();
	$time = time();
	if( empty( $judul ) || empty( $artis ) || empty( $lirik ) ) { $salah[] = 'Harap memasukkan judul lagu, nama artis dan isi lirik lagu.'; }
	if( !count( $salah ) ) {
		if( mysql_num_rows( mysql_query( "SELECT * FROM lirik WHERE judul='{$judul}'" ) ) == 0 ) {
			mysql_query( "INSERT INTO lirik VALUES( '', '{$kid}', '{$artis}', '{$judul}', '{$lirik}', '{$time}', '{$time}', '0' )" );
		} else {
			$salah[] = 'Maaf, judul lagu ini sudah ada sebelumnya. Coba yang lain.';
		}
	}
	if( count( $salah ) ) { $_SESSION['simpan-lirik']['gagal'] = implode( ', ', $salah ); }
	if( count( $salah ) ) {
		header( "Location: ".URL."/?module=tambah-lirik&kid=$kid" );
	} else {
		header( "Location: ".URL."/?module=kategori&kid=$kid" );
	}
	exit;
} elseif( $act == 'Edit Lirik' ) {
	$lid = isset( $_GET['lid'] ) ? $_GET['lid'] : '';
	$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
	$sql_lirik = mysql_fetch_array( mysql_query( "SELECT * FROM lirik WHERE kid='{$kid}' AND lid='{$lid}'" ) );
	$judul = isset( $_POST['judul'] ) ? $_POST['judul'] : '';
	$artis = isset( $_POST['artis'] ) ? $_POST['artis'] : '';
	$lirik = isset( $_POST['lirik'] ) ? $_POST['lirik'] : '';
	$salah = array();
	$time = time();
	$judul = ( $judul == "" ) ? $sql_lirik['judul'] : $judul;
	$artis = ( $artis == "" ) ? $sql_lirik['artis'] : $artis;
	$lirik = ( $lirik == "" ) ? $sql_lirik['lirik'] : $lirik;
	if( !count( $salah ) ) {
		mysql_query( "UPDATE lirik SET artis='{$artis}', judul='{$judul}', lirik='{$lirik}', diubah='{$time}' WHERE lid='{$lid}' AND kid='{$kid}'" );
	}
	if( count( $salah ) ) { $_SESSION['edit-lirik']['gagal'] = implode( ', ', $salah ); }
	if( count( $salah ) ) {
		header( "Location: ".URL."/?module=edit-lirik&kid=$kid&lid=$lid" );
	} else {
		header( "Location: ".URL."/?module=kategori&kid=$kid" );
	}
	exit;
} elseif( $module == 'delete-lirik' ) {
	$lid = isset( $_GET['lid'] ) ? $_GET['lid'] : '';
	$kid = isset( $_GET['kid'] ) ? $_GET['kid'] : '';
	mysql_query( "DELETE FROM lirik WHERE lid='{$lid}' AND kid='{$kid}'" );
	header( "Location: ".URL."/?module=kategori&kid=$kid" );
	exit;
}
?>	