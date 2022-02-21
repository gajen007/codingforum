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
<div class="card border-dark" style="margin:1%; position:absolute;top:7.5%" id="mainDiv">
	<div class="card-header bg-dark text-white" align="center">இதுவரை தமிழ் Coders தளத்தில் இணைந்துள்ளவர்கள்...</div>
	<div class="card-body">
		<mycomponent v-for="member in members" v-bind:myelement="member" v-bind:key="member.memberid" ></mycomponent>	
	</div>
</div>
<script type="text/x-template" id="idForTemplate">
	<button class="btn btn-lg bg-dark text-white" style="margin:1%">{{myelement.memberName}}</button>
</script>
<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				members: [],
			}
		},
		mounted(){
			var innerThis=this;
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var data=JSON.parse(xhttp.responseText);
					data.forEach(function(item){
						innerThis.members.push({"memberid":item.id,"memberName":item.memberName});
					});
				}
			};
			xhttp.open("GET", "<?php echo base_url('index.php/general/getallUsers?userName='.$username); ?>", true);
			xhttp.send();
		}
	}
	const app = Vue.createApp(componentVar)

	app.component('mycomponent', {
		props: ['myelement','memberid','memberName'],
		template: "#idForTemplate",
		mounted(){}
	});

	app.mount('#mainDiv')
</script>