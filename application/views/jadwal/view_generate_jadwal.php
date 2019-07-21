<?php
	$this->load->model('m_penjadwalan');
	$guru_lengkap = $this->m_penjadwalan->cek_guru_kelas_lengkap();
	$style = !$guru_lengkap?'disabled':'';
	$warning_kelas = !$guru_lengkap?'':'style="display:none"';
?>
<div class="">

  <div class="page-title">
    <div class="">
      <ul class="breadcrumb">
        <a style="cursor: pointer;color: #FF5733" id="menu_toggle" data-toggle="tooltip" data-placement="top" title="Toggle Sidebar">
          <i class="fa fa-arrows" aria-hidden="true"> </i> <i class="fa fa-arrows-v" aria-hidden="true"> </i>
        </a>
        <li><a href="<?php echo base_url();?>"><i class="fa fa-home"></i> Beranda </a>
        <li><a href="<?php echo base_url();?>penjadwalan/jadwal_pelajaran">Penjadwalan</a>
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
    
    <!--Main content-->

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2> <?=$title;?> <small>Jadwal</small></h2>
            <div class="clearfix"></div>
          </div>
          
          <div class="x_content">

          <div class="alert alert-info"> 
            <H4>Garis Besar Dasar Algoritma Genetika: </H4>
            <ol>
              <li>[Mulai] Menghasilkan populasi n kromosom secara acak (solusi yang sesuai untuk masalah)</li>
              <li>[Fitness] Evaluasi fitness f(x) setiap kromosom x pada populasi</li>
              <li>[Populasi baru] Buat populasi baru dengan mengulangi langkah-langkah berikut sampai populasi baru selesai</li>
              <ul>
                <li>[Seleksi] Pilih dua kromosom induk dari populasi sesuai dengan fitnessnya (fitness lebih baik, kesempatan lebih besar untuk dipilih)</li>
                <li>[Crossover] Dengan crossover kemungkinan cross over orang tua untuk membentuk anak baru (anak). Jika tidak ada crossover yang dilakukan, keturunan adalah salinan orang tua yang tepat.</li>
                <li>[Mutasi] Dengan kemungkinan mutasi keturunan baru pada masing-masing lokus (posisi dalam kromosom).</li>
                <li>[Menerima] Tempatkan keturunan baru dalam populasi baru</li>
              </ul>
              <li>[Ganti] Gunakan populasi baru yang dihasilkan untuk menjalankan algoritma lebih lanjut</li>
              <li>[Test] Jika kondisi akhir terpenuhi, berhenti, dan kembalikan solusi terbaik pada populasi saat ini</li>
              <li>[Loop] Ke langkah 2</li>
            </ol>
          </div>
          
          <hr>
    		  <div class="alert alert-warning" <?=$warning_kelas?>>
    		    <button data-dismiss="alert" class="close" type="button"><i class="fa fa-remove"></i></button>
    		   	<p>
    		  		<strong>Peringatan!</strong> Kelas belum lengkap!
    		  		<a href="<?=base_url().'penjadwalan/kelas';?>"> <span class="btn btn-xs btn-primary"> Lengkapi Kelas </span></a>
    			</p>
    		  </div>

          <form action="<?=$url_submit?>" method="post" class="form-horizontal form-label-left" novalidate>
            
            <br>
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah individu <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="number" name="jml_individu" class="form-control input-sm col-md-3 col-xs-12" required="required" value="10">
                <!--<input type="hidden" name="jml_individu" value="10">-->
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <span class="badge">Jumlah individu yang ingin dibangkitkan (bilangan real).</span>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Probabilitas CrossOver <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="number" name="pc" class="form-control input-sm col-md-3 col-xs-12" required="required" value="0.9">
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <span class="badge">Nilai yang digunakan dalam proses pindah silang (0.6 - 1).</span>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Probabilitas Mutasi <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="number" name="pm" class="form-control input-sm col-md-3 col-xs-12" required="required" value="0.4">
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <span class="badge">Nilai yang digunakan dalam proses mutasi (0.4 - 1).</span>
              </div>
            </div>

            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Generasi <span class="required">*</span></label>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <input type="number" name="generation" class="form-control input-sm col-md-3 col-xs-12" required="required" value="100">
                <!--<input type="hidden" name="generation" value="10">-->
              </div>
               <div class="col-md-3 col-sm-3 col-xs-12">
                <span class="badge">Jumlah generasi yang digunakan untuk iterasi (bilangan real).</span>
              </div>
           </div>

            <br>
            <div class="form-group">
              <div class="col-md-4 col-md-offset-3">
                <button type="submit" class="btn btn-round btn-primary" <?=$style?> onclick="ShowLoading()">Generate Jadwal</button>
                <a href="<?=$url_jadwal?>"><button type="button" class="btn btn-round btn-default">Kembali</button></a>
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