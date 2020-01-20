<?php foreach($kelas as $key => $c): ?>
	<div class="checkbox">
	    <label>
	      	<input type="checkbox" value="<?=$key?>" name="kelas[]" <?=in_array($key, $kelas_selected) ? 'checked' : ''?>> <?=$c?>
	    </label>
	</div>
<?php endforeach; ?>