<?php foreach($rs_mk->result() as $mk) {} ?>
<div class="">
  <div class="page-title">

    <div class="title_left">
      <ul class="breadcrumb">
        <li><a href="<?php echo base_url();?>">Beranda</a>
        <li><a href="<?php echo base_url();?>pengelolaan/mapel">Ubah Mata Pelajaran</a>
        <li class="active"><?php echo $title;?></li>
      </ul>
    </div>

  </div>

  <div class="clearfix"></div>
    
    <!--Main content-->
    <div class="container">
        <?php if(isset($msg)) { ?>                        
          <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>                
            <?php echo $msg;?>
          </div>  
        <?php } ?> 
        <form id="tab" method="post">

          <div class="form-group">
            <label>Kode Matakuliah</label>
            <input style="max-width:300px" id="kode_mk" type="text" value="<?php echo $mk->kode_mk;?>" name="kode_mk" class="form-control"/>
          </div>

          <div class="form-group">
            <label>Nama</label>
            <input style="max-width:300px" id="nama" type="text" value="<?php echo $mk->nama;?>" name="nama" class="form-control"/>
          </div>

          <div class="form-group">
            <label>Kategori</label>
            <select style="max-width:300px" name="jenis" class="form-control">            
              <option value="TEORI" <?php echo $mk->jenis === 'TEORI' ? 'selected':'';?> /> TEORI
              <option value="PRAKTIKUM" <?php echo $mk->jenis === 'PRAKTIKUM' ? 'selected':'';?> /> PRAKTIKUM            
            </select>
          </div>
          
          <div class="form-group">
            <label>SKS</label>
            <input style="max-width:300px" id="sks" type="text" value="<?php echo $mk->sks;?>" name="sks" class="form-control" />
          </div>
          
          <div class="form-group">
            <label>Semester</label>
            <input style="max-width:300px" id="semester" type="text" value="<?php echo $mk->semester;?>" name="semester" class="form-control" />       
          </div>

          <br>
          <div class="form-group">
            <a href="<?php echo base_url() .'pengelolaan/mapel'; ?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
            <button type="submit" class="btn btn-round btn-primary">Simpan</button>
          </div>
        </form>

    </div>
    <!--End Main content-->

  <br><hr>
  <div class="clearfix"></div>

</div>