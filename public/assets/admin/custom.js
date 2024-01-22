$(function () {
    $('body').on('keydown', 'input', function(e) {
        if (e.which === 32 &&  e.target.selectionStart === 0) {
            return false;
        }  
    });
    $('body').on('keydown', 'textarea', function(e) {
        if (e.which === 32 &&  e.target.selectionStart === 0) {
            return false;
        }  
    });
});

function deleteShowFun(id)
{
    console.log(id)
    swal({
        title: "Are you sure?",
        text: "Do you really want to delete this list?",
        icon: "warning",
        buttons: ["Cancel", "Delete Now"],
        dangerMode: true,
    })
    .then((willDelete) => {
            console.log('id', id)
        if (willDelete) {
            $.ajax({
                type:'POST',
                url: deleteRoute,
                data:{id:id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                console.log('data:', data )
                if(data == 1)
                {
                    swal({
                        title: "Deleted",
                        text: "The list has been deleted!",
                        icon: "success",
                    });
                    location.reload(true);
                }else{
                        swal("The list is not deleted!");
                    }
                }
            });
        } else {
            swal("The list is not deleted!");
        }
    });
}

function statusChange(id)
{
    swal({
        title: "Are you sure?",
        text: "Do you really want to change status?",
        icon: "warning",
        buttons: ["Cancel", "Change Now"],
        dangerMode: true,
    })
    .then((willDelete) => {
            console.log('id', id)
        if (willDelete) {
            $.ajax({
                type:'POST',
                url: statusRoute,
                data:{id:id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data){
                console.log('data:', data )
                if(data == 1)
                {
                    swal({
                        title: "Status",
                        text: "The status has been changed!",
                        icon: "success",
                    });
                    location.reload(true);
                }else{
                        swal("The status is not changed!");
                    }
                }
            });
        } else {
            swal("The list is not changed!");
        }
    });
}

$("#latitude").change(function(e){
    // e.preventDefault();
    var latitude = document.getElementById("latitude").value;
    if(latitude)
    {
        $.ajax({
            url: routeLatitude+latitude, 
            success: function(response){
                if(response.status == '200')
                {
                    document.getElementById("latitude-error").classList.add("hide");
                }else{
                    document.getElementById("latitude").value = "";
                    document.getElementById("latitude-error").classList.remove("hide");
                    document.getElementById("latitude-error").innerHTML = response.message;
                }
            },errors: function(errors){
                console.log('errors:', errors)
                alert("errors:.", errors)
            }
        });
    }
});
$("#longitude").change(function(e){
    // e.preventDefault();
    var longitude = document.getElementById("longitude").value;
    if(longitude)
    {
        $.ajax({
            url: routeLongitude+longitude, 
            success: function(response){
                if(response.status == '200')
                {
                    document.getElementById("longitude-error").classList.add("hide");
                }else{
                    document.getElementById("longitude").value = "";
                    document.getElementById("longitude-error").classList.remove("hide");
                    document.getElementById("longitude-error").innerHTML = response.message;
                }
            },errors: function(errors){
                console.log('errors:', errors)
                alert("errors:.", errors)
            }
        });
    }
   
});