@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-8">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <button class="nav-link" v-bind:class="{ active: show_list }" v-on:click="ListStatu()" >List utilisateurs</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" v-bind:class="{ active: !show_list }" v-on:click="ListStatu()" >Ajouter utilisateur</button>
                </li>

            </ul>

        </div>
    </div>

    <br>
    <br>

   
    <div v-show="show_list">
            <div class="row justify-content-center" >

                    <div class="col-md-8">
            
                        <table id="example" class="table table-striped " style="width:100%">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
            
                                </tr>
                            </thead>
                            <tbody>
            
                                
                                <tr v-for="utl in utls.data">
                                    <td> <img style="height: 50px ; width: 50px;" :src="base_url+'/'+utl.url"></td>
                                    <td>@{{ utl.name }}</td>
                                    <td>@{{ utl.lastname }}</td>
                                    <td>@{{ utl.email }}</td>
                                    <td style="display: flex; justify-content: space-around">
            
                                        <button class="btn btn-primary" data-toggle="modal" v-on:click="onUpdate(utl.id)" data-target="#myModal ">Update</button>
                                        <button type="submit" v-on:click="onDelete(utl.id)" class="btn btn-danger">Supp</button>
                                        
            
            
                                    </td>
            
                                </tr>
                                
                            </tbody>
            
                        </table>
            
                    </div>
            
                </div>
            
            
            
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">@{{message}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            
                            <div class="modal-body">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control" id="name"  v-model="utilisateur.name">
                                   </div>
                    
                                    <div class="form-group">
                                        <label for="">Last Name</label>
                                        <input type="text" class="form-control" id="lastname" v-model="utilisateur.lastname">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="email" class="form-control" id="email"  v-model=" utilisateur.email">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submite" v-on:click="onEdite()" class="btn btn-primary">Save changes</button>
                                </div>
                            
                        </div>
                    </div>
                </div>
    </div>


    <div class="row justify-content-center"   v-show="!show_list">
            <div class="col-md-8">

                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" id="name"  v-model="utilisateur.name">
                    </div>

                    <div class="form-group">
                        <label for="">Last Name</label>
                        <input type="text" class="form-control" id="lastname" v-model="utilisateur.lastname">
                    </div>
                    
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="form-control" id="email"  v-model=" utilisateur.email">
                    </div>

                     <div class="form-group">
                        <label for="">biographie</label>
                        <textarea name="biographie" id="inputbiographie"  class="form-control" rows="3" required="required" v-model=" utilisateur.biographie"></textarea>
                        
                    </div>

                     <div class="form-group">
                        <label for="">Image</label>
                        <input type="file" class="form-control" ref="img"  id="img">
                    </div>



                    <button type="submit" v-on:click="onStore()" class="btn btn-primary">Submit</button>


            </div>
            
        </div>



    </div>

@endsection

@section('javascripte')

    <script>



            $(document).ready(function() {
                $('#myModal').on('shown.bs.modal', function () {
                    $('#myInput').trigger('focus')
                });
            });

            

            var app = new Vue({

                el: '#app',
                data(){
                    return {
                        base_url : "{{asset('storage')}}",
                        message: 'Modifier',
                        show_list: true,
                        utls:null,
                        show_alert:false,
                        selected_img:'',
                        utilisateur:{
                            id:"",
                            name:"",
                            lastname:"",
                            email:"",
                            biographie:"",
                            img:'',
                        }
                    }
                },
                methods:{

                    ListStatu(){
                        this.show_list = !this.show_list ;
                        this.utilisateur={
                            id:"",
                            name:"",
                            lastname:"",
                            email:"",
                            biographie:"",
                            img:"",
                        };

                    },
                    GetUtilisateurs: function () {

                        axios.get('http://127.0.0.1:8000/utl')
                        .then(response=> {

                            // handle success
                            console.log(response.data);
                            this.utls =response;

                            console.log(this.utls);


                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        });
                    },
                    onStore(){

                        console.log('create *********************');


                        axios.defaults.headers.common["X-CSRF-TOKEN"] = document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content");

                       
                        let formData = new FormData();

                        const fileInput = document.querySelector( '#img' );

                        formData.append('img', fileInput.files[0]);
                        formData.append('name', this.utilisateur.name);
                        formData.append('lastname', this.utilisateur.lastname);
                        formData.append('email', this.utilisateur.email);
                        formData.append('biographie', this.utilisateur.biographie);

                        axios.post('http://127.0.0.1:8000/utl/',
                        formData)
                        .then(response=> {

                            // handle success
                            console.log(response);
                            this.GetUtilisateurs();


                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        });



                    },
                    onUpdate(id){
                        console.log('update',id);
                        axios.get('http://127.0.0.1:8000/utl/'+id+'/edit')
                        .then(response=> {

                            // handle success
                            console.log(response.data);
                            this.utilisateur =response.data;

                            console.log(this.utilisateur);


                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        });
                    },
                    onEdite(){
                        console.log("edite",this.utilisateur);

                        axios.put('http://127.0.0.1:8000/utl/'+this.utilisateur.id,this.utilisateur)
                        .then(response=> {

                            // handle success
                            console.log(response);

                            this.GetUtilisateurs();


                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        });
                    },
                    onDelete(id){
                        console.log("delete",id);
                        axios.delete('http://127.0.0.1:8000/utl/'+id)
                        .then(response=> {

                            // handle success
                            console.log(response);

                            this.GetUtilisateurs();                                         


                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        });
                    }
                               
                },
                mounted:function(){
                    this.GetUtilisateurs();   

                }
            });
            
    </script>
@endsection