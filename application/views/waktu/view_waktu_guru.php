<div class="">
  
  <div class="page-title">
    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?=base_url();?>"><i class="fa fa-home"></i> Beranda </a>
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

    <!--Search Form-->
    <form method="post" action="<?=base_url().'pengelolaan/waktu_guru'?>">
      <div class="title_left">
        <div class="col-md-3 col-sm-4 col-xs-12 form-group pull-left top_search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Pencarian..." name="search_query" value="<?=isset($search_query) ? $search_query : '' ;?>">
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </div>
      </div>
        <a href="<?=base_url() . 'pengelolaan/waktu_guru';?>">
          <button type="button" class="btn btn-round btn-default"><i class="fa fa-spinner"></i> Muat Ulang </button>
        </a>
      <div class="pull-right">
        <a href="<?=$url_add?>">
          <button type="button" class="btn btn-round btn-primary"><i class="fa fa-plus-circle"></i> Data Baru </button>
        </a>
      </div>
    </form>
    <!--Search Form-->

  <div class="clearfix"></div>
    
    <!--Table of content-->

    <div class="alert alert-<?=$notif_style?>" <?=$display?>>
      <button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button>
      <?=$notif_message?>
    </div>

    <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>NIP</th>
          <th>Nama</th>                               
          <th style="width:150px;text-align:center;">Aksi</th>                                       
        </tr>
      </thead>   
      <tbody>
        <?php 
        $no = $filter['start']+1; 
        if (!empty($data)) {
          foreach ($data as $key => $value) { 
            ?>
            <tr>
              <td><?=$no++?></td>
              <td><?=$value['nip']?></td>
              <td><?=$value['nama']?></td>
              <td align="center">
                <a href="<?=$url_edit.'?id='.$value['id']?>" class="btn btn-xs btn-round btn-warning"><i class="fa fa-edit"></i> Ubah </a>
                <a href="<?=$url_del.'/'.$value['id']?>" class="btn btn-xs btn-round btn-danger" onClick="return confirm('Anda yakin ingin menghapus data ini ?')"><i class="fa fa-trash"></i> Hapus </a>
              </td>                                       
            </tr>
            <?php 
          }
        }else{
          $filter['start'] = $filter['start'] - 1;
          ?>
          <tr>
            <td colspan="5" style="text-align:center;font-style:oblique"> -- Tidak ada data --</td>
          </tr>
          <?php
        } 
        ?>
      </tbody>
    </table>
    </div>

    <div class="">
      <span class="badge">Showing <?=$filter['start']+1?> to <?=($no-1)?> of <?=($jumlah_data)?> entries</span>
      <ul style="margin:0px" class="pagination pull-right"><?=$paging?></ul>
    </div>

    <!--Table of content-->

  <br><hr>
  <div class="clearfix"></div>

</div>