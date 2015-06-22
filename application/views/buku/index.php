<script>
 $(function(){
	$("#form-klasifikasi").hide();
	
	$('#tabs').tabs({
		ajaxOptions: {
				beforeSend: function(){
					$("#loading").show();
				},
				error: function( xhr, status, index, anchor ) {
							$( anchor.hash ).html(
								"Couldn't load this tab. We'll try to fix this as soon as possible. " +
								"If this wouldn't be a demo." );
						},
				cache: false,
				complete: function(){
					$("#loading").hide();
				}
		}
	});
	
	$("#katalog").click(function(){
		$.ajax({
			type: "GET",
			cache: false,
			beforeSend: function(){
					$("#loading").show();
			},
			complete: function(){
					$("#loading").hide();
			},
			url: "<?php echo base_url(); ?>index.php/buku/show_catalog_ajax",
			success: function(data){
				$("#list-buku").html(data);
			}
		});
	});
	
	$("#loading").hide();
 });
</script>
<?php echo $this->session->flashdata('notice')?>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1" id="katalog">Daftar Katalog</a></li>
		<li><a href="<?php echo base_url(); ?>index.php/buku/add">Tambah Katalog Baru</a></li>
		<li><a href="<?php echo base_url(); ?>index.php/buku/show_koleksi_ajax">Daftar Koleksi</a></li>
		<li><a href="<?php echo base_url(); ?>index.php/buku/show_koleksi_keluar">Daftar Koleksi Keluar</a></li>
	</ul>
	<div id="loading" style="margin-left: auto; margin-right: auto; width: 128px; position: fixed;">
			<img src="<?php echo base_url(); ?>asset/loading51.gif">
	</div>
	<div id="tabs-1">
		<!-- start form -->
		<img src="<?php echo base_url(); ?>asset/images/catalog64.png" align="left">
		<p style="border-bottom: 1px solid #757575; margin-left: 83px;">
			<b>KATALOG</b>- Jika ingin melakukan pencarian, silahkan masukan Judul buku kemudian klik tombol cari.<br>
			<form method="post" style="margin-left: 83px;">
			Masukan Judul Buku : <input type="text" name="judul_buku" size="35">
			Klasifikasi <?php echo form_dropdown('clasifications', $clasifications); ?>
			<?php echo form_submit('submit','cari')?>
			</form>
		</p>	
		<div style="float: right; margin-top:10px;">
			Total : <?php echo $count_buku; ?> buku
		</div>
		<div style="margin-top: 5px;">
			<input type="button" value="Hapus Data Terpilih" name="delete_selected">
			<input type="button" value="Cek Semua" name="check_all">
			<input type="button" value="Hilangkan Cek" name="delete_selected">
		</div>
		<!-- end form -->
		<div id="list-buku">
		<!-- start table -->
		<?php
			echo table_open_tag("cellpadding=\"5\" cellspacing=\"0\" width=\"100%\"");
			
			$title = array('Judul Pustaka', 'ISBN/ISNN', 'Copies', 'Last Update', '');
			echo table_title($title);
			
			foreach ($results as $row)
			{
				$data = array(
								$row->JUDUL_PUSTAKA,
								$row->ISBN_ISSN,
								! $row->JUMLAH ? "none" : $row->JUMLAH,
								date("d M Y", strtotime($row->TANGGAL_INPUT)),
								form_checkbox('check[]', $row->ID).  ' | '.
								edit_path('buku', $row->ID). ' | '.
								anchor('buku/add_item', 'Add Items')
							 );
				echo table_contents($data);
				
			}
			echo table_close_tag();
			echo data_is_empty($results);
		?>
		<div style="text-align: center">
			<?php echo isset($pages) ? $pages : ''; ?>
		</div>
		<!-- end table -->
		</div>
	</div>
</div>