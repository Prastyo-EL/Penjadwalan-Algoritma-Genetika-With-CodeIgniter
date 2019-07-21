<div class="">
  <div class="page-title">

    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?=base_url();?>"><i class="fa fa-home"></i> Beranda </a>
        <li><a href="<?=base_url();?>pengelolaan/waktu">Waktu</a>
        <li class="active"><?=$title;?></li>
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
    <div class="alert alert-<?=$notif_style?>" <?=$display?>>
      <button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button>
      <?=$notif_message?>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2> <?=$title;?> Waktu</h2>
            <div class="clearfix"></div>
          </div>
          <br>
          
          <div class="x_content">
          <form action="<?=$url_submit?>" method="post" class="form-horizontal form-label-left" novalidate>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Waktu Hari <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <?=$cb_hari?>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Waktu Jam Mulai <span class="required">*</span></label>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <input type="time" name="jam_mulai" class="form-control input-sm col-md-4 col-xs-12" required="required" value="<?=$data['jam_mulai']?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Waktu Jam Mulai <span class="required">*</span></label>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <input type="time" name="jam_selesai" required="required" class="form-control input-sm col-md-4 col-xs-12" value="<?=$data['jam_selesai']?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Waktu Belajar <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <?=$rd_isblj?>
              </div>
            </div>

            <br>
            <div class="form-group">
              <input type="hidden" name="id" value="<?=$filter['id']?>"/>
              <div class="col-md-6 col-md-offset-3">
                <button type="submit" class="btn btn-round btn-primary">Simpan</button>
                <a href="<?=$url_waktu?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
              </div>
            </div>

            <br>
          </form>
          </div>

        </div>
      </div>
    </div>

    <!--End Main content-->

  <br><hr>
  <div class="clearfix"></div>

</div>