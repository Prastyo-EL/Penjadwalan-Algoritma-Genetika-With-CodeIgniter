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
    <form method="post" action="<?=base_url() . 'penjadwalan/jadwal_list'?>">
      <div class="title_left">
        <div class="col-md-3 col-sm-4 col-xs-12 form-group pull-left top_search">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for..." name="search_query" value="<?=isset($search_query) ? $search_query : '' ;?>">
            <span class="input-group-btn">
              <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </div>
      </div>
      <a href="<?=base_url() . 'penjadwalan/jadwal_list';?>">
        <button type="button" class="btn btn-round btn-default"><i class="fa fa-spinner"></i> Muat Ulang</button>
      </a>
    </form>
    <!--Search Form-->
    
    <div class="pull-left">
      <select class="form-control input-sm" onchange="window.location.href=this.value;">
        <option value="?">-- Tampilkan Sesuai Program Studi --</option>
        <?php
          foreach ($prodi as $i => $pd) {
        ?>
          <option <?php if (isset($filter['pd']) && $filter['pd']==strtolower($pd['prodi_kode'])) echo "selected";?> value="?pd=<?=strtolower($pd['prodi_kode'])?>"><?=$pd['prodi_nama']?></option>
        <? } ?>
      </select>
    </div>
    <div class="pull-right">
      <a href="<?=base_url() . 'penjadwalan/jadwal_pelajaran';?>" data-toggle="tooltip" data-placement="top" title="Kolom Jadwal" class="btn btn-sm btn-round btn-default">
        <i class="fa fa-th-large" ></i>
      </a>
      <a href="<?=$url_cetak_jadwal?>" target="_blank">
        <button class="btn btn-sm btn-round btn-info"><i class="fa fa-print"></i> Cetak</button>
      </a>
      <?php if($this->session->userdata('level')=='admin' || $this->session->userdata('level')=='kepsek')
      { ?>
      <a href="<?=$url_cetak_laporan?>" target="_blank">
      <button class="btn btn-sm btn-round btn-info"><i class="fa fa-file-text"></i> Laporan</button>
      </a>
      <? } ?>

      <?php if($this->session->userdata('level')=='admin')
      { ?>
      <div class="pull-right">
        <a href="<?php echo base_url() . 'penjadwalan/reset_jadwal';?>">
          <button type="button" class="btn btn-sm btn-round btn-danger"><i class="fa fa-trash"></i> Reset </button>
        </a>
        <a href="<?php echo base_url() . 'penjadwalan/generate_jadwal';?>">
          <button type="button" class="btn btn-sm btn-round btn-primary"><i class="fa fa-plus-circle"></i> Generate </button>
        </a>
      </div>
      <? } ?>
      <br>
    </div>

  <div class="clearfix"></div>
    <br>
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
          <th>Prodi</th>
          <th>Kelas</th>
          <th>Smt</th>
          <th>Mata Pelajaran</th>                                    
          <th>Ruang & Waktu</th>
          <th>Guru Pengampu</th>
          <?php if ($this->session->userdata('level')=='admin') {
            echo '<th style="width:50px;text-align:center;">Aksi</th>';
          } ?>
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
              <td><?=$value['kelas']?></td>
              <td><?=$value['smt']?></td>
              <td><?=$value['mapel']?></td>
              <td><?=$value['label']?></td>
              <td><?=$value['guru']?></td>
              <?php if ($this->session->userdata('level')=='admin') {?>
              <td align="center">
                <a href="<?=base_url('penjadwalan/jadwal_edit').'?id='.$value['id_jp']?>"><button class="btn btn-xs btn-round btn-warning"><i class="fa fa-edit"></i> Ubah </button></a>
              </td>
              <? } ?>
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