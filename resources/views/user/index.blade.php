@extends('layout.base')

@section('title')
    <title>Prontoform At&t - Usuarios</title>
@endsection

@section('content')
<main id="crud" role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4" crud="user">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 class="h1">Usuarios</h1>
        <div class="btn btn-sm btn-bd-light my-2 my-md-0">
            <a href="#" class="btn btn-success pull-right" data-toggle="modal" data-target="#create">Nuevo... </a>
        </div>
    </div>
    <table id="User_table" class="table display">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in getCrudList">
                <td>@{{user.username}}</td>
                <td>
                    <a href="#" class="btn btn-warning" v-on:click.prevent="editCrud(user.id)">Editar</a>
                    <a href="#" class="btn btn-danger" v-on:click.prevent="deleteCrud(user.id)">Eliminar</a>
                </td>
            </tr>
        </tbody>
    </table>
    @include('user.create')
    @include('user.edit')
</main>
@endsection

@section('js')
<script>
    new Vue({
        el: '#crud',
        beforeMount: function() {
            this.crud = this.$el.attributes['crud'].value;
        },
        mounted: function(){
            this.getCrud();
        },
        data: {
            lists: [],
            errors: '',
            crud: '',
            getCrudList: [],
            getCrudDetail: '',
            user: {
                username: '',
                email: '',
                password: '',
                roles_id: '',
                prontoform_user: '',
                name: '',
                phone: '',
                address: '',
                city: '',
                state: '',
                zipcode: ''
            },
            passwordRep: '',
            roles: ''
        },
        methods: {
            getCrud: function(){
                axios.get('user/all').then(response => {
                    this.getCrudList = response.data;
                    axios.get('user/roles').then(response => {
                        this.roles = response.data;
                    });
                });
            },
            createCrud: function(){
                //----Validaciones
                let cuentaErrores = 0;
                let mensaje = [];
                
                // name:
                if(this.user.name.length > 45 || this.user.name.length ==0){
                        cuentaErrores ++;
                        mensaje.push('Nombre debe tener hasta 45 caracteres');
                    }

                // address:

                // zipcode:
                // city:
                // state:

                // phone:
                let phoneInt = parseInt(this.user.phone)
                    if(isNaN(phoneInt)){
                        mensaje.push('Teléfono debe ser un número');
                    }

                // email:
                expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    if ( !expr.test(this.user.email) ){
                        cuentaErrores ++;
                        mensaje.push('Email no válido');
                    }

                // prontoform_user: 
                    if(this.user.prontoform_user.length > 30 || this.user.prontoform_user.length ==0){
                        cuentaErrores ++;
                        mensaje.push('Nombre de usuario prontoform no válido');
                    }

                // username:
                if(this.user.username.length > 16 || this.user.username.length ==0){
                        cuentaErrores ++;
                        mensaje.push('Nombre de usuario debe tener hasta 50 caracteres');
                    }
                
                // password:
                let passwordValidate = this.user.password;
                if(passwordValidate.length > 255 || passwordValidate.length ==0){
                        cuentaErrores ++;
                        mensaje.push('Contraseña no válida');
                    }

                //repeat pass
                let passwordRepeat = this.passwordRep;
                if(passwordRepeat.length == 0){
                        cuentaErrores ++;
                        mensaje.push('Debe repetir la contraseña');
                }else if(passwordValidate != passwordRepeat){
                        cuentaErrores ++;
                        mensaje.push('La contraseña no coincide');
                } 

                //mensaje de error
                if(cuentaErrores == 0){
                        alert('Los datos ingresados son correctos');
                    } else{
                        var total = '\n';
                        mensaje.forEach( (input) =>{
                            total = total + '\n' + input;
                        });
                        alert('Se presentan errores en los siguientes campos: ' + total);
                    }

                axios.post(this.crud, this.user).then(response => {
                    this.getCrud(this.crud);
                    $('#create').modal('hide');
                    //toastr.success('Creada con éxito');
                }).catch(error => {
                    this.errors = 'Corrija para poder crear con éxito';
                });
            },
            editCrud: function(idCrud){
                $('.modal').modal('hide');
                axios.get(this.crud + '/' + idCrud + '/edit').then( response => {
                    this.user = response.data;
                    $('#edit').modal('show');
                });
            },
            updateCrud: function(idCrud){
                var sendUp = this.user;
                axios.put(this.crud + '/' + idCrud, sendUp).then( response => {
                    this.getCrud(this.crud);
                    $('#edit').modal('hide');
                    //toastr.success('Creada con éxito');
                    this.fd = [];
                });
            },
            deleteCrud: function(idCrud){
                if(window.confirm('¿Desea eliminar dicho registro?')){
                    axios.delete(this.crud + '/' + idCrud).then(response => { //eliminamos
                        $('.modal').modal('hide');
                        this.getCrud(); //listamos
                        //toastr.success('Eliminado correctamente'); //mensaje
                    });
                }
            },
            switchCrudCreate: function(idfunction, nameFunction){
                var fds = new FormData(document.getElementById(idfunction));
                axios.post(nameFunction, fds).then(response => {
                    this.getCrud(this.crud);
                    //toastr.success('Creada con éxito');
                    this.fd = [];
                }).catch(error => {
                    this.errors = 'Corrija para poder crear con éxito';
                    this.fd = [];
                });
            },
            switchCrudDelete: function(idfunction, nameFunction){
                axios.delete(nameFunction + '/' + idfunction).then(response => {
                    this.getCrud(this.crud);
                    //toastr.success('Creada con éxito');
                    this.fd = [];
                }).catch(error => {
                    this.errors = 'Corrija para poder crear con éxito';
                    this.fd = [];
                });
            },
        }
    });

</script>
@endsection
