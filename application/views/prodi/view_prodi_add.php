<div class="">

  <div class="page-title">
    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?=base_url();?>"><i class="fa fa-home"></i> Beranda </a>
        <li><a href="<?=base_url();?>pengelolaan/prodi">Program Studi</a>
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
      <button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
      <?=$notif_message?>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2> <?=$title;?> Program Studi</h2>
            <div class="clearfix"></div>
          </div>
          <br>
          
          <div class="x_content">
          <form action="<?=$url_submit?>" method="post" class="form-horizontal form-label-left" novalidate>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Prodi <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="text" name="kode" class="form-control input-sm col-md-3 col-xs-12" placeholder="kode program studi" required="required" value="<?=$data['kode']?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Prodi <span class="required">*</span></label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" name="nama" class="form-control input-sm col-md-5 col-xs-12" placeholder="nama program studi" required="required" value="<?=$data['nama']?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Prefix Prodi <span class="required">*</span></label>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" name="akronim" required="required" class="form-control input-sm col-md-5 col-xs-12" placeholder="Prefix Prodi Mata Pelajaran" value="<?=$data['akronim']?>">
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Kelas Prodi & Jumlah Siswa <span class="required">*</span></label>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <input type="checkbox" id="1" name="smt_x" value="1;2" <?=$S1['checked']?> > <label for="1"> Kelas X</label>
                <input type="number" id="x" name="sw_x" value="<?=$S1['jml_siswa']?>" class="form-control input-sm col-md-3 col-xs-12" placeholder="Jumlah Siswa" <?=$S1['disabled']?> >
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="required"></span></label>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <input type="checkbox" id="2" name="smt_xi" value="3;4" <?=$S3['checked']?> > <label for="2"> Kelas XI</label>
                <input type="number" id="xi" name="sw_xi" value="<?=$S3['jml_siswa']?>" class="form-control input-sm col-md-3 col-xs-12" placeholder="Jumlah Siswa" <?=$S3['disabled']?> >
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"><span class="required"></span></label>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <input type="checkbox" id="3" name="smt_xii" value="5;6" <?=$S5['checked']?> > <label for="3"> Kelas XII</label>
                <input type="number" id="xii" name="sw_xii" value="<?=$S5['jml_siswa']?>" class="form-control input-sm col-md-3 col-xs-12" placeholder="Jumlah Siswa" <?=$S5['disabled']?> >
              </div>
            </div>

            <br>
            <div class="form-group">
              <input type="hidden" name="id" value="<?=$filter['id']?>"/>
              <div class="col-md-6 col-md-offset-3">
                <button type="submit" class="btn btn-round btn-primary">Simpan</button>
                <a href="<?=$url_prodi?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
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
<script>
document.getElementById('1').onchange = function() {
document.getElementById('x').enabled = this.checked;
};
</script>