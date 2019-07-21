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
            <button class="btn btn-xs btn-round btn-default dropdown-toggle" type="button" data-toggle="dropdown"><?=$this->session->userdata('username');?>
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

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_content">
          <h3>PENJADWALAN SMA N 2 Sleman</h3><hr>

     			<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
    	      <div class="profile_img">
    	      <div id="crop-avatar">
    				  <img src="<?=base_url();?>assets/img/sq7_ECUz_400x400.jpg" alt="..." class="img-circle profile_img" alt="avatar">
    				</div>
    	      </div><br>
          </div>

          <br><br>
          <pre>Nama Pengguna   : <?=$this->session->userdata('username');?></pre>
          <pre>Email Pengguna  : <?=$this->session->userdata('email');?></pre>
          <pre>Status Pengguna : <?=$this->session->userdata('level');?></pre>

          <br>
          <div class="pull-right">
            <a href="<?=base_url().'pengelolaan/user_edit'?>"><button class="btn btn-sm btn-primary btn-round">Ubah Profil</button></a>
          </div>
          <br><br><hr>

        </div>
      </div>
    </div>
  </div>

  <br><hr>
  <div class="clearfix"></div>

</div>