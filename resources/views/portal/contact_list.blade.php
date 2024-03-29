@include('portal.inc.header')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contact List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Contacts</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover table-sm">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile Number</th>
                            <th>Phone Number</th>
                            <th>Work Number</th>
                            <th>E-Mail</th>
                            <th>Created On</th>
                            <th>Updated On</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

<script>
    var masterTable;
    window.addEventListener('load', function() {
        masterTable = $('#example2').DataTable({
            "paging": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "columnDefs": [
                {
                    'targets': 7,'searchable': false, 'orderable': false,
                    'render': function (data, type, full, meta)
                    {
                        var info = $('<div/>').text(data).html();
                        return (info.length> 0?'<button onclick="removeContact('+"'"+info+"'"+')" type="button" class="btn btn-danger btn-xs">Remove</button>':'');
                    }
                },
            ],
            "ajax": {
                "url": "/api/contact/view_all",
                "type": "POST",
                'beforeSend': function (request) {
                    request.setRequestHeader("Authorization", 'Bearer <?php echo session('AuthorizationToken'); ?>');
                },
                "data": function ( d )
                {
                    //d = theInstance.tableFilter
                },
                complete: function(data)
                {
                    //theInstance.dTableMount();
                },
                error: function (xhr, error, thrown)
                {
                    console.log(xhr);
                    console.log(error);
                    console.log(thrown);
                    if(xhr.status == 401)
                    {
                        alert("You have to Login Again")
                        window.location.href = "{{ env("APP_URL") }}logout";
                    }
                }
            },
        });
    });



    function removeContact(contactId) {
        if(confirm("Are you sure?"))
        {
            axios.delete(('{{ env("APP_URL") }}api/contact/remove/'+contactId), {
                headers: {
                    Authorization: 'Bearer <?php echo session('AuthorizationToken'); ?>'
                },
            })
                .then(function (response) {
                    masterTable.ajax.reload();
                })
                .catch(function (error) {
                    if (error.response) {
                        switch (error.response.status)
                        {
                            case 401:
                                alert("You have to Login Again");
                                window.location.href = "{{ env("APP_URL") }}logout";
                                break;
                        }
                    }
                });
        }
    }
</script>
<style>
    .pagination{
        float: right;
    }
</style>

@include('portal.inc.footer')
