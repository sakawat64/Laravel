<?php
$data = array();
            $serial = 1;
            $list = Product::with('category','admin')->orderBy('id', 'ASC')->get();
        if($list){
            foreach($list as $r)
            { 
                $nestedData['serial'] = $serial++;    
                $nestedData['id'] = $r->id;
                $name_link = '<a href="product-details/'.$r->id.'">'.$r->product_name.'</a>';
                $nestedData['name'] = $name_link;
                $nestedData['title'] = strlen($r->product_title) > 18 ? substr($r->product_title, 0, 18)."..." : $r->product_title;
                $nestedData['description'] = strlen($r->product_description) > 18 ? substr($r->product_description, 0, 18)."..." : $r->product_description;
                $nestedData['category'] = $r->category->name;
                $nestedData['price'] = $r->product_price;
                $thumble = '<img width="80" height="60" src="/uploads/products/thumble/'.$r->thumble.'">';
                $nestedData['thumble'] = $thumble;
                if($r->feature_status == 1){
                    $fst = "Enable";
                    $ftype = "success";
                }
                else
                {
                    $fst = "Disable";
                    $ftype = "danger";
                }
                if($r->product_status == 1){
                    $st = "Active";
                    $type = "success";
                }
                else
                {
                    $st = "Inactive";
                    $type = "danger";
                }
                $nestedData['entry_by'] = $r->admin->name;
                $nestedData['updated_at'] = date('d-M-Y', strtotime($r->updated_at));
                $action = '<a href="product-edit/'.$r->id.'" class="edit-modal btn btn-xs" target="_blank" style="color:teal" data-cids="'.$r->id.'">
            <i class="fa fa-edit"></i></a>';
                $action .= ' <a href="#" style="color:red" id="deletemodal" class="delete-modal btn btn-xs bg-Teal" data-delcids="'.$r->id.'"><i class="fa fa-trash"></i></a>';
                $status = '<a href="#" id="st" data-dst="'.$r->id.'"><button type="button" class="btn btn-'.$type.' btn-xs">'.$st.'</button></a>';
                $feature_status = '<a href="#" id="fst" data-dfst="'.$r->id.'"><button type="button" class="btn btn-'.$ftype.' btn-xs">'.$fst.'</button></a>';
                $nestedData['action']= $action;
                $nestedData['status']= $status;
                $nestedData['feature']= $feature_status;
                $data[] = $nestedData;
            }
        }     
            $json_data = array(
                "data"	      => $data
            );
            echo json_encode($json_data);
?>

//Blade File Edit And Delete




@extends('../admin/layouts.admin_sidebar')
@section('title')
{{$title}}
@endsection
@section('css')
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  margin-left: 213px;
  width: 30px;
  height: 10px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
@endsection
@section('content')
<!--//*********************modal******************************-->  
<!-- Modal Start-->
<div class="modal fade" id="addCall" role="dialog">
    <div class="modal-dialog">
    <div>
    <div class="loader" id="loader"></div>
    </div>
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" id="Sub">
            @csrf
                <div class="modal-body">
                    <div class="row" style="padding:0 10px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <input type="text" name="product_name" class="form-control"
                                           placeholder="Provide Product Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <input type="text" name="product_title" class="form-control"
                                           placeholder="Provide Product Title" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <select name="category_id" class="form-control" required="">
                                        <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                <textarea class="form-control" name="product_description" id="ResponsiveDetelis" rows="2" placeholder="Provide Description" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <input type="text" name="product_price" class="form-control"
                                           placeholder="Product Price" required onkeypress="return numbersOnly(event)">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row"><span style="color:red">*</span> Select Product Thumble Image
                                <input required type="file" id="photo_addthumble" class="form-control" name="thumble">
                                <div class="pthumb"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row"><span style="color:red">*</span> Select Product All Image
                                <input required type="file" id="photo_add" class="form-control" name="images[]" placeholder="Select Multiple Photo" multiple>
                                <div class="gallery"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary" id="addcust"> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    
<!--//***************************************************--> 
<!--//*****************************************************************************-->
<div id="delModel"class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <span class="title">Are You sure want to delete?</span>
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-1">
                        <input type="hidden" class="form-control" id="del_id" >
                    </div>
                </div>  
            </div>
            <div class="col-md-12">
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-danger" id="delbut"> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>   
<!--//*****************************************************************************--> 
<section class="content">
    <div class="row">
        <div class="col-12">
    <p class="text-right">
        <a class="btn btn-primary btn-xs create-modal" id="add" style="color: white;margin-right: 21px;
    padding: 5px;">
            Add Product
        </a>
    </p>
    </div>
  </div>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body" id="fresh">
            <table id="hosTable" class="table table-bordered table-striped">
        <thead>
            <tr style="background: #4548ad;color:white;text-align:center">
                <th>SL</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Thumble</th>
                <th>Feature</th>
                <th>Status</th>
                <th>Entry By</th>
                <th>Last Update</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        </tfoot>
    </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>  
    </div>
</section>
@endsection
@section('js')
<script>
var table = $('#hosTable').DataTable(
   {
        "responsive": true,
         "autoWidth"   : false,
        "ordering": true,
        "paging" : true,
        "processing": true,"serverSide": false,
      //  "columnDefs": [{ responsivePriority: 1, targets: 0 }],
        "ajax":
            {
                "url":"<?= route('admin.product_list') ?>",
                "dataType":"json",
                "type":"POST",
                "data": function ( d )
                {
                    d._token= $('meta[name="csrf-token"]').attr('content');
                }
            },
        "columns":[
        {"data":"serial"},
        {"data":"name"},
        {"data":"category"},
        {"data":"title"},
        {"data":"description"},
        {"data":"price"},
        {"data":"thumble"},
        {"data":"feature"},
        {"data":"status"},
        {"data":"entry_by"},
        {"data":"updated_at"},
        {"data":"action","searchable":false,"orderable":false}
    ],
        "order": [[0, 'desc']]   
    });
$(document).on('click','#add', function() 
{
    $("#loader").hide();
    $('#addCall').modal('show');
});
$("#Sub").on('submit',function(event)
{  
    event.preventDefault();
    $("#loader").show();
    $.ajax({
        type: 'POST',
        url: "{{URL::to('admin/add-product')}}",
        data:new FormData(this),
        dataType:'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success:function(data)
        {
            if(data.type == 'success'){
                $('div.gallery > img').remove();
                $('div.pthumb > img').remove();
                $("#loader").hide();
                $('#addCall').modal('hide');
            }
            toastr[data.type](data.message);
            if(data.type == 'error')
            {
                return 0;
            }
            table.ajax.reload( null, false );
            document.getElementById("Sub").reset();
        }
    });
});
$(document).on('click', '#fst', function()
{
    let fid = $(this).data('dfst');
    $.ajax({
    type: 'POST',
    url: "{{URL::to('admin/feature-update')}}",
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      id: fid
    },
    success: function(data){
       table.ajax.reload( null, false );
       toastr[data.type](data.message);
    }
  });
});
$(document).on('click', '#st', function()
{
    let did = $(this).data('dst');
    $.ajax({
    type: 'POST',
    url: "{{URL::to('admin/status-update')}}",
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      id: did
    },
    success: function(data){
       table.ajax.reload( null, false );
       toastr[data.type](data.message);
    }
  });
});
$(document).on('click', '#deletemodal', function()
{
    let id = $(this).data('delcids');
    $("#del_id").val(id);
    $('#delModel').modal('show');
});
$("#delbut").on('click',function()
{
  $.ajax({
    type: 'POST',
    url: "{{URL::to('admin/delete-product')}}",
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      del_id: $('#del_id').val()
    },
    success: function(data){
       table.ajax.reload( null, false );
       $('#delModel').modal('hide');
       toastr[data.type](data.message);
    }
  });
});
    function numbersOnly(e)
    {
        var unicode=e.charCode? e.charCode : e.keyCode;
        if (unicode!==8 && e.key !=='.')
        {
            if ((unicode<2534||unicode>2543)&&(unicode<48||unicode>57))
            {
                return false;
            }
        }
    }
    $(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img width="80" height="50">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };
    var imagesPreviewTh = function(input, placeToInsertImagePreview) {
            var reader = new FileReader();

            reader.onload = function(event) {
                $($.parseHTML('<img width="80" height="50">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
            }

            reader.readAsDataURL(input.files[0]);

    };
    

    $('#photo_add').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
    $('#photo_addthumble').on('change', function() {
        imagesPreviewTh(this, 'div.pthumb');
    });
});
</script>
@endsection