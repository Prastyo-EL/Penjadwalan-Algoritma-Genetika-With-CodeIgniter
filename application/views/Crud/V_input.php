<!DOCTYPE html>
<html>
<head>
	<title>Membuat CRUD dengan CodeIgniter | MalasNgoding.com</title>
</head>
<body>
	<center>
		<h1>Membuat CRUD dengan CodeIgniter | MalasNgoding.com</h1>
		<h3>Tambah data baru</h3>
	</center>
	<form action="<?php echo base_url(). 'crud/tambah_aksi'; ?>" method="post">
		<table style="margin:20px auto;">
			
			<tr>
				<td>Prodi</td>
				<input type="hidden" name="Id">
				<td><input type="text" name="Prodi"></td>
			</tr>
			<tr>
				<td>Kelas Id Kurikulum</td>
				<td><input type="text" name="Kurikulum"></td>
            </tr>
            <tr>
				<td>Nama Kelas</td>
				<td><input type="text" name="Nama"></td>
			</tr>
			<tr>
            <tr>
				<td>Paralel</td>
				<td><input type="text" name="Kelas >1"></td>
            </tr>
            <tr>
				<td>Jumlah Siswa</td>
				<td><input type="text" name="Jumlah"></td>
            </tr>
            <tr>
				<td>Kelas Merata</td>
				<td><input type="text" name="Wajib/Non"></td>
            </tr>
            <tr>
				<td>Group Kelas</td>
				<td><input type="text" name="Group"></td>
			</tr>
				<td></td>
				<td><input type="submit" value="Tambah"></td>
			</tr>
		</table>
	</form>	
</body>
</html>