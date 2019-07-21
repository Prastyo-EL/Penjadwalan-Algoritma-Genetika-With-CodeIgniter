<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<center><h1>Belajar View</h1></center>
	<center><?php echo anchor('crud/tambah','Tambah Data'); ?></center>
	<table style="margin:20px auto;" border="1">
		<tr>
			<th>Id Kelas</th>
			<th>Kelas Kode Prodi</th>
			<th>Kelas Id Mp Kurikulum</th>
			<th>Nama Kelas</th>
			<th>Kode Paralel</th>
			<th>Jumlah Siswa</th>
			<th>Kelas Merata</th>
			<th>Group Kelas</th>
			<th>Action</th>
		</tr>
		<?php 
		$kls_id = 1;
		foreach($kelas as $u){ 
		?>
		<tr>
			<td><?php echo $kls_id++ ?></td>
			<td><?php echo $u->kls_kode_prodi ?></td>
			<td><?php echo $u->kls_mpkur_id ?></td>
			<td><?php echo $u->kls_nama ?></td>
			<td><?php echo $u->kls_kode_paralel ?></td>
			<td><?php echo $u->kls_jml_peserta_prediksi ?></td>
			<td><?php echo $u->kls_jadwal_merata ?></td>
			<td><?php echo $u->kls_id_grup_jadwal ?></td>
			<td>
			      <?php echo anchor('crud/edit/'.$u->kls_id,'Edit'); ?>
                              <?php echo anchor('crud/hapus/'.$u->kls_id,'Hapus'); ?>
			</td>
		</tr>
		<?php } ?>
	</table>
</body>
</html>