<div class="">
  
  <div class="page-title">
    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?php echo base_url();?>"><i class="fa fa-home"></i> Beranda </a>
        <li class="active"><?php echo $title;?></li>
          <div class="dropdown pull-right">
            <button class="btn btn-xs btn-round btn-default dropdown-toggle" data-toggle="dropdown"><?=$this->session->userdata('username');?>
            <span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li class="divider"></li>
              <li><a href="<?=base_url('home')?>"><i class="fa fa-user"></i> Profil </a></li>
              <li class="divider"></li>
              <li><a href="<?=base_url('auth/logout')?>"><i class="fa fa-sign-out"></i> Keluar </a></li>
              <li class="divider"></li>
            </ul>
          </div>
      </ul>
    </div>
    <div class="title_left">
      <h3>Ubah Konfigurasi</h3>
    </div>    
  </div>

  <div class="clearfix"></div>

  <form action="<?=$url_submit?>" enctype="multipart/form-data" method="post">

    <div class="form-group">
      <label">Nama</label>
      <input style="width:200px" class="form-control" type="text" disabled="disabled" value="<?=$data[0]['nama']?>">
      </div>
    </div>

    <div class="form-group">
      <label>Nilai</label>
      <input style="width:200px" class="form-control" type="text" name="nilai" value="<?=$data[0]['nilai']?>">
    </div>

    <br>
    <div class="form-group">
      <input type="hidden" name="id" value="<?=$filter['id']?>" />
      <button class="btn btn-round btn-primary" type="submit">Simpan</button>
      <a href="<?=$url_konfig?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
    </div>

  </form>

  <br><hr>
  <div class="clearfix"></div>

</div>