<div id="content_modal" style="max-width:400px;margin:auto;background-color:#efefef;">

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i></button>
	<h3>Program Studi</h3>
</div>

	<form id="myform" class="myform" method="post" name="myform" action="<?=$url_submit?>" >
	<div class="modal-body" style="max-height:400px;overflow-y:auto;">
		<div class="row-fluid">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode</th>
						<th>Nama</th>
						<th style="width:30px;text-align:center;">Aksi</th>
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
								<td><?=$value['prodi_kode']?></td>
								<td><?=$value['prodi_nama']?></td>
								<td align="center">
									<input type="radio"  name="prodi[]" class="prodi" value="<?=$value['prodi_id']?>" <?=$value['checked']?>>
									
								</td>
							</tr>
							<?php
						}
					}else{
						$filter['start'] = $filter['start'] - 1;
						?>
						<tr>
							<td colspan="7" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>
						</tr>
						<?php
					} 
					?>
				</tbody>
			</table>
		</div>

	</div>
	<div class="modal-footer">
		<input type="hidden" name="id" id="id" value="<?=$id?>" />
		<button type="button" data-dismiss="modal" class="btn btn-round btn-default">Tutup</button>
		<input type="submit" id="submit" class="btn btn-round btn-primary" value="Simpan" ></button>
	</div>
	</form>

	<div class="" style="margin:0px 20px">
	  <span class="badge">Showing <?=$filter['start']+1?> to <?=($no-1)?> of <?=($jumlah_data)?> entries</span>
	  <ul style="margin:0px" class="pagination pull-right"><?=$paging?></ul>
	</div>

<hr>
</div>

<script type="text/javascript">
$(function(){
	var form = document.myform;
	var dataString = $(form).serialize();

	$(".pagin" ).on( "click", function() {
		
		urle = $(this).find("a").attr('href');
		id = $("#id").val();
		// alert(urle);
		$.ajax({
			type: "POST",
			url: urle,
			data: { <?=$filter['from']?>: id }
		}).done(function( msg ) {
			$('#content_modal').html(msg);
			$modal.modal('show');
		});

		return false;
	});
});




</script>