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
<div class="container" style="margin-top:5%">
	<div class="card border-dark">
	<div class="card-header bg-dark text-white" align="center">Admin Dashboard</div>
		<div class="card-body">
			<div class="row" style="margin:1%">
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">Add Bad Word</div>
						<form method="POST" id="addBadWord" action="<?php echo base_url('index.php/admin/addBadWord'); ?>">
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12" style="margin:1%">
									<input type="text" id="badWord" required class="form-control-sm"/>
								</div>
							</div>
						</div>
						<div class="card-footer" align="center"><button type="submit" class="btn btn-sm bg-dark text-white">Add</button></div>
						</form>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">temp</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">temp</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">temp</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
			</div>

			<div class="row" style="margin:1%">
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">Users</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">Questions</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">Answers</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">Comments</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
			</div>

			<div class="row" style="margin:1%">
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">Messages</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">Reports</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">Upvotes</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12" style="margin:1%">
					<div class="card border-dark">
					<div class="card-header bg-dark text-white" align="center">Downvotes</div>
						<div class="card-body">
					
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).on("submit","#addBadWord",function(e){
		e.preventDefault();
		        var toServer=new FormData();
		        toServer.append('badWord',$("#badWord").val());
		        fetch("<?php echo base_url('index.php/admin/addBadWord'); ?>",{
		          method:'POST',
		          body: toServer,
		          mode: 'no-cors',
		          cache: 'no-cache'})
		        .then(response => {
		          if (response.status == 200) { return response.json(); }
		          else { alert('Backend Error..!'); }
		        })
		        .then(data => { alert(data.message); $("#badWord").val(""); })
		        .catch(() => { alert("Network connection error"); });
		
	});
	

</script>