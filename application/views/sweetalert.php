<script>
function SuccessAlert(txt){
    swal({
        title: "Good job!",
        text: txt,
        icon: "success",
    });
}

function ErrorAlert(txt){
    swal({
        title: "เสียใจด้วย !!!",
        text: txt,
        icon: "error",
    });
}
</script>