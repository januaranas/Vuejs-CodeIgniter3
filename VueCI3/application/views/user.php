<!DOCTYPE html>
<html>
<head>
	<title>Users</title>

	<link rel="stylesheet" type="text/css" href="<?=base_url()?>asset/bootstrap/css/bootstrap.min.css">
</head>
<body>
	<nav class="navbar navbar-inverse">
	  	<div class="container">
	    	<div class="navbar-header">
		      	<a class="navbar-brand" href="">
		        	VueJS + CodeIgniter 3
		      	</a>
	    	</div>
	      	<p class="navbar-text navbar-right"><a href="https://github.com/mervinsantillanvillaceran" class="navbar-link">by Gentlemanoi</a></p>
	  	</div>
	</nav>

	<div id="app">
        <div class="container">
    		<div class="row">
        		<div class="col-md-4">
			    	<form id="user-form" method="POST" v-on:submit.prevent="submit">
	    				<div class="panel panel-warning">
					  		<div class="panel-heading">User Form</div>	
					  		<div class="panel-body">
			    				<div>
			    					<br>
		    						<label>Firstname</label>
		    						<input type="text" name="firstname" class="form-control" required="" v-model="user.firstname">
			    				</div>
			    				<div>
			    					<br>
		    						<label>Lastname</label>
		    						<input type="text" name="lastname" class="form-control" required="" v-model="user.lastname">
			    				</div>
			    				<div>
			    					<br>
			    					<input type="submit" name="add" v-model="submitButton" class="form-control btn btn-success">
			    				</div>
			    				<div>
			    					<br>
			    					<button name="cancel" v-on:click="cancel" v-if="isEdit" class="form-control btn btn-default">CANCEL</button>
			    				</div>
					  		</div>
						</div>
			    	</form>
        		</div>
        		<div class="col-md-8">
    				<div class="panel panel-primary">
					  	<div class="panel-heading">List</div>
					  	<div class="panel-body">
					  		<br>
					    	<table class="table table-stripped">
			                    <tr>
			                        <th width="40%">Firstname</th>
			                        <th width="40%">Lastname</th>
			                        <th width="20%">Action</th>
			                    </tr>
			                    <tr v-for="user in users">
			                        <td>{{ user.firstname }}</td>
			                        <td>{{ user.lastname }}</td>
			                        <td>
			                            <button class="btn btn-warning" v-on:click="edit(user.id)">Edit</button>
			                            <button class="btn btn-danger" v-on:click="remove(user.id)">Delete</button>
			                        </td>
			                    </tr>
			                </table>
					  	</div>
					</div>
        		</div>
    		</div>
		</div>
    </div>

	<script type="text/javascript" src="<?=base_url()?>asset/vue/vue.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>asset/axios/axios.min.js"></script>

	<script type="text/javascript">
		var api = '<?php echo base_url();?>';                
    
	    var app = new Vue({
	        el: '#app',
	        data: {
	        	users: [],
	           	user: {
	                firstname: '',
	                lastname: ''
	            },
	            isEdit: false,
	            editIdentifier: '',
	            submitButton: 'ADD'
	        },
	        created() {
	            this.get()
	        },
	        methods: {
	            get: function() {
	            	var vm = this;
	                axios.get(api + 'user/get_all').then(function(result){
	                	vm.users = result.data;
	                });
	            },
	            submit: function(){
	            	var vm = this;
	            	var url = api + ((vm.isEdit)? ('user/edit/' + vm.editIdentifier) : 'user/add')

	            	axios.post(url, JSON.stringify(vm.user)).then(function(response) {
            			vm.get()
            			vm.reset()
                    });
	            },
	            reset: function(){
	            	this.user.firstname = ''
	            	this.user.lastname = ''
	            	this.isEdit = false
		            this.editIdentifier = ''
		            this.submitButton = 'ADD'
	            },
	            remove: function(id){
	            	var vm = this
	            	var user = vm.select(id)
	            	var confirm = window.confirm("Are you sure you want to delete " + user.firstname + "?")
	            	if(confirm){	
		            	axios.post(api + 'user/delete/' + id).then(function(response) {
		        			vm.get()
		                })
	            	}
	            },
	            edit: function(id){
		            this.submitButton = 'EDIT'
	            	this.isEdit = true
	            	this.editIdentifier = id
	            	this.setUser(this.select(id))
	            },
	            cancel:function(e){
	            	e.preventDefault()
	            	this.reset()
	            },	
	            setUser: function(obj){
	            	this.user.firstname = obj.firstname
	            	this.user.lastname = obj.lastname
	            },
	            select: function(id){
	            	return this.users.find(function(obj){ return obj.id == id})
	            }
	        }
	    })
	</script>
</body>
</html>