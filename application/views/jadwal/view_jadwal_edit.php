<div class="">
  
  <div class="page-title">
    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?=base_url();?>"><i class="fa fa-home"></i> Beranda</a>
        <li><a href="<?=base_url();?>penjadwalan/jadwal_pelajaran">Jadwal</a>
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
            <h2><?=$title?> Jadwal </h2>
            <div class="clearfix"></div>
          </div>
          <br>
          
          <div class="x_content">
          <form action="<?=$url_submit?>" method="post" class="form-horizontal form-label-left" novalidate>

            <div class="col-md-5 col-sm-5 col-xs-12 form-group">
              <input type="hidden" name="id" value=""/>

              <pre><b>Informasi Terkait Jadwal</b></pre>
              <pre>Kelas          : <?=$data_mapel[0]['kls_nama']?></pre>
              <pre>Semester       : <?=$data_mapel[0]['smt']?></pre>
              <pre>Mata Pelajaran : <?=$data_mapel[0]['nama_mapel']?></pre>
              <pre>Ruang          : <?=$data_mapel[0]['ru_nama']?></pre>
              <pre>Waktu          : <?=$data_mapel[0]['waktu_hari'].', '.$data_mapel[0]['waktu_jam_mulai'].'-'.$data_mapel[0]['waktu_jam_selesai']?></pre>
              <pre>Guru Pengampu  : <?=$data_mapel[0]['guru_nama']?></pre>

              <div class=""><hr>
                <a href="<?=$url_jadwal?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
                <button type="submit" class="btn btn-round btn-primary">Simpan</button>
              </div>
              <br>
            </div>

            <div class="row-fluid table-responsive" style="max-height:450px;overflow-y:auto;">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width:30px;text-align:center;"><i class="fa fa-pencil"></i></th>
                  <th style="width:40px;text-align:center;">#</th>
                  <th>Ruang</th>
                  <th>Hari</th>
                  <th>Waktu</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $no = 1; 
                if (!empty($data_waktu)) {
                  foreach ($data_waktu as $key => $value) {
                    ?>
                    <tr>
                      <td align="center">
                        <input type="radio" name="waktu" class="waktu" value="<?=$data_mapel[0]['jp_id'].'-'.$value['id_waktu'].'-'.$value['id_ruang'].'-'.$value['jam_mulai'].'-'.$value['jam_selesai'].'-'.$value['nama_ruang'].'-'.$value['waktu_hari']?>">
                      </td>                                       
                      <td align="center"><?=$no++?></td>
                      <td><?=$value['nama_ruang']?></td>
                      <td><?=$value['waktu_hari']?></td>
                      <td><?=$value['jam_mulai']?></td>
                    </tr>
                    <?php 
                  }
                }else{
                ?>
                  <tr>
                    <td colspan="7" style="text-align:center;font-style:oblique"> -- Tidak ada data --</td>
                  </tr>
                <?php
                } 
                ?>
              </tbody>
            </table>
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