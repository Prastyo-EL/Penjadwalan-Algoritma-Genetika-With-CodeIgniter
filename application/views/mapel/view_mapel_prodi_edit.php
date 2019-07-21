<div class="">
  <div class="page-title">

    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?=base_url();?>"><i class="fa fa-home"></i> Beranda </a>
        <li><a href="<?=base_url();?>pengelolaan/mapel_prodi"> Mapel Prodi </a>
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
  
  <h3>Mapel Prodi : <?=$mapel['kode']?> - <?=$mapel['nama']?></h3>
  
  <div class="pull-right">
    <a href="<?=$url_add?>"><button type="button" class="btn btn-round btn-primary"><i class="fa fa-plus-circle"></i> Prodi </button></a>
    <a href="<?=$url_back?>"><button type="button" class="btn btn-round btn-default"><i class="fa fa-chevron-circle-left"></i> Kembali </button></a>
  </div>

  <div class="clearfix"></div><br>

    <!--Main content-->
    <div class="alert alert-<?=$notif_style?>" <?=$display?>>
      <button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button>
      <?=$notif_message?>
    </div>

    <div class="table-responsive">
    <table class="table table-bordered table-striped table-condensed table-nonfluid">
      <thead>
        <tr>
          <th>No</th>
          <th>Kode prodi</th>
          <th>Nama prodi</th>
          <th>Porsi</th>                                 
          <th style="width:150px;text-align:center;">Aksi</th>                                       
        </tr>
      </thead>   
      <tbody>
        <?php 
        $no = $filter['start']+1; 
        if (!empty($data)) {
          foreach ($data as $key => $value) { 
            $rowspan = count($value);
            $display = ($rowspan>1)?'style="display:none;"':'';
            ?>
            <tr>
              <td rowspan="<?=$rowspan?>"><?=$no++?></td>
              <td><?=$value[0]['kode']?></td>
              <td><?=$value[0]['nama']?></td>
              <td><?=$value[0]['porsi']?></td>
              <td align="center">
                <a href="<?=$url_edit.'&idjoin='.$value[0]['id']?>" class="btn btn-xs btn-round btn-warning"><i class="fa fa-edit"></i> Ubah </a>
                <a <?=$display?> href="<?=$url_del.'/'.$value[0]['id']?>" id="<?=$value[0]['id']?>" class="btn btn-xs btn-round btn-danger"><i class="fa fa-trash"></i> Hapus </a>
              </td>                                       
            </tr>
            <?php 
            if ($rowspan>1) {         
              for ($i=1; $i < $rowspan; $i++) { 
                $display_sub = ($i!=$rowspan-1)?'style="display:none;"':'';
              ?>
                <tr>
                  <td><?=$value[$i]['kode']?></td>
                  <td><?=$value[$i]['nama']?></td>
                  <td><?=$value[$i]['porsi']?></td>
                  <td align="center">
                    <a href="<?=$url_edit.'&idjoin='.$value[$i]['id']?>" class="btn btn-xs btn-round btn-warning"><i class="fa fa-edit"></i> Ubah </a>
                    <a <?=$display_sub?> href="<?=$url_del.'/'.$value[$i]['id']?>" id="<?=$value[$i]['id']?>" class="btn btn-xs btn-round btn-danger"><i class="fa fa-trash"></i> Hapus </a>
                  </td>                                       
                </tr>
              <?php
              }
            }
          }
        }else{
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
    <!--Main content-->

  <br><hr>
  <div class="clearfix"></div>

</div>