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
  </div>

  <div class="clearfix"></div>

    <!--Search Form-->
    <form method="post" action="<?php echo base_url() . 'penjadwalan/kelas'?>">
      <div class="title_left">
        <div class="col-md-3 col-sm-4 col-xs-12 form-group top_search">
          <div class="input-group">
            <input type="text" class="form-control input-sm" placeholder="Search for..." name="search_query" value="<?php echo isset($search_query) ? $search_query : '' ;?>">
            <span class="input-group-btn">
              <button class="btn btn-sm btn-default" type="submit"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </div>
      </div>
        <a href="<?php echo base_url() . 'penjadwalan/kelas';?>">
          <button type="button" class="btn btn-sm btn-round btn-default"><i class="fa fa-spinner"></i> Muat Ulang</button>
        </a>
      <div class="title_right pull-right">
        <a href="<?php echo base_url() . 'penjadwalan/reset_kls';?>">
          <button type="button" class="btn btn-sm btn-round btn-danger"><i class="fa fa-trash"></i> Reset Kelas </button>
        </a>
        <a href="<?php echo base_url() . 'penjadwalan/generate_kelas';?>">
          <button type="button" class="btn btn-sm btn-round btn-primary"><i class="fa fa-plus-circle"></i> Generate Kelas </button>
        </a>
      </div>
    </form>
    <!--End Search Form-->

  <div class="clearfix"></div>

    <!--Table of content-->

    <div class="alert alert-warning" <?=$display_warning_gurukelas?>>
      <button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button>
      <p><strong>Peringatan!</strong> Guru kelas belum lengkap.</p>
    </div>

    <div class="alert alert-<?=$notif_style?>" <?=$display?>>
      <button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button>
      <?=$notif_message?>
    </div>

    <div class="btn_add">
      <a href="<?=$url_proses_jadwal?>" <?=$display_buat_jadwal?>>
        <button class="btn btn-sm btn-round btn-info"><i class="fa fa-plus-circle"></i> Generate jadwal </button>
      </a>
      <a href="<?=$url_list_jadwal?>" <?=$display_list_jadwal?>>
        <button class="btn btn-sm btn-round btn-info"><i class="fa fa-folder"></i> Lihat jadwal </button>
      </a>
    </div>

    <hr>
    <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode Prodi</th>
          <th>Nama Kelas</th>
          <th>Mata Pelajaran</th>      
          <th>Semester</th>
          <th>sks</th>
          <th>Sifat</th>
          <!--th>Jumlah Peserta</th-->
          <th>Guru Kelas</th>
          <th style="width:90px;text-align:center;">Aksi</th>
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
              <td><?=$value['kd_prodi']?></td>
              <td><?=$value['nama_kelas']?></td>
              <td><?=$value['nama_mapel']?></td>
              <td><?=$value['paket_smt']?></td>
              <td><?=$value['sks']?></td>
              <td><?=$value['sifat']?></td>
              <td><?=$value['guru_kelas']?></td>
              <td align="center">
                <a id="<?=$value['id']?>" href="<?=$url_pilih_guru?>" class="btn btn-xs btn-round btn-info edit_guru"><i class="fa fa-edit"></i> Guru </a>
              </td> 
                                              
            </tr>
            <?php 
          }
        }else{
          $filter['start'] = $filter['start'] - 1;
          ?>
          <tr>
            <td colspan="8" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>
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

    <div id="responsive" class="modal fade" tabindex="-1" data-width="760"></div>
    <!--End Table of content-->

  <br><hr>
  <div class="clearfix"></div>
  
</div>