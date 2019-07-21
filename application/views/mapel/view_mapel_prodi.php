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
    <form method="post" action="<?=base_url() . 'pengelolaan/mapel_prodi'?>">
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
      <a href="<?=base_url() . 'pengelolaan/mapel_prodi';?>">
        <button type="button" class="btn btn-round btn-default"><i class="fa fa-spinner"></i> Muat Ulang </button>
      </a>
    </form>
    <!--Search Form-->

  <div class="clearfix"></div>
    
    <!--Table of content-->
    <div class="alert alert-<?=$notif_style?>" <?=$display?>>
      <button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button>
      <?=$notif_message?>
    </div>

    <div class="table-responsive">
    <table class="table table-bordered table-striped table-condensed table-nonfluid">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode Mapel</th>
          <th>Mata Pelajaran</th>
          <th>Paket semester</th>
          <th>Semester</th>                                    
          <th>SKS</th>
          <th>Program Studi</th>
          <th style="width:70px;text-align:center;">Aksi</th>                                       
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
              <td><?=$value['kode']?></td>
              <td><?=$value['nama']?></td>
              <td><?=$value['paket']?></td>
              <td><?=$value['smt']?></td>
              <td><?=$value['sks']?></td>
              <td><?=$value['nama_prodi']?></td>
              <td align="center">
                <a style="<?=$value['display']?>" href="<?=$url_edit.'?id='.$value['id']?>"><button class="btn btn-xs btn-round btn-warning"><i class="fa fa-edit"></i> Ubah </button></a>
              </td>                                       
            </tr>
            <?php 
          }
        }else{
          $filter['start'] = $filter['start'] - 1;
          ?>
          <tr>
            <td colspan="8" style="text-align:center;font-style:oblique"> -- Tidak ada data --</td>
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