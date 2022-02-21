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
	<div class="card-header" align="center"><input type="text" class="form-control" v-on:change="search" v-model="searchString" placeholder="Type writer's name here"/></div>
	<div class="card-body">
		<ol style="list-style:none;">
			<mycomponent
			v-for="user in writers"
			v-bind:myelement="user"
			v-bind:key="user.userid"
			></mycomponent>
		</ol>
	</div>
</div>
<script type="text/x-template" id="idForTemplate"><div v-on:click="clickToHeading(myelement.userid)" style="margin:1%" class="card border-dark"><div class="card-body">{{myelement.userFullName}}</div></div></script>
<script type="text/javascript">
	const componentVar = {
		props:{},
		data() {
			return {
				searchString:"",
				writers:[]
			}
		},
		methods:{
			search:function(){
				if (this.searchString!=="") {
					this.headings=[];
					var innerThis=this;
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							var result=JSON.parse(xhttp.responseText);
							result.forEach(function(item){
								innerThis.writers.push({"userid":item.id,"userFullName":item.userFullName});
							});
						}
					};
					xhttp.open("GET", "<?php echo base_url('index.php/general/searchUser?searchKey='); ?>"+innerThis.searchString, true);
					xhttp.send();
				}
				else{
					this.writers=[];
				}
			}
		}
	}
	const app = Vue.createApp(componentVar)

	app.component('mycomponent', {
		props: ['myelement', 'userid', 'userFullName'],
		template: "#idForTemplate",
		methods : {
			clickToHeading : function(userid) {
				window.location.href="<?php echo base_url('index.php/user/singleuser?userid='); ?>"+userid;
			}
		}
	});

	app.mount('#mainDiv')
</script>