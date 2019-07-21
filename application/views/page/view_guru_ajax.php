<div id="content_modal" style="max-width:400px;margin:auto;background-color:#efefef;">

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i></button>
	<h3>Data Guru</h3>
</div>

<form id="myform" class="myform" method="post" name="myform" action="#">
<div class="modal-body" style="max-height:400px;overflow-y:auto;">
<div class="row-fluid">
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<tr>
			<th>No</th>
			<th>NIP Guru</th>
			<th>Nama Guuru</th>
			<th style="width:30px; text-align:center;">Aksi</th>
		</tr>
	</thead>   
	<tbody>
		<?php 
		$no = $filter['start']+1; 
		if (!empty($data_guru)) {
			foreach ($data_guru as $key => $value) { 
				?>
				<tr>
					<td><?=$no++?></td>
					<td><?=$value['nip']?></td>
					<td><?=$value['nama']?></td>
					<td align="center">
						<input type="radio" name="guru" class="guru" value="<?=$value['id']?>" id="" style="">
						<input type="hidden" id="guru_<?=$value['id']?>" class="prodi" value="<?=$value['nama']?>" id="" style="">
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
	<input type="hidden" name="id_kelas" id="id_kelas" value="" />
	<button type="button" data-dismiss="modal" class="btn btn-round btn-default">Tutup</button>
	<input type="submit" id="submit" class="btn btn-round btn-primary" value="Pilih"></button>
</div>
</form>

<div class="" style="margin:0px 20px">
  <span class="badge">Showing <?=$filter['start']+1?> to <?=($no-1)?> of <?=($jumlah_data_guru)?> entries</span>
  <ul style="margin:0px" class="pagination pull-right"><?=$paging_guru?></ul>
</div>

<hr>
</div>

<script type="text/javascript">

$(document).on('click','.pagin',function() {
	urle = $(this).find("a").attr('href');
	id_kelas = $("#id_kelas").val();
	// alert(urle);
	$.ajax({
		type: "POST",
		url: urle,
		data: { idkls: id_kelas }
	}).done(function( msg ) {
		$('#content_modal').html(msg);
		$modal.modal('show');
	});
	return false;
});

$(document).on('click','#submit',function() {
	id = $('.guru:checked').val();
	guru = $('#guru_'+id).val();
	$('#id_guru').val(id);
	$('#nama').val(guru);
	$('#responsive').modal('hide');
	return false;
});

</script>