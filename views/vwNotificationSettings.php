<script src="<?php echo base_url('public/vue/vue@3.2.2'); ?>" crossorigin="anonymous"></script>
<style type="text/css">
	body { 
		background: url("<?php echo base_url('public/images/bg.jpg'); ?>") repeat; 
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<div class="container" style="position:absolute;top:7.5%; right:0.25%; left:0.25%;">
	<div class="card border-dark">
		<div class="card-header bg-dark text-white" align="center">அறிவித்தல்களுக்கான அமைப்புகள்</div>
		<div class="card-body">
			<ol style="list-style:none;">



				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->sendemail==1){ echo "checked"; } ?> id="sendemail" class="form-control-sm"/>&nbsp;<label for="sendemail">&nbsp;அறிவிப்புகளை மின்னஞ்சலில் பெற</label>
				</li>

				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type1==1){ echo "checked"; } ?> id="type1" class="form-control-sm"/>&nbsp;<label for="type1">&nbsp;உங்களின் Comment'க்கு பின்னர் எவரேனும் Comment செய்தால்</label>
				</li>
				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type2==1){ echo "checked"; } ?> id="type2" class="form-control-sm"/>&nbsp;<label for="type2">&nbsp;நீங்கள் கேட்ட கேள்விக்கு எவரேனும் பதில் அளித்தால்</label>
				</li>
				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type3==1){ echo "checked"; } ?> id="type3" class="form-control-sm"/>&nbsp;<label for="type3">&nbsp;நீங்கள் பின்தொடரும் கேள்விக்கு எவரேனும் பதில் அளித்தால்</label>
				</li>
				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type4==1){ echo "checked"; } ?> id="type4" class="form-control-sm"/>&nbsp;<label for="type4">&nbsp;உங்களின் பதிலுக்கு எவரேனும் comment செய்தால்</label>
				</li>
				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type5==1){ echo "checked"; } ?> id="type5" class="form-control-sm"/>&nbsp;<label for="type5">&nbsp;உங்களின் பதிலை எவரேனும் upvote செய்தால்</label>
				</li>
				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type6==1){ echo "checked"; } ?> id="type6" class="form-control-sm"/>&nbsp;<label for="type6">&nbsp;நீங்கள் பின்தொடரும் கேள்வி Edit செய்யப்பட்டால்</label>
				</li>
				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type7==1){ echo "checked"; } ?> id="type7" class="form-control-sm"/>&nbsp;<label for="type7">&nbsp;நீங்கள் பின்தொடரும்  பதில் Edit செய்யப்பட்டால்</label>
				</li>
				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type8==1){ echo "checked"; } ?> id="type8" class="form-control-sm"/>&nbsp;<label for="type8">&nbsp;நீங்கள் பின்தொடரும் பதிலுக்கு எவரேனும் comment செய்தால்</label>
				</li>
				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type9==1){ echo "checked"; } ?> id="type9" class="form-control-sm"/>&nbsp;<label for="type9">&nbsp;நீங்கள் பின்தொடரும் தலைப்பில் ஏதேனும் கேள்வி சேர்க்கப்பட்டால்</label>
				</li>
				<li style="font-size:13px; margin:1%">
					<input type="checkbox" <?php if($ns->type10==1){ echo "checked"; } ?> id="type10" class="form-control-sm"/>&nbsp;<label for="type10">&nbsp;நீங்கள் பின்தொடரும் தலைப்பில் ஏதேனும்  பதில் சேர்க்கப்பட்டால்</label>
				</li>
			</ol>
		</div>
		<div class="card-footer bg-light" align="center">
			<button class="btn btn-sm bg-dark text-white" id="saveNs">Save செய்ய</button>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).on("click","#saveNs",function(){

		var sendemail=0; if ($("#sendemail").is(':checked')) { sendemail=1; }
		var type1=0; if ($("#type1").is(':checked')) { type1=1; }
		var type2=0; if ($("#type2").is(':checked')) { type2=1; }
		var type3=0; if ($("#type3").is(':checked')) { type3=1; }
		var type4=0; if ($("#type4").is(':checked')) { type4=1; }
		var type5=0; if ($("#type5").is(':checked')) { type5=1; }
		var type6=0; if ($("#type6").is(':checked')) { type6=1; }
		var type7=0; if ($("#type7").is(':checked')) { type7=1; }
		var type8=0; if ($("#type8").is(':checked')) { type8=1; }
		var type9=0; if ($("#type9").is(':checked')) { type9=1; }
		var type10=0; if ($("#type10").is(':checked')) { type10=1; }
		var fd = new FormData();
		fd.append("sendemail",sendemail);
		fd.append("type1",type1);
		fd.append("type2",type2);
		fd.append("type3",type3);
		fd.append("type4",type4);
		fd.append("type5",type5);
		fd.append("type6",type6);
		fd.append("type7",type7);
		fd.append("type8",type8);
		fd.append("type9",type9);
		fd.append("type10",type10);
        fetch("<?php echo base_url('index.php/notifications/modifySettings'); ?>",{
          method:'POST',
          body: fd,
          mode: 'no-cors',
          cache: 'no-cache'})
        .then(response => {
          if (response.status == 200) {
            return response.json();            
          }
          else {
            alert('Backend Error..!');
            console.log(response);
          }
        })
        .then(data => {
          alert(data.message); window.location.reload();
        })
        .catch(() => {
          (console.log("Network connection error"));
          alert("Reloading"); window.location.reload();
        });
	});
</script>