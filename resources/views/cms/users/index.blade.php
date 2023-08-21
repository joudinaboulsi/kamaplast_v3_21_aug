
@extends('cms.layouts.app')

@section('content')

{!! Form::open() !!} {!! Form::close() !!}

<!-- Header and Main Information -->
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-4">
    <h2>Users Management</h2>
    <ol class="breadcrumb active">
      <li>
        <strong><a href="">Customers Subscribed to Your Website</a></strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-8">
    <div class="title-action">
      <a href="{{ route('cms_add_users') }}" class="btn btn-primary">Add User</a>
    </div>
  </div>
</div>

<!-- List of Users in Datatable -->
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>Website Users</h5>
          <div class="ibox-tools">
            <a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="table-responsive">
            <table id="client_table" class="footable table table-striped table-hover toggle-arrow-tiny default footable-loaded" >
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Newsletters</th>
                  <th># Of Orders</th>
                  <th>Amount spent</th>
                  <th>User Since</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/cms/js/custom_functions.js"></script>
<script type="text/javascript">

//SCRIPT LOADING THE USERS LIST ON THE SERVER SIDE
$(document).ready(function(){
  
  var users_details_route = '{{ route("cms_users_details", ":user_id") }}';

  $('#client_table').DataTable( {
     processing: true,
     serverSide: true,
     ajax: {
         url: '/load-users-table',
         type: 'POST',
         headers: { 'X-CSRF-Token': $('input[name=_token]').val() }
     },
     columns: [
             
             { data: "hidden",
               render: function (data, type, row)
               {
                 if(row.hidden == 0)
                  checked = 'checked';
                 else
                  checked = '';

                 return `<div class="controls">
                          <div class="switch">
                            <div class="onoffswitch">
                              <input type="checkbox" onclick="ajaxPublish(`+ row.id +`)" class="onoffswitch-checkbox" id="sales`+ row.id +`" name="sales`+ row.id +`" data-toggle="collapse" data-target="#demo" value="1"`+ checked +`>
                              <label class="onoffswitch-label" for="sales`+row.id+`">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                              </label>
                            </div>
                          </div>
                        </div>`;
               }  
              },
             { data: "name",
               render: function (data, type, row)
               {
                 return '<a href="'+ users_details_route.replace(':user_id', row.id) +'">'+ row.name +'</a>';
               }
             },
             { data: "email" },
             { data: "has_newsletters",
               render: function (data, type, row)
               {
                 if(row.has_newsletters == 0)
                  return '<i class="fa fa-times" style="color:red"></i>';
                 else
                  return '<i class="fa fa-check" style="color:#1AB394"></i>';
               } 
              },
             { data: "number_of_orders" },
             { data: "amount_spent" ,
               render: function (data, type, row)
               {
                  if(row.amount_spent != null)
                      return '$'+row.amount_spent;
                  else
                      return '';
               } 
              },
             { data: "since",
               render: function (data, type, row)
               {
                  var date = new Date(row.since);
                  var month = ("0" + (date.getMonth() + 1)).slice(-2);
                  return month + '/' + ("0" + date.getDate()).slice(-2) + '/' + date.getFullYear();
               }
             },
             { data: "id" ,
               render: function (data, type, row)
               {
                  return '<button type="button" id='+ row.id +' class="edit_btn delete_user" title="Delete user"><i class="fa fa-trash fa-lg"></i></button>';
               } 
              }
         ]
       } );


      // ==================== DELETE User  =====================
      ajaxDeleteRefresh('.delete_user', 'delete-user');

});

</script>

<script src="/cms/js/users/ajax-publish-users.js"></script>


@endsection