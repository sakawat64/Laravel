<script type="text/javascript">
    $(document).on('click','#edit', function() 
{
    let id = $(this).data("value");
    $("#car_id").val(id);
    $("#loader_edit").hide();
    $('div.gallery_edit > img').remove();
    $('div.pthumb_edit > img').remove();
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: "{{URL::to('admin/car-edit')}}",
        data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        id: id
        },
        success: function(data){
            $('#car_name').val(data.car_name);
            $('#brand').val(data.brand);
            $('#model').val(data.model);
            $('#registration_year').val(data.registration_year);
            $('#condition').val(data.condition);
            $('#transmission').val(data.transmission);
            let items = data.fuel_type.split(',');
            $.each(items, function(i,e){
                $("#fuel_type option[value='" + e + "']").prop("selected", true);
            });
            $('#engine').val(data.engine);
            $('#miles').val(data.miles);
            $('#tax_token').val(data.tax_token);
            $('#fitness_date').val(data.fitness_date);
            $('#insurance_ex_date').val(data.insurance_ex_date  );
            $('#road_per_date').val(data.road_per_date);
            $('#video_link').val(data.video_link);
            $('#description').summernote("code",data.description);
            $('#price').val(data.price);
            $('#status').val(data.status);
            $('#pthumb_edit').append('<img width="80" height="50" src="/uploads/thumbnail/'+ data.thumble +'"/>');
            var mul = data.multiple_image.split(',');
            $.each(mul, function(index, value) {
                $('#gallery_edit').append('<img width="80" height="50" src="/uploads/photos/'+ value +'"/>');
            });
            $('#editCall').modal('show');
        }
    });
});
</script>