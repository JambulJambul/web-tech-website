<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contacts</title>
    <script src="https://kit.fontawesome.com/e391ce7786.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="assets/style/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/datatables.min.css" />
</head>

<body>
    <div class="p-5 text-white">
        <h4 class="text-center text-white">Contacts</h4>
        <form method="post" class="form-data" id="form-data">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="hidden" name="id" id="id">
                        <input type="text" name="name" id="name" class="form-control" required="true">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" required="true">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Photo</label>
                        <input type="file" name="image" id="image" class="form-control p-1" accept=".jpg,.png,.jpeg" required="true">
                    </div>
                </div>
            </div>

            <div class="form-group mt-2">
                <label>Address</label>
                <textarea name="address" id="address" class="form-control" required="true"></textarea>
            </div>

            <div class="form-group mt-2">
                <div class="alert alert-info" id="alert"></div>
            </div>

            <div class="form-group d-flex justify-content-center">
                <button type="button" name="simpan" id="simpan" class="btn btn-primary mt-0">
                    Simpan
                </button>
                <button type="button" name="update" id="update" class="btn btn-primary mt-0">
                    Update
                </button>
            </div>
        </form>

        <table id="example" class="table table-striped table-bordered text-white" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="tbody">
            </tbody>
        </table>
    </div>

    <section>
        <div class="buttons">
            <a href="/">
                <button href="" class="btn-start">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </a>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/datatables.min.js"></script>

    <script>
        function loadData() {
            $('.tbody').html('');
            $('#example').DataTable().destroy();
            $.ajax({
                url: "/api/contacts",
                type: "GET",
                contentType: "application/json;charset=utf-8",
                dataType: "json",
                success: function(result) {
                    var html = '';
                    var number = 1;
                    console.log(result.data);
                    $.each(result.data, function(key, item) {
                        html += '<tr>';
                        html += '<td>' + number + '</td>';
                        html += '<td><img src="assets/img/' + item.image + '" style="width: 50px; height: auto;" /></td>';
                        html += '<td>' + item.name + '</td>';
                        html += '<td>' + item.phone + '</td>';
                        html += '<td>' + item.address + '</td>';
                        html += '<td><a href="#" class="btn btn-warning" data-id="' + item.id + '" onclick="editContact(' + item.id + ')"><span class="bi bi-pencil"></span></a> <a href="#" class="btn btn-danger" data-id="' + item.id + '" onclick="deleteContact(' + item.id + ')"><span class="bi bi-trash"></span></a></td>';
                        html += '</tr>';
                        number++;
                    });
                    $('.tbody').html(html);
                    $('#example').DataTable();
                },
                error: function(errormessage) {
                    alert(errormessage.responseText);
                }
            });
        }

        function editContact($id) {
            formReset();
            $.ajax({
                url: "/api/contacts/" + $id,
                type: "GET",
                contentType: "application/json;charset=utf-8",
                dataType: "json",
                success: function(result) {
                    $('#id').val(result.data.id);
                    $('#name').val(result.data.name);
                    $('#phone').val(result.data.phone);
                    $('#address').val(result.data.address);
                    $('#simpan').hide();
                    $('#update').show();
                },
                error: function(errormessage) {
                    alert(errormessage.responseText);
                }
            });
        }

        function deleteContact($id) {
            $.ajax({
                url: "/api/contacts/" + $id,
                type: "DELETE",
                contentType: "application/json;charset=utf-8",
                dataType: "json",
                success: function(result) {
                    loadData();
                    $('#alert').show();
                    $('#alert').text('Success: ' + result.message);
                },
                error: function(errormessage) {
                    alert(errormessage.responseText);
                }
            });
        }

        function formReset() {
            $('#form-data')[0].reset();
            $('#simpan').show();
            $('#update').hide();
            $('#alert').hide();
            loadData();
        }

        $("#update").click(function() {
            var id = $('#id').val();
            console.log("ID Button"+id);
            $.ajax({
                type: 'POST',
                url: "/api/contacts/"+id,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                data: new FormData(document.querySelector('.form-data')),
                success: function(result) {
                    formReset();
                    loadData();
                    $('#alert').show();
                    $('#alert').text('Success: ' + result.message);
                },
                error: function(response) {
                    loadData();
                    $('#alert').show();
                    $('#alert').text('Error: ' + JSON.parse(response.responseText).message);
                }
            });
        });

        $("#simpan").click(function() {
            $.ajax({
                type: 'POST',
                url: "/api/contacts",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                data: new FormData(document.querySelector('.form-data')),
                success: function(result) {
                    formReset();
                    loadData();
                    $('#alert').show();
                    $('#alert').text('Success: ' + result.message);
                },
                error: function(response) {
                    loadData();
                    $('#alert').show();
                    $('#alert').text('Error: ' + JSON.parse(response.responseText).message);
                }
            });
        });

        $(document).ready(function() {
            $('#alert').hide();
            $('#simpan').show();
            $('#update').hide();
            loadData();
        });
    </script>

</body>

</html>