<div class="">

  <div class="page-title">
    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?=base_url();?>"><i class="fa fa-home"></i> Beranda </a>
        <li><a href="<?=base_url();?>pengelolaan/waktu_guru">Waktu Guru</a>
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
            <h2><?=$title;?> Guru</h2>
            <div class="clearfix"></div>
          </div>
          <br>
          
          <div class="x_content">
          <form action="<?=$url_submit?>" method="post" class="form-horizontal form-label-left" novalidate>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Guru <span class="required">*</span></label>
              <div class="col-md-3 col-sm-3 col-xs-8">
              <?php
              if ($filter['id']=='') {
                  echo '<input id="nama" name="nama" type="text" value="'.$data["nama"].'" class="form-control input-sm col-md-4">';
                }else{
                  echo '<input id="nama" name="nama" type="text" value="'.$data["nama"].'" class="form-control input-sm col-md-4" readonly>';
                }
              ?>
              </div>
              <input type="hidden" value="<?=$data['id_guru']?>" name="id_guru" id="id_guru"/>
              <div class="col-md-1 col-sm-2 col-xs-3">
              <?php
              if ($filter['id']=='') {  
                echo '<a class="get_guru" href="'.$url_pilih_guru.'"><button type="button" class="btn btn-sm btn-round btn-info">Pilih Guru</button></a>';
                }
              ?>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Waktu <span class="required">*</span></label>
              
              <div class="col-md-5 col-sm-5 col-xs-12">
                <a class="get_waktu" href="<?=$url_pilih_waktu?>"><button type="button" class="btn btn-sm btn-round btn-info">Pilih waktu</button></a>
                <br><br>
                <div class="table-responsive">
                <table id="table_time" class="table table-bordered table-striped">
                  
                  <thead>
                  <tr>
                    <th>Jam ke-</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th style="width:70px;text-align:center;">Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?=$tabel_waktu?>
                  </tbody>
                </table>
                </div>
              </div>
            </div>

            <br>
            <div class="form-group">
              <input type="hidden" name="id" value="<?=$filter['id']?>"/>
              <div class="col-md-6 col-md-offset-3">
                <button type="submit" class="btn btn-round btn-primary">Simpan</button>
                <a href="<?=$url_waktu_guru?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
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

<div id="responsive" class="modal fade" tabindex="-1"></div>
<div id="waktu" class="modal fade" tabindex="-1"></div>

</div>

<script type="text/javascript">
  $('.remove').live("click",function() {
    tr = $(this).parents('tr');
    test = tr.attr('class');
     alert(test);
    tr.remove();
    return false;
  });
</script>