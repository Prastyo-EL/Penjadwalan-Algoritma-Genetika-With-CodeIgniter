<div class="">
  
  <div class="page-title">
    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?=base_url();?>"><i class="fa fa-home"></i> Beranda </a>
        <li><a href="<?=base_url();?>pengelolaan/mapel"> Mata Pelajaran </a>
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
            <h2><?=$title?> Mata Pelajaran</h2>
            <div class="clearfix"></div>
          </div>
          <br>
          
          <div class="x_content">
          <form action="<?=$url_submit?>" method="post" class="form-horizontal form-label-left" novalidate>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Mapel <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="text" name="kode" class="form-control input-sm col-md-3 col-xs-12" placeholder="kode mata pelajaran" required="required" value="<?=$data['kode']?>" autofocus>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Mapel <span class="required">*</span></label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" name="nama" class="form-control input-sm col-md-5 col-xs-12" placeholder="nama mata pelajaran" required="required" value="<?=$data['nama']?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah SKS <span class="required">*</span></label>
              <div class="col-md-1 col-sm-1 col-xs-12">
                <input type="number" name="sks" required="required" data-validate-minmax="1,6" class="form-control input-sm col-md-2 col-xs-12" value="<?=$data['sks']?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Pertemuan <span class="required">*</span></label>
              <div class="col-md-1 col-sm-1 col-xs-12">
                <input type="number" name="jml_pert" class="form-control input-sm col-md-2 col-xs-12" required="required" value="<?=$data['jml_pert']?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Semester <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <?=$cb_smt?>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Paket Semester <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <?=$cb_paket?>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Sifat <span class="required">*</span></label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <?=$rd_sft?>
              </div>
            </div>
            
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Universal <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <?=$rd_univers?>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Mapel <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <?=$rd_jns?>
              </div>
            </div>

            <input type="hidden" name="format" class="form-control input-sm col-md-2 col-xs-12" value="(NULL)">

            <input type="hidden" name="maks_kelas" class="form-control input-sm col-md-2 col-xs-12" value="50">

            <br>
            <div class="form-group">
              <input type="hidden" name="id" value="<?=$filter['id']?>"/>
              <div class="col-md-6 col-md-offset-3">
                <button type="submit" class="btn btn-round btn-primary">Simpan</button>
                <a href="<?=$url_mapel?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
              </div>
            </div>

          </form>
          </div>

        </div>
      </div>
    </div>

    <!--End Main content-->

  <br><hr>
  <div class="clearfix"></div>

</div>