<!DOCTYPE html>
<html>
<head>
    {!! SEO::generate(true) !!}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="/img/favicon.png" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendors/kanban/css/minified-kanban.css') }}" type="text/css">

    <style type="text/css">
        .content{
            padding: 5px 10px 40px;
        }
        #kanban-board {
            overflow-x: auto;
            padding: 20px 0;
        }
        .kanban-item{
            padding: 0 !important;
        }

        .kanban-board .kanban-drag{
            height: calc(100vh - 300px);
            overflow: scroll;
            overflow-x: hidden;
        }
        .py-15{
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .py-10{
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .px-10{
            padding-right: 10px;
            padding-left: 10px;
        }

        .px-20{
            padding-right: 20px;
            padding-left: 20px;
        }

        .multiselect--active {
            z-index: 1000 !important;
        }

        .multiselect__placeholder {
            width: 100%;
        }

        body .multiselect__select {
            text-align: right;
            width: 100%;
            height: 100%;
            transition: none;
        }

        /* we use scale(1, -1) to flip the caret vertically since rotate no longer works,
         because of width and height 100% */
        body .multiselect--active .multiselect__select{
            transform: scale(1, -1);
        }
        /* ie11 fix end */
        /* reset to initial values for modern browsers since ie11 doesn't know @supports */
        @supports (height: unset) {
            body .multiselect__select {
                text-align: right;
                width: 40px;
                height: 38px;
                transition: .2s ease, -webkit-transform .2s ease
            }
        }
    </style>
</head>

<body class="fixed-navbar sidebar-scroll   hide-sidebar">
<!-- Simple splash screen-->
<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>{{ trans('app.name') }}</h1><p> </p><div class="spinner"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div> </div> </div>
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Header -->
<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version">
        <span>
           Laravel Kanban Board
        </span>
    </div>
    <nav role="navigation">
        <a href="{{ url('/') }}"><div class="header-link"><i class="fa fa-home"></i></div></a>
        <div class="small-logo">
            <span class="text-primary">Laravel Kanban Board</span>
        </div>


        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <a href="{{ url('/') }}">
                        <i class="pe-7s-upload pe-rotate-90"></i>
                    </a>
                </li>

            </ul>
        </div>
    </nav>
</div>

<div id="wrapper">
    <div id="app">
        <div class="small-header transition animated fadeIn">
            <div class="hpanel">
                <div class="panel-body">
                    <div class="row float-e-margins">
                        <div class="col-md-2">
                            <button class="btn btn-block btn-md btn-primary2 text-center" v-on:click="openAddBoardModalWindow()" type="button">
                                ADD COLUMN &nbsp;<i class="fa fa-plus-square"></i>
                            </button>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-block btn-md btn-primary2 text-center" v-on:click="openAddItemModalWindow()" type="button">
                                ADD TASK CARD &nbsp;<i class="fa fa-plus-square"></i>
                            </button>
                        </div>

                        <div class="col-md-8">
                            <p>The Kanban Board is based on Laravel, Vue.js and Jkanban, Suitable for building Scrum Board, You can create unlimited Columns and Cards, You can drag and drop cards from one column to another, Re order cards & columns, Add, Edit or Delete Cards and Columns and many more feature.. <a href="{{ url('/') }}">Read more</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div id="kanban-board" >

            </div>
        </div>

        <div class="modal fade" id="addBoard" tabindex="-1" role="dialog"  aria-hidden="true" data-keyboard="false" data-backdrop="static" >
            <div class="modal-dialog modal-md">
                <div class="modal-content">

                    <div class="modal-header py-10 px-20">
                        <h4 class="modal-title">Add Column</h4>

                    </div>

                    <div class="modal-body px-20" style="padding-bottom: 0px;">
                        <div id="addBoard-content">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr v-for="(item,index) in boardmodal" :key="index">
                                        <td>
                                            <input type="text" v-model="item.title" class="form-control input-md" placeholder="Column Title" autocomplete="off">
                                        </td>

                                        <td width="20">
                                            <button type="button" class="btn btn-md btn-danger2 " v-on:click="removeBoardItemModal(index)"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td class="p-sm" colspan="2">
                                            <button type="button" class="btn btn-warning2 pull-left" v-on:click="addMoreClickBoardModal()">Add More Column <i class="fa fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success pull-leftd" v-on:click="saveAndCloseBoardModal()">Save</button>
                        <button type="button" class="btn btn-default pull-leftd" v-on:click="discardAndCloseBoardModal()">Discard</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addItem" tabindex="-1" role="dialog"  aria-hidden="true" data-keyboard="false" data-backdrop="static" >
            <div class="modal-dialog modal-md">
                <div class="modal-content">

                    <div class="modal-header py-10 px-20">
                        <h4 class="modal-title">Add Task Card</h4>

                    </div>

                    <div class="modal-body px-20" style="padding-bottom: 0px;">
                        <div id="addItem-content">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Select Board</label>
                                        <multiselect v-model="itemCreate.board" open-direction="bottom" :options="boards" placeholder="Select a Board" label="title" track-by="id" >
                                            {{--<template slot="singleLabel" slot-scope="{ option }"> <strong>@{{ option.title }}</strong> <i class="fa fa-check text-success"></i></template>--}}
                                        </multiselect>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Task Name</label>
                                        <input type="text" class="form-control input-md" name="taskname" v-model="itemCreate.taskname" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Task Description</label>
                                        <textarea class="form-control" name="remark" v-model="itemCreate.remark"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control input-md" name="name" v-model="itemCreate.name" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <multiselect v-model="itemCreate.gender" open-direction="bottom" :options="gender_options" placeholder="Select Gender"  >
                                        </multiselect>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <multiselect v-model="itemCreate.country" open-direction="bottom" :options="country_options" placeholder="Select Country"  :taggable="true" tag-placeholder="Add this as a new country" @tag="addNewCountry">
                                        </multiselect>
                                    </div>
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label>Hiring Agency</label>
                                        <multiselect v-model="itemCreate.agency" open-direction="bottom" :options="agency_options" placeholder="Select Agency" :taggable="true" tag-placeholder="Add this as a new agency" @tag="addNewAgency">
                                        </multiselect>
                                    </div>
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label>Position</label>
                                        <multiselect v-model="itemCreate.position" open-direction="bottom" :options="position_options" placeholder="Select a Position" :taggable="true" tag-placeholder="Add this as a new Position" @tag="addNewPosition">
                                        </multiselect>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control input-md" name="phone" v-model="itemCreate.phone" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control input-md" name="email" v-model="itemCreate.email" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success pull-leftd" v-on:click="saveAndCloseItemModal()">Save</button>
                        <button type="button" class="btn btn-default pull-leftd" v-on:click="discardAndCloseItemModal()">Discard</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="editItem" tabindex="-1" role="dialog"  aria-hidden="true" data-keyboard="false" data-backdrop="static" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header py-10 px-20">
                        <h4 class="modal-title">Edit Task Card</h4>

                    </div>

                    <div class="modal-body px-20" style="padding-bottom: 0px;">
                        <div id="editItem-content">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Task Name</label>
                                        <input type="text" class="form-control input-md" name="taskname" v-model="itemEdit.taskname" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Task Description</label>
                                        <textarea class="form-control" name="remark" v-model="itemEdit.remark"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control input-md" name="name" v-model="itemEdit.name" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <multiselect v-model="itemEdit.gender" open-direction="bottom" :options="gender_options" placeholder="Select Gender"  >
                                        </multiselect>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control input-md" name="phone" v-model="itemEdit.phone" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control input-md" name="email" v-model="itemEdit.email" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <multiselect v-model="itemEdit.country" open-direction="bottom" :options="country_options" placeholder="Select Country"  :taggable="true" tag-placeholder="Add this as a new country" @tag="addNewCountryOnUpdate">
                                        </multiselect>
                                    </div>
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label>Hiring Agency</label>
                                        <multiselect v-model="itemEdit.agency" open-direction="bottom" :options="agency_options" placeholder="Select Agency" :taggable="true" tag-placeholder="Add this as a new agency" @tag="addNewAgencyOnUpdate">
                                        </multiselect>
                                    </div>
                                </div>
                                <div class="col-md-6" style="display: none;">
                                    <div class="form-group">
                                        <label>Position</label>
                                        <multiselect v-model="itemEdit.position" open-direction="bottom" :options="position_options" placeholder="Select a Position" :taggable="true" tag-placeholder="Add this as a new Position" @tag="addNewPositionOnUpdate">
                                        </multiselect>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success pull-leftd" v-on:click="updateAndCloseItemModal()">Save</button>
                        <button type="button" class="btn btn-default pull-leftd" v-on:click="discardEditAndCloseItemModal()">Discard</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="auditItem" tabindex="-1" role="dialog"  aria-hidden="true" data-keyboard="false" data-backdrop="static" >
            <div class="modal-dialog modal-md">
                <div class="modal-content">

                    <div class="modal-header py-10 px-20">
                        <h4 class="modal-title">Time span history</h4>
                        <button type="button" class="btn btn-default pull-right" v-on:click="closeItemAuditModal()">Close</button>
                    </div>

                    <div class="modal-body px-20" style="padding-bottom: 0px;">
                        <div id="auditItem-content">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-leftd" v-on:click="closeItemAuditModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer-->
    <footer class="footer">
        <span class="pull-right">
             Developed Techies in mind
        </span>
        Copyright © {{ date('Y') }} {{ config('app.name') }}, All rights reserved.
    </footer>

</div>


<script src="{{ asset('vendors/kanban/js/minified-kanban.js') }}"></script>
<script type="text/javascript">
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
<script src="https://unpkg.com/vue/dist/vue.min.js"></script>

<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=default,Array.prototype.find,Array.prototype.findIndex"></script>
<script type="text/javascript">
    (function () {
        if ( typeof NodeList.prototype.forEach === "function" ) return false;
        NodeList.prototype.forEach = Array.prototype.forEach;
    })();

    window.recruitment_kanban = null;
    var app = new Vue({
        el : '#app',
        components: {
            Multiselect: window.VueMultiselect.default
        },
        data: {
            boards: [],
            boardmodal: [
                {title:''}
            ],

            country_options: [],
            gender_options: ['Male','Female'],
            agency_options: [],
            position_options: [],

            itemCreate: {
                taskname: '',
                remark: '',
                photo: '',
                name: '',
                gender: '',
                country: '',
                agency: '',
                position: '',
                phone: '',
                email: '',
                board: ''
            },

            itemEdit: {
                id: '',
                itemIdPrefixed: '',
                itemBoardId: '',
                itemIndex: '',
                boardIndex: '',
                taskname: '',
                remark: '',
                photo: '',
                name: '',
                gender: '',
                country: '',
                agency: '',
                position: '',
                phone: '',
                email: ''
            }
        },
        computed:{
            boardValidateAndFilter: function () {
                return this.boardmodal.filter(function(item) {
                    return item.title != '';
                });
            },
        },
        methods: {

            openAddBoardModalWindow: function(){
                $('#addBoard').modal('show');
            },

            addMoreClickBoardModal: function(){
                this.boardmodal.push({ title:'' });
            },

            removeBoardItemModal: function(index){
                if(this.boardmodal.length > 1){
                    this.boardmodal.splice(index, 1);
                }else{
                    showNotificationToast({
                        message: "You cannot delete the last raw.",
                        type: "error"
                    });
                }
            },

            saveAndCloseBoardModal: function(){
                var self = this;

                if(self.boardValidateAndFilter.length > 0){
                    $.ajax({
                        type: "POST",
                        url: '/techalyst/kanban/create/board',
                        data: {
                            boarditems: JSON.stringify(self.boardValidateAndFilter)
                        },
                        dataType : "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend:   function(){
                            AppCommonFunction.ShowWaitBlock();
                        }
                    }).done(function(data) {
                        AppCommonFunction.HideWaitBlock();

                        if(data.length > 0){
                            data.forEach(function(item,index){
                                self.boards.push({
                                    id: item.id,
                                    boardId: item.boardId,
                                    title: item.title,
                                    item: item.item
                                });
                                var item_count_span_id = 'total_item_'+item.boardId;
                                window.recruitment_kanban.addBoards(
                                    [{
                                        "id": item.boardId,
                                        "title": item.title + '<i class="px-10 fa fa-arrow-right"></i><span class="text-success" id="'+item_count_span_id+'">'+item.item.length+'</span> <small class="text-success">Active Tasks</small> <div class="button_new" style="float: right;"><button class="btn btn-circle btn-danger2 btn-md delete-board" type="button" id="delete-'+item.boardId+'"><i class="fa fa-trash-o"></i></button></div>',
                                        "item": item.item
                                    }]
                                )
                            });
                        }

                        self.boardmodal = [{ title: ''}];
                        $('#addBoard').modal('hide');

                    }).fail(function(data) {
                        AppCommonFunction.HideWaitBlock();
                        AppCommonFunction.RenderAjaxFailError(data);
                    });
                }else{
                    showNotificationToast({
                        message: 'Please fill the board title to add one or more board',
                        type: "error"
                    });
                }



            },

            discardAndCloseBoardModal: function(){
                this.boardmodal = [{ title:'' }];
                $('#addBoard').modal('hide');
            },

            openAddItemModalWindow: function(){
                if(this.boards.length > 0){
                    $('#addItem').modal('show');
                }else{
                    showNotificationToast({
                        message: 'Please Create one or more board Prior to ADD Task!.',
                        type: "warning"
                    });
                }

            },

            saveAndCloseItemModal: function(){
                var self = this;

                $.ajax({
                    type: "POST",
                    url: '/techalyst/kanban/create/item',
                    data: {
                        taskname: self.itemCreate.taskname,
                        remark: self.itemCreate.remark,
                        name: self.itemCreate.name,
                        gender: self.itemCreate.gender,
                        country: self.itemCreate.country,
                        agency: self.itemCreate.agency,
                        position: self.itemCreate.position,
                        phone: self.itemCreate.phone,
                        email: self.itemCreate.email,
                        boardId: self.itemCreate.board ? self.itemCreate.board.id : ''
                    },
                    dataType : "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend:   function(){
                        AppCommonFunction.ShowWaitBlock();
                    }
                }).done(function(data) {
                    AppCommonFunction.HideWaitBlock();

                    //insert new candidate to UI
                    window.recruitment_kanban.addElement(
                        self.itemCreate.board.boardId,
                        {
                            "id": data.item_prefixed_id ,
                            "title": data.rendered_html,
                        }
                    );

                    //reset board array, and options for select list arrays

                    var boardIndex = self.boards.findIndex(function(item){ return item.id == self.itemCreate.board.id});
                    self.boards[boardIndex].item.push({
                        id: data.item_prefixed_id,
                        title: data.rendered_html,
                        taskname: data.taskname,
                        remark: data.remark,
                        name: data.name,
                        gender: data.gender,
                        country: data.country,
                        agency: data.agency,
                        position: data.position,
                        phone: data.phone,
                        email: data.email
                    });
                    //self.fetchOptionsData();
                    self.refreshTotalItemCount();

                    self.itemCreate.taskname = '';
                    self.itemCreate.remark = '';
                    self.itemCreate.name = '';
                    self.itemCreate.gender = '';
                    self.itemCreate.country = '';
                    self.itemCreate.agency = '';
                    self.itemCreate.position = '';
                    self.itemCreate.phone = '';
                    self.itemCreate.email = '';
                    self.itemCreate.board = '';

                    $('#addItem').modal('hide');

                }).fail(function(data) {
                    AppCommonFunction.HideWaitBlock();
                    AppCommonFunction.RenderAjaxFailError(data);
                });
            },

            discardAndCloseItemModal: function(){
                this.itemCreate.taskname = '';
                this.itemCreate.remark = '';
                this.itemCreate.name = '';
                this.itemCreate.gender = '';
                this.itemCreate.country = '';
                this.itemCreate.agency = '';
                this.itemCreate.position = '';
                this.itemCreate.phone = '';
                this.itemCreate.email = '';
                this.itemCreate.board = '';

                $('#addItem').modal('hide');
            },


            addNewCountry: function(newItem){
                this.country_options.push(newItem);
                this.itemCreate.country = newItem;
            },

            addNewAgency: function(newItem){
                this.agency_options.push(newItem);
                this.itemCreate.agency = newItem;
            },

            addNewPosition: function(newItem){
                this.position_options.push(newItem);
                this.itemCreate.position = newItem;
            },

            addNewCountryOnUpdate: function(newItem){
                this.country_options.push(newItem);
                this.itemEdit.country = newItem;
            },

            addNewAgencyOnUpdate: function(newItem){
                this.agency_options.push(newItem);
                this.itemEdit.agency = newItem;
            },

            addNewPositionOnUpdate: function(newItem){
                this.position_options.push(newItem);
                this.itemEdit.position = newItem;
            },


            fetchMasterData: function () {
                var self = this;
                self.boards = [];

                $.ajax({
                    url: '/techalyst/kanban/board',
                    data: {
                        masterData: 1
                    },
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {
                    if (data.boards) {
                        //self.boards = data.boards;
                        if(data.boards.length > 0){
                            data.boards.forEach(function(item,index){

                                self.boards.push({
                                    id: item.id,
                                    boardId: item.boardId,
                                    title: item.title,
                                    item: item.item
                                });

                                var item_count_span_id = 'total_item_'+item.boardId;
                                window.recruitment_kanban.addBoards(
                                    [{
                                        "id": item.boardId,
                                        "title": item.title + '<i class="px-10 fa fa-arrow-right"></i><span class="text-success" id="'+item_count_span_id+'">'+item.item.length+'</span> <small class="text-success">Active Tasks</small> <div class="button_new" style="float: right;"><button class="btn btn-circle btn-danger2 btn-md delete-board" type="button" id="delete-'+item.boardId+'"><i class="fa fa-trash-o"></i></button></div>',
                                        "item": item.item
                                    }]
                                )
                            });
                        }
                    }


                }).fail(function(data) {
                    AppCommonFunction.RenderAjaxFailError(data);
                });
            },

            fetchOptionsData: function () {
                var self = this;

                $.ajax({
                    url: '/techalyst/kanban/board',
                    data: {
                        masterData: 1
                    },
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function(data) {

                    if (data.country_options) {
                        self.country_options = data.country_options;
                    }

                    if(data.agency_options){
                        self.agency_options = data.agency_options;
                    }
                    if(data.position_options){
                        self.position_options = data.position_options;
                    }

                }).fail(function(data) {
                    AppCommonFunction.RenderAjaxFailError(data);
                });
            },

            refreshTotalItemCount: function(){
                var self = this;
                self.boards.forEach(function(item,index){
                    var item_count_span_id = 'total_item_'+item.boardId;
                    $('#'+item_count_span_id).text(item.item.length);
                });
            },

            moveBoardItem : function (itemId,newBoardId,oldBoardId) {
                var self = this;
                $.ajax({
                    url: '/techalyst/kanban/move/item',
                    data: {
                        itemId: itemId,
                        oldBoardId: oldBoardId,
                        newBoardId: newBoardId
                    },
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend:   function(){
                        AppCommonFunction.ShowWaitBlock();
                    }
                }).done(function(data) {

                    var newBoardIndex = self.boards.findIndex(function(item){ return item.boardId == newBoardId});
                    var oldBoardIndex = self.boards.findIndex(function(item){ return item.boardId == oldBoardId});

                    var itemCopy = null;
                    for (var i = 0;i < self.boards[oldBoardIndex].item.length; i++){
                        if(self.boards[oldBoardIndex].item[i].id == itemId){
                            itemCopy = JSON.parse(JSON.stringify(self.boards[oldBoardIndex].item[i]));
                            self.boards[oldBoardIndex].item.splice(i, 1);
                        }
                    }
                    self.boards[newBoardIndex].item.push(itemCopy);

                    self.refreshTotalItemCount();
                    AppCommonFunction.HideWaitBlock();

                }).fail(function(data) {
                    AppCommonFunction.HideWaitBlock();
                    AppCommonFunction.RenderAjaxFailError(data);
                });
            },

            reorderBoard: function(boardSorted){
                var self = this;
                $.ajax({
                    url: '/techalyst/kanban/reorder/board',
                    data: {
                        boardSorted: JSON.stringify(boardSorted)
                    },
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend:   function(){
                        AppCommonFunction.ShowWaitBlock();
                    }
                }).done(function(data) {

                    AppCommonFunction.HideWaitBlock();

                }).fail(function(data) {
                    AppCommonFunction.HideWaitBlock();
                    AppCommonFunction.RenderAjaxFailError(data);
                });
            },

            reorderBoardItem: function(itemSorted){

                var self = this;
                $.ajax({
                    url: '/techalyst/kanban/reorder/item',
                    data: {
                        itemSorted: JSON.stringify(itemSorted)
                    },
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend:   function(){
                        AppCommonFunction.ShowWaitBlock();
                    }
                }).done(function(data) {

                    AppCommonFunction.HideWaitBlock();

                }).fail(function(data) {
                    AppCommonFunction.HideWaitBlock();
                    AppCommonFunction.RenderAjaxFailError(data);
                });

            },


            updateAndCloseItemModal: function () {
                var self = this;

                $.ajax({
                    type: "POST",
                    url: '/techalyst/kanban/edit/item',
                    data: {
                        id: self.itemEdit.id,
                        taskname: self.itemEdit.taskname,
                        remark: self.itemEdit.remark,
                        name: self.itemEdit.name,
                        gender: self.itemEdit.gender,
                        country: self.itemEdit.country,
                        agency: self.itemEdit.agency,
                        position: self.itemEdit.position,
                        phone: self.itemEdit.phone,
                        email: self.itemEdit.email
                    },
                    dataType : "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend:   function(){
                        AppCommonFunction.ShowWaitBlock();
                    }
                }).done(function(data) {
                    AppCommonFunction.HideWaitBlock();

                    //insert modified candidate to UI
                    window.recruitment_kanban.replaceElement(
                        self.itemEdit.itemIdPrefixed,
                        {
                            "id": data.item_prefixed_id ,
                            "title": data.rendered_html,
                        }
                    );

                    //reset board array, and options for select list arrays

                    self.boards[self.itemEdit.boardIndex].item[self.itemEdit.itemIndex] = {
                        id: data.item_prefixed_id,
                        title: data.rendered_html,
                        taskname: data.taskname,
                        remark: data.remark,
                        name: data.name,
                        gender: data.gender,
                        country: data.country,
                        agency: data.agency,
                        position: data.position,
                        phone: data.phone,
                        email: data.email
                    };

                    self.itemEdit.id = '';
                    self.itemEdit.itemIdPrefixed = '';
                    self.itemEdit.itemBoardId = '';
                    self.itemEdit.itemIndex = '';
                    self.itemEdit.boardIndex = '';
                    self.itemEdit.taskname = '';
                    self.itemEdit.remark = '';
                    self.itemEdit.name = '';
                    self.itemEdit.gender = '';
                    self.itemEdit.country = '';
                    self.itemEdit.agency = '';
                    self.itemEdit.position = '';
                    self.itemEdit.phone = '';
                    self.itemEdit.email = '';

                    $('#editItem').modal('hide');

                }).fail(function(data) {
                    AppCommonFunction.HideWaitBlock();
                    AppCommonFunction.RenderAjaxFailError(data);
                });
            },

            discardEditAndCloseItemModal: function () {
                this.itemEdit.id = '';
                this.itemEdit.itemIdPrefixed = '';
                this.itemEdit.itemBoardId = '';
                this.itemEdit.itemIndex = '';
                this.itemEdit.boardIndex = '';
                this.itemEdit.taskname = '';
                this.itemEdit.remark = '';
                this.itemEdit.name = '';
                this.itemEdit.gender = '';
                this.itemEdit.country = '';
                this.itemEdit.agency = '';
                this.itemEdit.position = '';
                this.itemEdit.phone = '';
                this.itemEdit.email = '';

                $('#editItem').modal('hide');
            },

            closeItemAuditModal: function () {
                $('#auditItem').modal('hide');
            }

        },
        watch:{

        },
        mounted: function(){
            var self = this;

            window.recruitment_kanban = new jKanban({
                element: '#kanban-board',
                gutter: '10px',
                widthBoard: '400px',
                //addItemButton: true,
                buttonContent: '<i class="fa fa-plus"></i>',
                boards: [],
                dragBoards: true,

                buttonClick: function (el, boardId) {
                    var boardIndex = self.boards.findIndex(function(item){ return item.boardId == boardId});
                    self.itemCreate.board = self.boards[boardIndex];
                    $('#addItem').modal('show');
                },
                dropEl: function (el, target, source, sibling) {
                    //console.log(source);
                    var itemId = el.dataset.eid;
                    var droppedFromBoard = source.parentNode.dataset.id;
                    var droppedToBoard = target.parentNode.dataset.id;

                    //i need make sure this is not triggerd by dragging in the same board
                    if(droppedFromBoard != droppedToBoard){
                        self.moveBoardItem(itemId,droppedToBoard,droppedFromBoard);
                    }

                    //sorting call
                    var _target = $(target.parentElement);
                    var sorted = [];
                    var nodes = window.recruitment_kanban.getBoardElements(_target.data("id"));
                    var currentOrder = 0;
                    nodes.forEach(function(value, index, array) {
                        sorted.push({
                            "id": $(value).data("eid"),
                            "order": currentOrder++
                        })
                    });
                    self.reorderBoardItem(sorted);
                },

                dragendBoard: function (el) {
                    var boardIds = $('.kanban-board').map(function(){
                        return $(this).attr("data-id")
                    }).get();

                    var sorted = [];
                    var currentOrder = 0;
                    boardIds.forEach(function(value, index, array) {
                        sorted.push({
                            "id": value,
                            "order": currentOrder++
                        })
                    });

                    self.reorderBoard(sorted);
                    //console.log(JSON.stringify(sorted));
                }

            });

            self.fetchMasterData();

            $('.audit-item').live("click",function(){
                var itemId = $(this).attr('id');
                var itemIdParts = itemId.split('-');
                var itemIdPrefixed = 'item'+itemIdParts[1];
                var itemBoardId = window.recruitment_kanban.getParentBoardID(itemIdPrefixed);

                $.ajax({
                    type: "POST",
                    url: '/techalyst/kanban/audit/item',
                    data: {'id': itemIdParts[1]},
                    dataType : "json",
                    beforeSend:   function(){
                        AppCommonFunction.ShowWaitBlock();
                    }
                }).done(function(data) {
                    AppCommonFunction.HideWaitBlock();
                    $("#auditItem-content").html(data.Message);

                    $('#auditItem').modal('show');
                }).fail(function(data) {
                    AppCommonFunction.HideWaitBlock();
                    AppCommonFunction.RenderAjaxFailError(data);

                });
            });

            $('.delete-item').live("click",function(){
                var itemId = $(this).attr('id');
                var itemIdParts = itemId.split('-');
                var itemIdPrefixed = 'item'+itemIdParts[1];
                var itemBoardId = window.recruitment_kanban.getParentBoardID(itemIdPrefixed);

                var boardIndex = self.boards.findIndex(function(item){ return item.boardId == itemBoardId});
                var itemIndex = null;
                for (var i = 0;i < self.boards[boardIndex].item.length; i++){
                    if(self.boards[boardIndex].item[i].id == itemIdPrefixed){
                        itemIndex = i;
                    }
                }

                swal({
                        title: "Are you sure you want to delete the task?",
                        text: "Your will not be able to recover!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel",
                        closeOnConfirm: true,
                        showLoaderOnConfirm: false
                    },
                    function () {

                        $.ajax({
                            type: "POST",
                            url: '/techalyst/kanban/delete/item',
                            data: {'id': itemIdParts[1]},
                            dataType : "json",
                            beforeSend:   function(){
                                AppCommonFunction.ShowWaitBlock();
                            }
                        }).done(function(data) {
                            AppCommonFunction.HideWaitBlock();
                            showNotificationSwal({
                                Status: data.Status,
                                Message: data.Message
                            });

                            self.boards[boardIndex].item.splice(itemIndex, 1);
                            window.recruitment_kanban.removeElement(itemIdPrefixed);
                            self.refreshTotalItemCount();

                        }).fail(function(data) {
                            AppCommonFunction.HideWaitBlock();
                            AppCommonFunction.RenderAjaxFailError(data);

                        });

                    });

            });

            $('.edit-item').live("click",function(){
                var itemId = $(this).attr('id');
                var itemIdParts = itemId.split('-');
                var itemIdPrefixed = 'item'+itemIdParts[1];
                var itemBoardId = window.recruitment_kanban.getParentBoardID(itemIdPrefixed);


                var boardIndex = self.boards.findIndex(function(item){ return item.boardId == itemBoardId});
                var itemIndex = null;
                var itemCopy = null;
                for (var i = 0;i < self.boards[boardIndex].item.length; i++){
                    if(self.boards[boardIndex].item[i].id == itemIdPrefixed){
                        itemIndex = i;
                        itemCopy = JSON.parse(JSON.stringify(self.boards[boardIndex].item[i]));
                    }
                }

                self.itemEdit.id = itemIdParts[1];
                self.itemEdit.itemIdPrefixed = itemIdPrefixed;
                self.itemEdit.itemBoardId = itemBoardId;

                self.itemEdit.itemIndex = itemIndex;
                self.itemEdit.boardIndex = boardIndex;

                self.itemEdit.taskname = itemCopy.taskname;
                self.itemEdit.remark = itemCopy.remark;
                self.itemEdit.name = itemCopy.name;
                self.itemEdit.gender = itemCopy.gender;
                self.itemEdit.country = itemCopy.country;
                self.itemEdit.agency = itemCopy.agency;
                self.itemEdit.position = itemCopy.position;
                self.itemEdit.phone = itemCopy.phone;
                self.itemEdit.email = itemCopy.email;

                $('#editItem').modal('show');
            });

            $('.delete-board').live("click",function(){
                var boardId = $(this).attr('id');
                var boardIdParts = boardId.split('-');
                var boardIdPrefixed = boardIdParts[1];


                var boardIndex = self.boards.findIndex(function(item){ return item.boardId == boardIdPrefixed});

                swal({
                        title: "Are you sure you want to delete the board?",
                        text: "all Tasks will be deleted on the Board and you cannot recover!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel",
                        closeOnConfirm: true,
                        showLoaderOnConfirm: false
                    },
                    function () {

                        $.ajax({
                            type: "POST",
                            url: '/techalyst/kanban/delete/board',
                            data: {'boardIdPrefixed': boardIdPrefixed},
                            dataType : "json",
                            beforeSend:   function(){
                                AppCommonFunction.ShowWaitBlock();
                            }
                        }).done(function(data) {
                            AppCommonFunction.HideWaitBlock();

                            self.boards.splice(boardIndex, 1);
                            window.recruitment_kanban.removeBoard(boardIdPrefixed);

                            showNotificationSwal({
                                Status: data.Status,
                                Message: data.Message
                            });
                        }).fail(function(data) {
                            AppCommonFunction.HideWaitBlock();
                            AppCommonFunction.RenderAjaxFailError(data);

                        });

                    });

            });

        },
        updated: function () {

        }
    });

</script>
</body>
</html>
