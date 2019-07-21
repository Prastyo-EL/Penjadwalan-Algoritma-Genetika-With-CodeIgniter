<div class="">

  <div class="page-title">
    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?php echo base_url();?>"><i class="fa fa-home"></i> Beranda </a>
        <li><a href="<?php echo base_url();?>penjadwalan/kelas">Kelas</a>
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
  </div>

  <div class="clearfix"></div>
    
    <!--Main content-->

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2> <?=$title;?> <small>Kelas</small></h2>
            <div class="clearfix"></div>
          </div>
          <br>
          
          <div class="x_content">
          <form action="<?=$url_submit?>" method="post" class="form-horizontal form-label-left" novalidate>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Batas Minimal Kelas <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="number" name="batas_jml_kelas_min" class="form-control input-sm col-md-3 col-xs-12" required="required" value="5">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah PerKelas <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="number" name="batas_jml_kelas" class="form-control input-sm col-md-3 col-xs-12" required="required" value="50">
              </div>
            </div>

            <br>
            <div class="form-group">
              <div class="col-md-4 col-md-offset-3">
                <button type="submit" class="btn btn-round btn-primary">Generate</button>
                <a href="<?=$url_kelas?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
              </div>
            </div>

            <br>
          </form>
          </div>

        </div>
      </div>
    </div>

    <!--Main content-->

  <br><hr>
  <div class="clearfix"></div>

</div>