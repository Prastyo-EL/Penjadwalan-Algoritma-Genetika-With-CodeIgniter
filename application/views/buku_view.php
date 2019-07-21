<!doctype html>
<html>
<head>
 <meta  charset="utf-8"/>
 <title><?php  echo  $judul; ?></title>
 <style>
  body{
   font: 10px verdana
  }
  table{
   width:100%; border:1px solid #ccc; align:left; 
  }
  th{
   background:blue;   padding:3px; color:#fff;
  }
  td{
   border:1px solid #ccc;
  }
h1 {
   font:20px verdana;
  }
  hr{
   margin-bottom:20px;
  }
  h3 a{
   font:10px verdana;
   padding:5px;
   background:red;
   text-decoration:none;
   color:#fff;
  }
  h3 a:hover{
   background:green;
  }
 </style>
</head>
<body>
 <h1>BUKU</h1>
 <hr>
 <h3><?php echo '<a href="'.base_url().'buku/add_buku">Add Buku</a>'?></h3>
 <table>
  <tr>
   <th align="left">Kode Buku</th>
   <th align="left">Nama Buku</th>
   <th align="left">Judul Buku</th>
   <th align="left">Pengarang</th>
   <th align="left">Jumlah Halaman</th>
   <th align="left">Penerbit</th>
   <th align="left">Tahun Terbit</th>   
   <th colspan="2">Aksi</th>
  </tr>
  <?php
   foreach($databuku as $buku){
  ?>
  <tr>
   <td><?php echo $buku->kode_buku; ?></td>
   <td><?php echo $buku->nama_buku; ?></td>
   <td><?php echo $buku->judul_buku; ?></td>
   <td><?php echo $buku->pengarang; ?></td>
   <td align="center"><?php echo $buku->jumlah_halaman; ?></td>
   <td><?php echo $buku->penerbit; ?></td>
   <td><?php echo $buku->tahun_terbit; ?></td>  
   <td><?php echo '<a href="'.base_url().'buku/delete_buku/'.$buku->kode_buku.'" onclick="return confirm(\'Anda yakin akan menghapus JUDUL BUKU '.$buku->judul_buku.'?\')">Delete</a>'?></td>
   <td><?php echo '<a href="'.base_url().'buku/edit_buku/'.$buku->kode_buku.'">Edit</a>'?></td>
</tr>
<?php } ?>
</table>
</body>
</html>