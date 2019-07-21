<script type="text/javascript">
function get_matakuliah() {   
  var semester_tipe = document.getElementById('semester_tipe');
  $.ajax({
     type:"POST",
     async   : false,
     cache   : false,
     url: "<?php echo base_url()?>pengelolaan/mapel_ajax/" + semester_tipe.options[semester_tipe.selectedIndex].value,
     success: function(){
      $('mapel_ajax').html();
     }
  });
  return false;
}
</script>

<div class="">
  <div class="page-title">

    <div class="title_left">
      <ul class="breadcrumb">
        <li><a href="<?php echo base_url();?>">Beranda</a>
        <li><a href="<?php echo base_url();?>pengelolaan/kurikulum">Kurikulum</a>
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
            <label>Semester</label>
            <select style="max-width:300px" id = "semester_tipe" name="semester_tipe" class="form-control" onchange="get_matakuliah();">         
              <option value="1" <?php echo isset($semester_tipe) ? ($semester_tipe === '1' ? 'selected':'') : '' ;?> /> GANJIL
              <option value="0" <?php echo isset($semester_tipe) ? ($semester_tipe === '0' ? 'selected':'') : '' ;?> /> GENAP
            </select>
          </div>

          <div class="form-group">
            <label>Mata Pelajaran</label>
            <select style="max-width:300px" name="kode_mp" class="form-control" id="mapel_ajax">
              <?php foreach($rs_mp->result() as $mp) { ?>
                <option value="<?php echo $mp->kode;?>" <?php echo set_select('kode_mp',$mp->kode);?> /> <?php echo $mp->nama;?>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label>Guru</label>
            <select style="max-width:300px" name="kd_guru" class="form-control">            
              <?php foreach($rs_guru->result() as $guru) { ?>
                <option value="<?php echo $guru->kode;?>" <?php echo set_select('kode_guru',$guru->kode);?> /> <?php echo $guru->nama;?>
              <?php } ?>
            </select>
          </div>
          
          <div class="form-group">
            <label>Kelas</label>
            <input style="max-width:300px" id="sks" type="text" value="<?php echo set_value('sks');?>" name="sks" class="form-control" />
          </div>
          
          <div class="form-group">
            <label>Tahun Akademik</label>
            <select style="max-width:300px" class="form-control">
              <option value=""></option>
            </select>
          </div>

          <br>
          <div class="form-group">
            <a href="<?php echo base_url() .'pengelolaan/kurikulum'; ?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
            <button type="submit" class="btn btn-round btn-primary">Simpan</button>
          </div>
        </form>

    </div>
    <!--End Main content-->

  <br><hr>
  <div class="clearfix"></div>

</div>