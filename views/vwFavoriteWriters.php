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
<div class="card border-dark" style="margin:1%;position:absolute;top:7.5%;width:98%" id="mainDiv">
<div class="card-header bg-dark text-white" align="center">பின்தொடரப்படும் எழுத்தாளர்கள்</div>
<div class="card-body">
	<ol style="list-style:none">
		<mycomponent v-for="writer in writers" v-bind:myelement="writer" v-bind:key="writer.writerid" ></mycomponent>
	</ol>
</div>
</div>
<script type="text/x-template" id="idForTemplate"><button style="margin:1%" v-on:click="clickToViewWriter(myelement.writerid)" class="btn bg-dark text-white">{{myelement.writerName}}</button></script>
<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				writers: [],
			}
		},
		mounted(){
			var innerThis=this;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var data=JSON.parse(xhttp.responseText);
					data.forEach(function(item){
						innerThis.writers.push({"writerid":item.starUserid,"writerName":item.starName});
					});
				}
			};
			xhttp.open("GET", "<?php echo base_url('index.php/user/getFollowingUsersForThisUser?userName='.$username); ?>", true);
			xhttp.send();
		}
	}
	const app = Vue.createApp(componentVar)

	app.component('mycomponent', {
		props: ['myelement','writerid','writerName'],
		template: "#idForTemplate",
		methods : {
			clickToViewWriter : function(writerid) {
				window.location.href="<?php echo base_url('index.php/user/singleuser?userid='); ?>"+writerid;
			},
		},
		mounted(){}
	});

	app.mount('#mainDiv')
</script>