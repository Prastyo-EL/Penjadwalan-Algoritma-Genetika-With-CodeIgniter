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
    <?php foreach($kelas as $u){ ?>
	<form action="<?php echo base_url(). 'crud/update'; ?>" method="post">
		<table style="margin:20px auto;">
			<tr>
            <td>Prodi</td>
            <td>
				<input type="hidden" name="Id" value="<?php echo $u->kls_id ?>">
				<input type="text" name="Prodi" value="<?php echo $u->kls_kode_prodi ?>">
            </td>
            </tr>
			<tr>
				<td>Kelas Id Kurikulum</td>
				<td><input type="text" name="Kurikulum" value="<?php echo $u->kls_mpkur_id ?>" ></td>
            </tr>
            <tr>
				<td>Nama Kelas</td>
				<td><input type="text" name="Nama" value="<?php echo $u->kls_nama ?>" ></td>
			</tr>
			<tr>
            <tr>
				<td>Paralel</td>
				<td><input type="text" name="Kelas >1" value="<?php echo $u->kls_kode_paralel ?>"></td>
            </tr>
            <tr>
				<td>Jumlah Siswa</td>
				<td><input type="text" name="Jumlah" value="<?php echo $u->kls_jml_peserta_prediksi ?>"></td>
            </tr>
            <tr>
				<td>Kelas Merata</td>
				<td><input type="text" name="Wajib/Non" value="<?php echo $u->kls_jadwal_merata ?>"></td>
            </tr>
            <tr>
				<td>Group Kelas</td>
				<td><input type="text" name="Group" value="<?php echo $u->kls_id_grup_jadwal ?>"></td>
			</tr>
				<td></td>
				<td><input type="submit" value="Save"></td>
			</tr>
		</table>
    </form>	
    <?php } ?>
</body>
</html>