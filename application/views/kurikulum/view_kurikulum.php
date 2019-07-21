<script type="text/javascript">
  function change_get(){    
    var semester_tipe = document.getElementById('semester_tipe');
    var tahun_akademik = document.getElementById('tahun_akademik');
    window.location.href = "<?php echo base_url().'pengelolaan/kurikulum/'?>" + semester_tipe.options[semester_tipe.selectedIndex].value  + "/"    + tahun_akademik.options[tahun_akademik.selectedIndex].value;   
  }
</script>

<div class="">

  <div class="page-title">
    <div class="title_left">
      <ul class="breadcrumb">
        <li><a href="<?php echo base_url();?>">Beranda</a>
        <li class="active"><?php echo $title;?></li>
      </ul>
    </div>
  </div>

  <div class="clearfix"></div>
    
    <!--Search Form-->
    <form class="form" method="post" action="<?php echo base_url() . 'pengelolaan/kurikulum_search'?>">
      <div class="form-group">
      <label>Semester</label>
      <select style="max-width:300px" id = "semester_tipe" name="semester_tipe" class="form-control" onchange="change_get()">         
        <option value="1" <?php echo $this->session->userdata('pengampu_semester_tipe') ==='1' ? 'selected':'';?>>Ganjil</option>
        <option value="0" <?php echo $this->session->userdata('pengampu_semester_tipe') ==='0' ? 'selected':'';?>>Genap</option>
      </select>
      </div>
      
      <div class="form-group">
      <label>Tahun Akademik</label>
      <select style="max-width:300px" id="tahun_akademik" name="tahun_akademik" class="form-control" onchange="change_get()">
      <?php foreach($rs_tahun->result() as $th) { ?>
        <option value="<?php echo $th->tahun_akademik;?>"<?php $this->session->userdata('pengampu_tahun_akademik') === $th->tahun_akademik ? 'selected':'';?>><?php echo $th->tahun_akademik;?></option>
      <?php } ?>
      </select>            
      </div>
      
      <br>
      <div class="title_left">
        <div class="col-md-4 col-sm-5 col-xs-12 form-group top_search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for..." name="search_query" value="<?php echo isset($search_query) ? $search_query : '' ;?>">
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit">Go!</button>
            </span>
          </div>
        </div>
      </div>
        <a href="<?php echo base_url() . 'pengelolaan/kurikulum';?>">
          <button type="button" class="btn btn-round btn-default">Muat Ulang</button>
        </a>
      <div class="title_right pull-right">
        <a href="<?php echo base_url() . 'pengelolaan/kurikulum_add';?>">
          <button type="button" class="btn btn-round btn-primary"><i class="fa fa-plus"> Data Baru </i></button>
        </a>
      </div>
    </form>
    <!--End Search Form-->

  <div class="clearfix"></div>
    
    <!--Table of content-->
    <?php if($rs_kurikulum->num_rows() === 0):?>
    <div class="alert alert-error">
      <button type="button" class="close" data-dismiss="alert">x</button>             
      <p>Tidak ada data.</p>
    </div>  
    <?php else: ?> 
    <div id="content_ajax">
      <div class="widget-content table-responsive">
        <table class="table table-striped table-bordered">
          
          <thead>
            <tr>
              <th>No</th>
              <th>Program Keahlian</th>
              <th>Semeter</th>
              <th>Mata Pelajaran</th>
              <th>Kelas</th>
              <th>Guru</th>
              <th>Tahun Akademik</th>
              <th style="width: 90px;text-align: center;">Aksi</th>
            </tr>
          </thead>
          
          <tbody>
          <?php 
            $i =  intval($start_number) + 1;
            foreach ($rs_kurikulum->result() as $kurikulum) { ?>
            <tr>
              <td><?php echo str_pad((int)$i,2,0,STR_PAD_LEFT);?></td>    
              <td>PK</td>                    
              <td>SM</td>                    
              <td><?php echo $kurikulum->nama_mp;?></td>                    
              <td><?php echo $kurikulum->kelas;?></td>
              <td><?php echo $kurikulum->nama_guru;?></td>
              <td><?php echo $kurikulum->tahun_akademik;?></td>                   
              
              <td align="center">
              <a href="<?php echo base_url() . 'pengelolaan/kurikulum_edit/' .$kurikulum->kode;?>"><button class="btn-round btn-warning"><i class="fa fa-edit"></i></button></a>
              <a href="<?php echo base_url() . 'pengelolaan/kurikulum_delete/' .$kurikulum->kode;?>" onClick="return confirm('Anda yakin ingin menghapus data ini?')" ><button class="btn-round btn-danger"><i class="fa fa-trash"></i></button></a>
            </td>
          </tr>
        <?php $i++;} ?>          
          </tbody>

        </table>
      </div>
           
      <div id="ajax_paging">
        <ul class="pagination"><?php echo $this->pagination->create_links();?></ul>
      </div>

    </div>
    <?php endif; ?>
    <!--End Table of content-->

  <br><hr>
  <div class="clearfix"></div>

</div>